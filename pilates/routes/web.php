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

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
    // User CRUD
    Route::resource('users', UserController::class)->names('users');
    // Kategori CRUD for Petugas
    Route::resource('kategori-alat', KategoriAlatController::class)
        ->names('kategori-alat')
        ->parameters(['kategori-alat' => 'kategori']);
    // Alat CRUD (Petugas)
    Route::resource('alats', AlatController::class)->names('alats');
    // Pinjaman CRUD (Petugas)
    Route::resource('pinjaman', PinjamanController::class)->names('pinjaman');
    // Pinjaman helpers: pending list and change status
    Route::get('pinjaman/pending-list', [PinjamanController::class, 'pendingList'])->name('pinjaman.pendingList');
    Route::post('pinjaman/{pinjaman}/status', [PinjamanController::class, 'changeStatus'])->name('pinjaman.changeStatus');
    // Audit logs (below users in menu)
    Route::get('logs', [AuditLogController::class, 'index'])->name('logs.index');
    Route::post('logs/delete', [AuditLogController::class, 'destroyBulk'])->name('logs.destroyBulk');
});

require __DIR__.'/auth.php';
