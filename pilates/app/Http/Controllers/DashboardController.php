<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Alat;
use App\Models\Pinjaman;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if (! $user) return redirect()->route('login');

        // regular users are not allowed to view admin/petugas dashboard
        if (($user->role ?? 'user') === 'user') {
            abort(403, 'Akses ditolak. Halaman ini tidak tersedia untuk role user.');
        }

        // If the logged-in user is a 'petugas', redirect them directly to the pinjaman list
        if (($user->role ?? '') === 'petugas') {
            return redirect()->route('pinjaman.index');
        }

        // Fetch dashboard statistics
        $totalKategori = Kategori::count();
        $totalAlat = Alat::count();
        $totalAlatTersedia = Alat::selectRaw('SUM(jumlah_total - COALESCE(jumlah_rusak, 0) - COALESCE(jumlah_dipinjam, 0)) as total')->first()?->total ?? 0;
        $totalAlatRusak = Alat::sum('jumlah_rusak') ?? 0;
        $totalAlatDipinjam = Alat::sum('jumlah_dipinjam') ?? 0;

        $totalPinjaman = Pinjaman::count();
        $pinjamanPending = Pinjaman::where('status', 'pending')->count();
        $pinjamanDisetujui = Pinjaman::where('status', 'disetujui')->count();
        $pinjamanDipinjam = Pinjaman::where('status', 'dipinjam')->count();
        $pinjamanSelesai = Pinjaman::where('status', 'selesai')->count();

        // Recent pinjamans
        $recentPinjamans = Pinjaman::with('peminjam')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Top alat yang paling sering dipinjam (use subquery to avoid ONLY_FULL_GROUP_BY errors)
        $topAlat = Alat::selectRaw('alats.*, (
                select count(*) from detail_pinjaman where detail_pinjaman.id_alat = alats.id
            ) as jumlah_pinjaman')
            ->with('kategori')
            ->orderByDesc('jumlah_pinjaman')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalKategori',
            'totalAlat',
            'totalAlatTersedia',
            'totalAlatRusak',
            'totalAlatDipinjam',
            'totalPinjaman',
            'pinjamanPending',
            'pinjamanDisetujui',
            'pinjamanDipinjam',
            'pinjamanSelesai',
            'recentPinjamans',
            'topAlat'
        ));
    }
}
