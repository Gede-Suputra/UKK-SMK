<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPengembalian extends Model
{
    protected $table = 'detail_pengembalian';
    protected $fillable = ['id_detail_pinjaman','id_detail_pinjaman_code','jumlah_kembali','tanggal_kembali','kondisi','denda','pesan','diterima_oleh','foto'];

    public function detailPinjaman()
    {
        return $this->belongsTo(DetailPinjaman::class, 'id_detail_pinjaman');
    }
}
