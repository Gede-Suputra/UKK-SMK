<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    use HasFactory;

    protected $table = 'alats';

    protected $fillable = [
        'id_kategori',
        'nama_alat',
        'deskripsi',
        'jumlah_total',
        'jumlah_dipinjam',
        'jumlah_rusak',
        'path_foto',
    ];

    public function kategori()
    {
        return $this->belongsTo(\App\Models\Kategori::class, 'id_kategori');
    }
}
