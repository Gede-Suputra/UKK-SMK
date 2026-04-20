<?php

namespace App\Http\Controllers;

use App\Models\Pinjaman;
use App\Models\DetailPinjaman;
use App\Models\Alat;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\AuditLogService;

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

    public function changeStatus(Request $request, Pinjaman $pinjaman)
    {
        $data = $request->validate([
            'status' => 'required|string|in:pending,approved,active,returned,cancelled'
        ]);

        $old = $pinjaman->status;
        $pinjaman->status = $data['status'];
        if ($data['status'] === 'approved') {
            $pinjaman->disetujui_oleh = auth()->id();
        }
        if ($data['status'] === 'returned' || $data['status'] === 'cancelled') {
            $pinjaman->diselesaikan_oleh = auth()->id();
        }
        $pinjaman->save();

        try { AuditLogService::log(AuditLogService::ACTION_UPDATE, ['subject_type'=>'pinjaman','subject_id'=>$pinjaman->id,'message'=>"Changed status from {$old} to {$pinjaman->status}",'meta'=>['id'=>$pinjaman->id,'from'=>$old,'to'=>$pinjaman->status]]); } catch(\Throwable $e){}

        if ($request->ajax()) return response()->json(['success' => true, 'status' => $pinjaman->status]);
        return redirect()->back()->with('success','Status diperbarui.');
    }

    public function create(Request $request)
    {
        $pinjaman = new Pinjaman();
        $alats = Alat::orderBy('nama_alat')->get();
        $users = User::orderBy('name')->get();
        if ($request->ajax()) {
            return view('pinjaman.partials.form', compact('pinjaman','alats','users'))->render();
        }
        return redirect()->route('pinjaman.index');
    }

    public function store(Request $request)
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
            'status' => 'required|string|in:pending,approved,active,returned,cancelled',
            'details' => 'nullable|array',
            'details.*.id_alat' => 'required_with:details|exists:alats,id',
            'details.*.jumlah' => 'required_with:details|integer|min:1',
        ]);

        $details = $data['details'] ?? [];
        unset($data['details']);

        $p = Pinjaman::create($data);

        foreach ($details as $d) {
            DetailPinjaman::create([ 'id_pinjaman' => $p->id, 'id_alat' => $d['id_alat'], 'jumlah' => $d['jumlah'], 'status' => $d['status'] ?? 'pending' ]);
        }

        try { AuditLogService::log(AuditLogService::ACTION_CREATE, ['subject_type'=>'pinjaman','subject_id'=>$p->id,'message'=>'Created pinjaman','meta'=>['id'=>$p->id]]); } catch(\Throwable $e){}

        if ($request->ajax()) return response()->json(['success' => true, 'id' => $p->id]);
        return redirect()->route('pinjaman.index')->with('success','Pinjaman dibuat.');
    }

    public function show(Request $request, Pinjaman $pinjaman)
    {
        if ($request->ajax()) {
            $alats = Alat::orderBy('nama_alat')->get();
            return view('pinjaman.partials.modal-content', compact('pinjaman','alats'))->render();
        }
        return redirect()->route('pinjaman.index');
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
            'status' => 'required|string|in:pending,approved,active,returned,cancelled',
            'details' => 'nullable|array',
            'details.*.id_alat' => 'required_with:details|exists:alats,id',
            'details.*.jumlah' => 'required_with:details|integer|min:1',
        ]);

        $details = $data['details'] ?? [];
        unset($data['details']);

        $pinjaman->update($data);
        // replace details
        $pinjaman->details()->delete();
        foreach ($details as $d) {
            DetailPinjaman::create([ 'id_pinjaman' => $pinjaman->id, 'id_alat' => $d['id_alat'], 'jumlah' => $d['jumlah'], 'status' => $d['status'] ?? 'pending' ]);
        }

        try { AuditLogService::log(AuditLogService::ACTION_UPDATE, ['subject_type'=>'pinjaman','subject_id'=>$pinjaman->id,'message'=>'Updated pinjaman','meta'=>['id'=>$pinjaman->id]]); } catch(\Throwable $e){}

        if ($request->ajax()) return response()->json(['success' => true]);
        return redirect()->route('pinjaman.index')->with('success','Pinjaman diperbarui.');
    }

    public function destroy(Pinjaman $pinjaman)
    {
        try { AuditLogService::log(AuditLogService::ACTION_DELETE, ['subject_type'=>'pinjaman','subject_id'=>$pinjaman->id,'message'=>'Deleted pinjaman','meta'=>['id'=>$pinjaman->id]]); } catch(\Throwable $e){}
        $pinjaman->details()->delete();
        $pinjaman->delete();
        return redirect()->route('pinjaman.index')->with('success','Pinjaman dihapus.');
    }
}
