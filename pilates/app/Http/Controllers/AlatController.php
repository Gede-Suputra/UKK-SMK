<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Services\AuditLogService;

class AlatController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $query = Alat::with('kategori');
        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('id', $q)
                    ->orWhere('nama_alat', 'like', "%{$q}%");
            });
        }

        $alats = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('alats.index', compact('alats', 'q'));
    }

    public function create(Request $request)
    {
        $alat = new Alat();
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        if ($request->ajax()) {
            return view('alats.partials.form', compact('alat', 'kategoris'))->render();
        }
        return redirect()->route('alats.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_kategori' => 'nullable|string|max:100',
            'nama_alat' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'jumlah_total' => 'required|integer|min:0',
            'jumlah_dipinjam' => 'nullable|integer|min:0|lte:jumlah_total',
            'jumlah_rusak' => 'nullable|integer|min:0|lte:jumlah_total',
        ]);

        if ($request->hasFile('profile_photo_path')) {
            $data['path_foto'] = $request->file('profile_photo_path')->store('alats', 'public');
        }

        $data['jumlah_dipinjam'] = $data['jumlah_dipinjam'] ?? 0;
        $data['jumlah_rusak'] = $data['jumlah_rusak'] ?? 0;

        $a = Alat::create($data);

        try { AuditLogService::log(AuditLogService::ACTION_CREATE, [
            'subject_type' => 'alat',
            'subject_id' => $a->id,
            'message' => 'Created alat',
            'meta' => ['id' => $a->id]
        ]); } catch (\Throwable $e) {}

        if ($request->ajax()) {
            return response()->json(['success' => true, 'id' => $a->id]);
        }

        return redirect()->route('alats.index')->with('success', 'Alat created.');
    }

    public function edit(Alat $alat, Request $request)
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        if ($request->ajax()) {
            return view('alats.partials.form', compact('alat', 'kategoris'))->render();
        }
        return redirect()->route('alats.index');
    }

    public function show(Request $request, Alat $alat)
    {
        if ($request->ajax()) {
            $kategoris = Kategori::orderBy('nama_kategori')->get();
            return view('alats.partials.modal-content', compact('alat', 'kategoris'))->render();
        }
        return redirect()->route('alats.index');
    }

    public function update(Request $request, Alat $alat)
    {

        $data = $request->validate([
            'id_kategori' => 'nullable|string|max:100',
            'nama_alat' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'jumlah_total' => 'required|integer|min:0',
            'jumlah_dipinjam' => 'nullable|integer|min:0|lte:jumlah_total',
            'jumlah_rusak' => 'nullable|integer|min:0|lte:jumlah_total',
        ]);

        if ($request->hasFile('profile_photo_path')) {
            // delete old photo if exists
            if ($alat->path_foto) {
                try { \Storage::disk('public')->delete($alat->path_foto); } catch(\Throwable $e){}
            }
            $data['path_foto'] = $request->file('profile_photo_path')->store('alats', 'public');
        }

        $data['jumlah_dipinjam'] = $data['jumlah_dipinjam'] ?? 0;
        $data['jumlah_rusak'] = $data['jumlah_rusak'] ?? 0;

        $alat->update($data);

        try { AuditLogService::log(AuditLogService::ACTION_UPDATE, [
            'subject_type' => 'alat',
            'subject_id' => $alat->id,
            'message' => 'Updated alat',
            'meta' => ['id' => $alat->id]
        ]); } catch (\Throwable $e) {}

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('alats.index')->with('success', 'Alat updated.');
    }

    public function destroy(Alat $alat)
    {
        try { AuditLogService::log(AuditLogService::ACTION_DELETE, [
            'subject_type' => 'alat',
            'subject_id' => $alat->id,
            'message' => 'Deleted alat',
            'meta' => ['id' => $alat->id]
        ]); } catch (\Throwable $e) {}

        if ($alat->path_foto) {
            try { \Storage::disk('public')->delete($alat->path_foto); } catch(\Throwable $e){}
        }

        $alat->delete();

        return redirect()->route('alats.index')->with('success', 'Alat deleted.');
    }
}
