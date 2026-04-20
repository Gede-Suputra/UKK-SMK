<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    use HasFactory;

    protected $table = 'pinjaman';

    protected $fillable = [
        'id_peminjam',
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'disetujui_oleh',
        'tanggal_selesai',
        'total_denda',
        'pesan',
        'diselesaikan_oleh',
        'status',
    ];

    public function peminjam()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_peminjam');
    }

    public function details()
    {
        return $this->hasMany(DetailPinjaman::class, 'id_pinjaman');
    }
}
