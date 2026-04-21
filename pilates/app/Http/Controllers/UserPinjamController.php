<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pinjaman;
use App\Models\Alat;
use App\Models\Kategori;

class UserPinjamController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $q = $request->query('q');
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        $kategori = $request->query('kategori');

        // user's pinjaman (active or recent)
        $pinjamans = Pinjaman::with('details.alat')
            ->where('id_peminjam', $user->id)
            ->whereIn('status', ['pending', 'disetujui', 'dipinjam'])
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        // basic stats for the user
        $stats = [
            'total' => Pinjaman::where('id_peminjam', $user->id)->count(),
            'active' => Pinjaman::where('id_peminjam', $user->id)->whereIn('status', ['pending', 'disetujui', 'dipinjam'])->count(),
            'dipinjam' => Pinjaman::where('id_peminjam', $user->id)->where('status', 'dipinjam')->count(),
            'selesai' => Pinjaman::where('id_peminjam', $user->id)->where('status', 'selesai')->count(),
        ];

        // available alats for catalog
        $alatsQuery = Alat::query();
        if ($q) {
            $alatsQuery->where('nama_alat', 'like', "%{$q}%");
        }
        if ($kategori) {
            $alatsQuery->where('id_kategori', $kategori);
        }
        $alats = $alatsQuery->orderBy('nama_alat')->get();

        return view('user-pinjam.index', compact('pinjamans', 'alats', 'kategoris', 'q', 'kategori', 'stats'));
    }
}
