<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPinjaman extends Model
{
    use HasFactory;

    protected $table = 'detail_pinjaman';

    protected $fillable = [
        'id_pinjaman',
        'id_alat',
        'jumlah',
        'status',
    ];

    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class, 'id_pinjaman');
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'id_alat');
    }
}
