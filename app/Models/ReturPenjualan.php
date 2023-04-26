<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturPenjualan extends Model
{
    use HasFactory;

    // default table nya adalah jamak
    // lindungi $table = 'return_penjualan'
    protected $table = 'retur_penjualan';

    protected $primaryKey = 'retur_penjualan_id';
    // agar bisa create data dan update secara massal
    // lindungi $penjaga = []
    protected $guarded = [];

    // 1 retur penjualan milik 1 penjualan
    public function penjualan()
    {
        // argumen kedua adaah foreign key milik table return_penjualan
        // argumen ketiga adalah primary key di table penjualan
        return $this->belongsTo(Penjualan::class, 'penjualan_id', 'penjualan_id');
    }

    // 1 retur penjualan milik 1 produk
    public function produk()
    {
        // argumen kedua adaah foreign key milik table return_produk
        // argumen ketiga adalah primary key di table produk
        return $this->belongsTo(Produk::class, 'produk_id', 'produk_id');
    }
}
