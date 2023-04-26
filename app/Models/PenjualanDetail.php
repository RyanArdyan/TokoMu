<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    use HasFactory;

    // nama table bawaan atau default nya adalah jamak versi inggris maka aku mengubah nya
    // lindungi $meja = 'penjualan_detail';
    protected $table = 'penjualan_detail';
    // default nya adalah id makanya aku mengubahnya
    // lindungi $utamaKunci = 'penjualan_detail_id'
    protected $primaryKey = 'penjualan_detail_id';
    // agar aku bisa menjalankan buat dan perbarui data secara massal
    // lindungi $penjaga berisi array
    protected $guarded = [];

    // 1 penjualan_detail milik 1 penjualan
    public function penjualan()
    {
        // kembalikkan penjualan_detail berelasi dengan table penjualan
        // argumen pertama adalah relasi table nya
        // argumen kedua adalah foreign key nya
        // argumen ketiga adalah primary key di table penjualan
        return $this->belongsTo(Penjualan::class, 'penjualan_id', 'id_penjualan');
    }

    // 1 penjualan detail hanya bisa memilih 1 produk
    // 1 penjualan_detail milik 1 produk
    public function produk()
    {
        // kembalikkan 1 penjualan milik 1 produk
        // kembalikkan penjualan_detail berelasi dengan table penjualan
        // argumen pertama adalah relasi table nya
        // argumen kedua adalah foreign key nya
        // argumen ketiga adalah primary key di table produk
        return $this->belongsTo(Produk::class, 'produk_id', 'produk_id');
    }
}
