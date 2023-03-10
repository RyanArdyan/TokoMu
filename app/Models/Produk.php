<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    // panggil table produk
    // lindung table produk
    protected $table = 'produk';
    // aku butuh ini agar aku bisa menggunakan menggunakan Produk::find()
    // kunci utamanya adalah produk_id
    protected $primaryKey = 'produk_id';
    // agar aku bisa melakukan create dan update data secara massal
    protected $guarded = [];

    // eager loading mencegah kueri N+1
    // lindungi dengan relasi kategori dan penyuplai
    protected $with = ["kategori", "penyuplai"];

    // relasi
    // belongs to / satu produk milik 1 kategori
    public function kategori()
    {
        // argumen pertama adalah berelasi dengan models/kategori
        // argumen kedua adalah foreign key di table produk
        // argumen ketiga adalah primary key di table kategori
        return $this->belongsTo(Kategori::class, 'kategori_id', 'kategori_id');
    }

    // relasi
    // belongs to / satu produk milik 1 penyuplai
    public function penyuplai()
    {
        // argumen pertama adalah berelasi dengan models/penyuplai
        // argumen kedua adalah foreign key di table produk
        // argumen ketiga adalah primary key di table penyuplai
        return $this->belongsTo(Penyuplai::class, 'penyuplai_id', 'penyuplai_id');

    }
}
