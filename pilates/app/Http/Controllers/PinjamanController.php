<?php

namespace App\Http\Controllers;

use App\Models\Pinjaman;
use App\Models\DetailPinjaman;
use App\Models\Alat;
use App\Models\DetailPengembalian;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\AuditLogService;
use App\Http\Requests\StorePinjamanRequest;
use App\Http\Requests\StoreReturnRequest;
use Illuminate\Support\Facades\DB;

class PinjamanController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $status = $request->query('status');
        $query = Pinjaman::with(['peminjam', 'details.alat']);
        if ($q) {
            $query->whereHas('peminjam', function($q2) use ($q) { $q2->where('name', 'like', "%{$q}%"); })
                  ->orWhere('status', $q);
        }
        if ($status) {
            $query->where('status', $status);
        }

        $items = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        // pending count & sample list for header
        $pendingCount = Pinjaman::where('status', 'pending')->count();

        return view('pinjaman.index', compact('items', 'q', 'status', 'pendingCount'));
    }

    public function pendingList(Request $request)
    {
        $pending = Pinjaman::with('peminjam')->where('status', 'pending')->orderBy('id','desc')->get();
        return view('pinjaman.partials.pending-list', compact('pending'))->render();
    }

    public function pendingCount(Request $request)
    {
        $count = Pinjaman::where('status', 'pending')->count();
        return response()->json(['pending' => $count]);
    }

    public function changeStatus(Request $request, Pinjaman $pinjaman)
    {
        $data = $request->validate([
            'status' => 'required|string|in:pending,disetujui,dipinjam,selesai'
        ]);

        // Prevent any status changes when already finished
        if ($pinjaman->status === 'selesai') {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Pinjaman sudah selesai — status tidak dapat diubah.'], 422);
            }
            return redirect()->back()->withErrors(['error' => 'Pinjaman sudah selesai — status tidak dapat diubah.']);
        }
        $old = $pinjaman->status;
        $new = $data['status'];
        // restrict approval to admin/petugas
        if ($new === 'disetujui') {
            $role = auth()->user()->role ?? null;
            if (!in_array($role, ['admin','petugas'])) {
                if ($request->ajax()) return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
                abort(403, 'Unauthorized');
            }
            $pinjaman->disetujui_oleh = auth()->id();
        }

        $pinjaman->status = $new;
        if (in_array($new, ['selesai'])) {
            $pinjaman->diselesaikan_oleh = auth()->id();
        }

        // Persist status change first
        $pinjaman->save();

        // Adjust alat.jumlah_dipinjam based on status transition
        // Consider 'dipinjam' as the active state that affects alat.jumlah_dipinjam
        $wasActive = ($old === 'dipinjam');
        $nowActive = ($new === 'dipinjam');

        if (!$wasActive && $nowActive) {
            // moved into dipinjam: increment alat counts and mark details as dipinjam
            foreach ($pinjaman->details as $det) {
                $alat = Alat::find($det->id_alat);
                if (!$alat) continue;
                $alat->jumlah_dipinjam = intval($alat->jumlah_dipinjam ?? 0) + intval($det->jumlah);
                $alat->save();
                $det->status = 'dipinjam';
                $det->save();
            }
        } elseif ($wasActive && !$nowActive) {
            // moved out of dipinjam: decrement alat counts
            foreach ($pinjaman->details as $det) {
                $alat = Alat::find($det->id_alat);
                if (!$alat) continue;
                $alat->jumlah_dipinjam = max(0, intval($alat->jumlah_dipinjam ?? 0) - intval($det->jumlah));
                $alat->save();
            }
        }

        try { AuditLogService::log(AuditLogService::ACTION_UPDATE, ['subject_type'=>'pinjaman','subject_id'=>$pinjaman->id,'message'=>"Changed status from {$old} to {$pinjaman->status}",'meta'=>['id'=>$pinjaman->id,'from'=>$old,'to'=>$pinjaman->status]]); } catch(\Throwable $e){}

        if ($request->ajax()) return response()->json(['success' => true, 'status' => $pinjaman->status]);
        return redirect()->back()->with('success','Status diperbarui.');
    }

    public function create()
    {
        $alats = Alat::orderBy('nama_alat')->get();
        $users = User::orderBy('name')->get();
        return view('pinjaman.create', compact('alats','users'));
    }

    public function store(StorePinjamanRequest $request)
    {
        $data = $request->validated();
        \Log::info('PinjamanController::store - validated payload', ['data' => $data]);
        $details = $data['details'] ?? [];

        DB::beginTransaction();
        try {
            $pinjaman = Pinjaman::create([
                'id_peminjam' => $data['id_peminjam'],
                'tanggal_pinjam' => $data['tanggal_pinjam'],
                'tanggal_kembali_rencana' => $data['tanggal_kembali_rencana'],
                'pesan' => $data['pesan'] ?? null,
                'disetujui_oleh' => $data['disetujui_oleh'] ?? null,
                'diselesaikan_oleh' => $data['diselesaikan_oleh'] ?? null,
                'tanggal_selesai' => isset($data['diselesaikan_oleh']) ? date('Y-m-d') : null,
                'status' => $data['status'] ?? 'pending',
                'total_denda' => 0,
            ]);

                foreach ($details as $d) {
                    DetailPinjaman::create([
                        'id_pinjaman' => $pinjaman->id,
                        'id_alat' => $d['alat_id'],
                        'jumlah' => $d['jumlah'],
                        'status' => ($pinjaman->status === 'dipinjam') ? 'dipinjam' : 'pending',
                    ]);
                }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('PinjamanController::store exception', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString(), 'data' => $data]);
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }

        // If pinjaman is immediately in 'dipinjam' state, increase alat.jumlah_dipinjam
        if ($pinjaman->status === 'dipinjam') {
            foreach ($pinjaman->details()->get() as $det) {
                $alat = Alat::find($det->id_alat);
                if (!$alat) continue;
                $alat->jumlah_dipinjam = intval($alat->jumlah_dipinjam ?? 0) + intval($det->jumlah);
                $alat->save();
            }
        }

        try { AuditLogService::log(AuditLogService::ACTION_CREATE, ['subject_type'=>'pinjaman','subject_id'=>$pinjaman->id,'message'=>'Created pinjaman','meta'=>['id'=>$pinjaman->id]]); } catch(\Throwable $e){}

        if ($request->ajax()) return response()->json(['success' => true, 'id' => $pinjaman->id]);
        return redirect()->route('pinjaman.index')->with('success','Pinjaman dibuat.');
    }

    public function show(Pinjaman $pinjaman)
    {
        $pinjaman->load('details.alat');
        return view('pinjaman.show', compact('pinjaman'));
    }

    /**
     * Cetak/print view for a Pinjaman record.
     * Opens in a new tab for printing.
     */
    public function cetak(Pinjaman $pinjaman)
    {
        $pinjaman->load('peminjam', 'details.alat');
        return view('pinjaman.cetak', compact('pinjaman'));
    }

    public function returnForm(Request $request, Pinjaman $pinjaman)
    {
        if ($request->ajax()) {
            return view('pinjaman.partials.return-form', compact('pinjaman'))->render();
        }
        return redirect()->route('pinjaman.index');
    }

    // Approve pinjaman (role check)
    public function approve(Request $request, Pinjaman $pinjaman)
    {
        // Do not allow approving a finished pinjaman
        if ($pinjaman->status === 'selesai') {
            abort(403, 'Pinjaman sudah selesai — status tidak dapat diubah.');
        }
        $user = auth()->user();
        if (!in_array($user->role ?? null, ['admin','petugas'])) {
            abort(403);
        }
        $pinjaman->status = 'disetujui';
        $pinjaman->disetujui_oleh = $user->id;
        $pinjaman->save();
        return redirect()->back()->with('success','Pinjaman disetujui.');
    }

    // Mark items as taken
    public function take(Request $request, Pinjaman $pinjaman)
    {
        // Do not allow changing to dipinjam if already finished
        if ($pinjaman->status === 'selesai') {
            abort(403, 'Pinjaman sudah selesai — status tidak dapat diubah.');
        }
        if ($pinjaman->status !== 'dipinjam') {
            // increment alat counts and mark details
            foreach ($pinjaman->details as $det) {
                $alat = Alat::find($det->id_alat);
                if (!$alat) continue;
                $alat->jumlah_dipinjam = intval($alat->jumlah_dipinjam ?? 0) + intval($det->jumlah);
                $alat->save();
                $det->status = 'dipinjam';
                $det->save();
            }
            $pinjaman->status = 'dipinjam';
            $pinjaman->save();
        }
        return redirect()->back()->with('success','Status dipinjam.');
    }

    public function edit(Pinjaman $pinjaman, Request $request)
    {
        $alats = Alat::orderBy('nama_alat')->get();
        $users = User::orderBy('name')->get();
        if ($request->ajax()) return view('pinjaman.partials.form', compact('pinjaman','alats','users'))->render();
        return redirect()->route('pinjaman.index');
    }

    public function update(Request $request, Pinjaman $pinjaman)
    {
        $data = $request->validate([
            'id_peminjam' => 'required|exists:users,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
            'disetujui_oleh' => 'nullable|exists:users,id',
            'tanggal_selesai' => 'nullable|date',
            'total_denda' => 'nullable|numeric|min:0',
            'pesan' => 'nullable|string',
            'diselesaikan_oleh' => 'nullable|exists:users,id',
            'status' => 'required|string|in:pending,disetujui,dipinjam,selesai',
            'details' => 'nullable|array',
            'details.*.id_alat' => 'required_with:details|exists:alats,id',
            'details.*.jumlah' => 'required_with:details|integer|min:1',
        ]);

        $details = $data['details'] ?? [];
        unset($data['details']);

        // Prevent changing status on a finished pinjaman
        if (isset($data['status']) && $pinjaman->status === 'selesai' && $data['status'] !== 'selesai') {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Pinjaman sudah selesai — status tidak dapat diubah.'], 422);
            }
            return redirect()->back()->withErrors(['error' => 'Pinjaman sudah selesai — status tidak dapat diubah.']);
        }

        // Server-side availability validation on update: consider existing pinjaman if it was dipinjam
        $wasActive = ($pinjaman->status === 'dipinjam');
        // build current occupied by others: alat->jumlah_dipinjam already includes this pinjaman if wasActive
        foreach ($details as $d) {
            if (empty($d['id_alat'])) continue;
            $alat = Alat::find($d['id_alat']);
            if (!$alat) continue;
            // compute available: total - rusak - (jumlah_dipinjam - currentPinjamanAmountForThisAlatIfWasActive)
            $currentPinned = intval($alat->jumlah_dipinjam ?? 0);
            $currentThis = 0;
            if ($wasActive) {
                $existingDetail = $pinjaman->details()->where('id_alat', $d['id_alat'])->first();
                $currentThis = $existingDetail ? intval($existingDetail->jumlah) : 0;
            }
            $available = intval($alat->jumlah_total) - intval($alat->jumlah_rusak ?? 0) - ($currentPinned - $currentThis);
            if (intval($d['jumlah']) > $available) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => "Stok tidak mencukupi untuk {$alat->nama_alat}. Tersedia: {$available}"], 422);
                }
                return redirect()->back()->withErrors(['details' => "Stok tidak mencukupi untuk {$alat->nama_alat}. Tersedia: {$available}"])->withInput();
            }
        }

        // If the old pinjaman was dipinjam, decrement alat counts for its existing details
        if ($wasActive) {
            foreach ($pinjaman->details as $oldDet) {
                $alat = Alat::find($oldDet->id_alat);
                if (!$alat) continue;
                $alat->jumlah_dipinjam = max(0, intval($alat->jumlah_dipinjam ?? 0) - intval($oldDet->jumlah));
                $alat->save();
            }
        }

        $pinjaman->update($data);
        // replace details
        $pinjaman->details()->delete();
        foreach ($details as $d) {
            DetailPinjaman::create([ 'id_pinjaman' => $pinjaman->id, 'id_alat' => $d['id_alat'], 'jumlah' => $d['jumlah'], 'status' => $d['status'] ?? 'dipinjam' ]);
        }

        // If now in 'dipinjam', increment alat counts
        if ($pinjaman->status === 'dipinjam') {
            foreach ($pinjaman->details as $det) {
                $alat = Alat::find($det->id_alat);
                if (!$alat) continue;
                $alat->jumlah_dipinjam = intval($alat->jumlah_dipinjam ?? 0) + intval($det->jumlah);
                $alat->save();
            }
        }

        try { AuditLogService::log(AuditLogService::ACTION_UPDATE, ['subject_type'=>'pinjaman','subject_id'=>$pinjaman->id,'message'=>'Updated pinjaman','meta'=>['id'=>$pinjaman->id]]); } catch(\Throwable $e){}

        if ($request->ajax()) return response()->json(['success' => true]);
        return redirect()->route('pinjaman.index')->with('success','Pinjaman diperbarui.');
    }

    public function destroy(Pinjaman $pinjaman)
    {
        try { AuditLogService::log(AuditLogService::ACTION_DELETE, ['subject_type'=>'pinjaman','subject_id'=>$pinjaman->id,'message'=>'Deleted pinjaman','meta'=>['id'=>$pinjaman->id]]); } catch(\Throwable $e){}

        // If pinjaman was dipinjam, decrement alat counts
        if ($pinjaman->status === 'dipinjam') {
            foreach ($pinjaman->details as $det) {
                $alat = Alat::find($det->id_alat);
                if (!$alat) continue;
                $alat->jumlah_dipinjam = max(0, intval($alat->jumlah_dipinjam ?? 0) - intval($det->jumlah));
                $alat->save();
            }
        }

        $pinjaman->details()->delete();
        $pinjaman->delete();
        return redirect()->route('pinjaman.index')->with('success','Pinjaman dihapus.');
    }

    /**
     * Store a pengembalian (return) for a pinjaman.
     * Expects payload: details: array of [id_detail_pinjaman, jumlah_kembali, tanggal_kembali, kondisi, pesan]
     */
    public function storeReturn(Request $request, Pinjaman $pinjaman)
    {
        $data = $request->validate([
            'details' => 'required|array',
            'details.*.id_detail_pinjaman' => 'required|exists:detail_pinjaman,id',
            'details.*.jumlah_kembali' => 'required|integer|min:1',
            'details.*.tanggal_kembali' => 'required|date',
            'details.*.kondisi' => 'required|in:baik,rusak,hilang',
            'details.*.foto' => 'nullable|image|max:2048',
            'details.*.denda' => 'nullable|numeric|min:0',
            'details.*.pesan' => 'nullable|string',
        ]);

        $totalDenda = intval($pinjaman->total_denda ?? 0);

        DB::beginTransaction();
        try {
            foreach ($data['details'] as $index => $d) {
                $detail = DetailPinjaman::find($d['id_detail_pinjaman']);
                if (!$detail) continue;

                $jumlahKembali = intval($d['jumlah_kembali']);
                $tglKembali = $d['tanggal_kembali'];
                $kondisi = $d['kondisi'];
                $pesan = $d['pesan'] ?? null;

                // create pengembalian record
                $pengData = [
                    'id_detail_pinjaman' => $detail->id,
                    'jumlah_kembali' => $jumlahKembali,
                    'tanggal_kembali' => $tglKembali,
                    'kondisi' => $kondisi,
                    'denda' => 0,
                    'pesan' => $pesan,
                    'diterima_oleh' => auth()->user()?->name ?? auth()->id(),
                ];

                // handle uploaded foto for this detail if provided (fix: use proper nested file access)
                $file = $request->file('details.' . $index . '.foto');
                if ($file && $file->isValid()) {
                    $path = $file->store('pengembalian', 'public');
                    $pengData['foto'] = $path;
                }

                $peng = DetailPengembalian::create($pengData);

                // If a manual denda was provided in form, add it
                $manualDenda = isset($d['denda']) ? intval($d['denda']) : 0;

                // Update alat: decrement jumlah_dipinjam and handle rusak/hilang
                $alat = Alat::find($detail->id_alat);
                if ($alat) {
                    // Decrease jumlah_dipinjam on return
                    $alat->jumlah_dipinjam = max(0, intval($alat->jumlah_dipinjam ?? 0) - $jumlahKembali);
                    
                    // Increase jumlah_rusak if damaged or lost
                    if (in_array($kondisi, ['rusak', 'hilang'])) {
                        $alat->jumlah_rusak = intval($alat->jumlah_rusak ?? 0) + $jumlahKembali;
                    }
                    
                    $alat->save();
                }

                // compute total returned so far for this detail
                $sumReturned = $detail->pengembalians()->sum('jumlah_kembali');
                if ($sumReturned < $detail->jumlah) {
                    $detail->status = 'kembali_sebagian';
                } else {
                    $detail->status = 'selesai';
                }
                $detail->save();

                // compute denda if return date past rencana
                $rencana = $pinjaman->tanggal_kembali_rencana;
                $daysLate = (strtotime($tglKembali) - strtotime($rencana)) > 0 ? intval((strtotime($tglKembali) - strtotime($rencana)) / 86400) : 0;
                $autoDenda = 0;
                if ($daysLate > 0) {
                    // denda otomatis = hari terlambat × 1000 × jumlah barang
                    $autoDenda = $daysLate * 1000 * $jumlahKembali;
                }

                // total denda for this pengembalian = manual + otomatis
                $dendaTotalForThis = $manualDenda + $autoDenda;
                if ($dendaTotalForThis > 0) {
                    $totalDenda += $dendaTotalForThis;
                    $peng->denda = $dendaTotalForThis;
                    $peng->save();
                }

                // Audit log per pengembalian: record who received/processed it
                try {
                    AuditLogService::log(
                        AuditLogService::ACTION_CREATE,
                        [
                            'subject_type' => 'detail_pengembalian',
                            'subject_id' => $peng->id,
                            'message' => 'Catat pengembalian',
                            'meta' => [
                                'peng_id' => $peng->id,
                                'id_detail_pinjaman' => $detail->id,
                                'jumlah_kembali' => $jumlahKembali,
                                'kondisi' => $kondisi,
                                'denda' => $peng->denda ?? 0,
                                'diterima_oleh' => auth()->id(),
                            ],
                        ]
                    );
                } catch (\Throwable $e) {}
            }

            // update pinjaman total_denda
            $pinjaman->total_denda = $totalDenda;
            
            // Check status: if there are any returns started, mark pinjaman as 'dipinjam' (in return process)
            // if all details finished, mark pinjaman selesai
            $allDone = $pinjaman->details()->whereNotIn('status', ['selesai'])->count() === 0;
            if ($allDone) {
                $pinjaman->status = 'selesai';
                $pinjaman->tanggal_selesai = date('Y-m-d');
                $pinjaman->diselesaikan_oleh = auth()->id();
            } else {
                // If starting return process but not all done, keep status as dipinjam
                if ($pinjaman->status !== 'selesai') {
                    $pinjaman->status = 'dipinjam';
                }
            }
            $pinjaman->save();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }

        try { AuditLogService::log(AuditLogService::ACTION_CREATE, ['subject_type'=>'pengembalian','subject_id'=>$pinjaman->id,'message'=>'Created pengembalian','meta'=>['id'=>$pinjaman->id]]); } catch(\Throwable $e){}

        if ($request->ajax()) return response()->json(['success' => true]);
        return redirect()->back()->with('success','Pengembalian tercatat.');
    }
}
