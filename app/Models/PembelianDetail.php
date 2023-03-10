<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianDetail extends Model
{
    use HasFactory;

    // panggil table pembelian_detail, bawaan nama table adalah jamak nya engish
    protected $table = 'pembelian_detail';
    // kunci utama nya adalah pembelian_detail_id, aku menulis ini agar aku bisa menggunakan PembelianDetail::find()
    protected $primaryKey = 'pembelian_detail_id';
    // aku butuh ini agar aku bisa membuat dan memperbarui data secara massal
    // lindungi penjaga berisi array
    protected $guarded = [];

    // relasi
	//  semua pembelian_detail hanya memiliki 1 produk
    // publik fungsi produk
    public function produk() 
	{
        // argumen pertama adalah relasi table nya
        // argumen kedua adalah foreign key / kunci asing di table pembelian_detail
        // argumen ketiga adalah primary key atau kunci utama di table produk
        // kembalikkan pembelian_detail milik table produk, produk_id, produk_id
        return $this->belongsTo(Produk::class, 'produk_id', 'produk_id');
    }

    // Model PembelianDetail milik model Pembelian atau 1 pembelian punya banyak pembelian_detail
    public function pembelian()
    {
        // argumen pertama adalah relasi table nya
        // argumen kedua adalah foreign key / kunci asing di table pembelian_detail
        // argumen ketiga adalah primary key atau kunci utama di table pembelian
        // kembalikkan pembelian_detail milik table pembelian, pembelian_id, pembelian_id
        return $this->belongsTo(Pembelian::class, 'pembelian_id', 'pembelian_id');
    }
}
