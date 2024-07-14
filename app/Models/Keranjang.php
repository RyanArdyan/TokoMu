<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    use HasFactory;

    // panggil table keranjang
    // lindung table keranjang
    protected $table = 'keranjang';
    // aku butuh ini agar aku bisa menggunakan menggunakan keranjang::find()
    // kunci utamanya adalah keranjang_id
    protected $primaryKey = 'keranjang_id';
    // agar aku bisa melakukan create dan update data secara massal
    protected $guarded = [];

    // eager loading mencegah kueri N+1, bersemangat memuat secara bawaan, ini penting untuk membuat api, jadi ketika aku mengambil setiap keranjang maka detail_produk dan detail_user juga ikut terbawa
    // lindungi $dengan relasi produk dan user
    protected $with = ["produk", "user"];

    // relasi
    // belongs to / satu keranjang milik 1 produk
    public function produk()
    {
        // argumen pertama adalah berelasi dengan models/produk
        // argumen kedua adalah foreign key di table keranjang
        // argumen ketiga adalah primary key di table produk
        return $this->belongsTo(Produk::class, 'produk_id', 'produk_id');
    }

    // relasi
    // belongs to / satu keranjang milik 1 user
    public function user()
    {
        // argumen pertama adalah berelasi dengan models/user
        // argumen kedua adalah foreign key di table keranjang
        // argumen ketiga adalah primary key di table user
        return $this->belongsTo(User::class, 'user_id', 'user_id');

    }
}
