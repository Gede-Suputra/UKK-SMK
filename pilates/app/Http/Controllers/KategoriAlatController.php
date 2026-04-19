<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Services\AuditLogService;

class KategoriAlatController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $query = Kategori::query();
        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('id', $q)
                    ->orWhere('nama_kategori', 'like', "%{$q}%");
            });
        }

        $kategori = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('kategori-alat.index', compact('kategori', 'q'));
    }

    public function create(Request $request)
    {
        $kategori = new Kategori();
        if ($request->ajax()) {
            return view('kategori-alat.partials.form', compact('kategori'))->render();
        }
        return redirect()->route('kategori-alat.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        $k = Kategori::create($data);

        try { AuditLogService::log(AuditLogService::ACTION_CREATE, [
            'subject_type' => 'kategori',
            'subject_id' => $k->id,
            'message' => 'Created kategori',
            'meta' => ['id' => $k->id]
        ]); } catch (\Throwable $e) {}

        if ($request->ajax()) {
            return response()->json(['success' => true, 'id' => $k->id]);
        }

        return redirect()->route('kategori-alat.index')->with('success', 'Kategori created.');
    }

    public function edit(Kategori $kategori, Request $request)
    {
        if ($request->ajax()) {
            return view('kategori-alat.partials.form', compact('kategori'))->render();
        }
        return redirect()->route('kategori-alat.index');
    }

    public function show(Request $request, Kategori $kategori)
    {
        if ($request->ajax()) {
            return view('kategori-alat.partials.modal-content', compact('kategori'))->render();
        }
        return redirect()->route('kategori-alat.index');
    }

    public function update(Request $request, Kategori $kategori)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        $kategori->update($data);

        try { AuditLogService::log(AuditLogService::ACTION_UPDATE, [
            'subject_type' => 'kategori',
            'subject_id' => $kategori->id,
            'message' => 'Updated kategori',
            'meta' => ['id' => $kategori->id]
        ]); } catch (\Throwable $e) {}

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('kategori-alat.index')->with('success', 'Kategori updated.');
    }

    public function destroy(Kategori $kategori)
    {
        try { AuditLogService::log(AuditLogService::ACTION_DELETE, [
            'subject_type' => 'kategori',
            'subject_id' => $kategori->id,
            'message' => 'Deleted kategori',
            'meta' => ['id' => $kategori->id]
        ]); } catch (\Throwable $e) {}

        $kategori->delete();

        return redirect()->route('kategori-alat.index')->with('success', 'Kategori deleted.');
    }
}
