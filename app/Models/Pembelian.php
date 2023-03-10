<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';
    protected $primaryKey = 'pembelian_id';
    protected $guarded = [];

    // pembelian milik 1 penyuplai
    // aku membeli barang-barang dari 1 penyuplai atau perusahaan
    public function penyuplai()
    {
        // semua pembelian hanya memiliki 1 penyuplai
        // penyuplai_id yang pertama adalah foreign key milik table Pembelian
        // penyuplai_id yang kedua adalah primary key milik table penyuplai
        return $this->belongsTo(Penyuplai::class, 'penyuplai_id', 'penyuplai_id');
    }

    // 1 pembelian punya banyak pembelian detail
    public function pembelian_detail()
    {
        // 1 pemblian punya banyak pembelian_detail
        return $this->hasMany(PembelianDetail::class);
    }
}
