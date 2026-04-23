<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\KategoriAlatController;
use App\Http\Controllers\AlatController;
use App\Http\Controllers\PinjamanController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

use App\Http\Controllers\DashboardController;

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
    // Admin-only routes (protected by runtime role check)
    Route::middleware([
        // simple inline middleware to restrict to admin role
        function ($request, $next) {
            if (($request->user()->role ?? null) !== 'admin') {
                abort(403, 'Akses hanya untuk admin.');
            }
            return $next($request);
        }
    ])->group(function () {
        // User CRUD
        Route::resource('users', UserController::class)->names('users');
        // Kategori CRUD
        Route::resource('kategori-alat', KategoriAlatController::class)
            ->names('kategori-alat')
            ->parameters(['kategori-alat' => 'kategori']);
        // Alat CRUD
        Route::resource('alats', AlatController::class)->names('alats');
        // Audit logs (admin only)
        Route::get('logs', [AuditLogController::class, 'index'])->name('logs.index');
        Route::post('logs/delete', [AuditLogController::class, 'destroyBulk'])->name('logs.destroyBulk');
    });
    // Pinjaman helpers: pending list, pending count and change status (define before resource to avoid route-model binding conflict)
    Route::get('pinjaman/pending-list', [PinjamanController::class, 'pendingList'])->name('pinjaman.pendingList');
    Route::get('pinjaman/pending-count', [PinjamanController::class, 'pendingCount'])->name('pinjaman.pendingCount');
    Route::get('pinjaman/{pinjaman}/cetak', [PinjamanController::class, 'cetak'])->name('pinjaman.cetak');
    Route::post('pinjaman/{pinjaman}/status', [PinjamanController::class, 'changeStatus'])->name('pinjaman.changeStatus');
    Route::get('pinjaman/{pinjaman}/return-form', [PinjamanController::class, 'returnForm'])->name('pinjaman.returnForm');
    Route::post('pinjaman/{pinjaman}/pengembalian', [PinjamanController::class, 'storeReturn'])->name('pinjaman.storeReturn');
    // User-specific peminjaman page
    Route::get('user-pinjam', [App\Http\Controllers\UserPinjamController::class, 'index'])->name('user-pinjam');
    // Pinjaman CRUD (Petugas)
    Route::resource('pinjaman', PinjamanController::class)->names('pinjaman');
    // Audit logs (below users in menu)
    Route::get('logs', [AuditLogController::class, 'index'])->name('logs.index');
    Route::post('logs/delete', [AuditLogController::class, 'destroyBulk'])->name('logs.destroyBulk');
});

require __DIR__.'/auth.php';
