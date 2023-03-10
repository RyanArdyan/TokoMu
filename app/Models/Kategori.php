<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    // bawaan nama table adalah jamak dalam bahasa inggris, makanya aku gunakan $table
    // protected $table kategori
    protected $table = 'kategori';
    // aku butuh ini agar aku bisa menggunakan Kategori::find(1)
    // lindungi kunci utama "kategori_id
    protected $primaryKey = 'kategori_id';
    // agar aku bisa membuat dan memperbarui data secara massal
    // lindugi penjaga yang berisi array kosong
    protected $guarded = [];

    // 1 kategori punya banyak produk
    public function produk()
    {
        return $this->hasMany(Produk::class);
    }
}
