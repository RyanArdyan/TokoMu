<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanHarian extends Model
{
    use HasFactory;

    // panggil table penjualan_harian
    // lindung table penjualan_harian
    protected $table = 'penjualan_harian';
    // aku butuh ini agar aku bisa menggunakan menggunakan penjualan_harian::find()
    // kunci utamanya adalah penjualan_harian_id
    protected $primaryKey = 'penjualan_harian_id';
    // agar aku bisa melakukan create dan update data secara massal
    protected $guarded = [];

    // eager loading mencegah kueri N+1, bersemangat memuat secara bawaan, ini penting untuk membuat api, jadi ketika aku mengambil setiap penjualan_harian maka detail_produk juga ikut terbawa
    // lindungi $dengan relasi produk dan penyuplai
    protected $with = ["produk"];

    // relasi
    // belongs to / satu penjualan_harian milik 1 produk
    public function produk()
    {
        // argumen pertama adalah berelasi dengan models/produk
        // argumen kedua adalah foreign key di table penjualan_harian
        // argumen ketiga adalah primary key di table produk
        return $this->belongsTo(Produk::class, 'produk_id', 'produk_id');

    }
}
