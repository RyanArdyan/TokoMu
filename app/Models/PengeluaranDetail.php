<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranDetail extends Model
{
    use HasFactory;

    // panggil table pengeluaran_detail
    // lindung table pengeluaran_detail
    protected $table = 'pengeluaran_detail';
    // aku butuh ini agar aku bisa menggunakan menggunakan pengeluaran_detail::find()
    // kunci utamanya adalah pengeluaran_detail_id
    protected $primaryKey = 'pengeluaran_detail_id';
    // agar aku bisa melakukan create dan update data secara massal
    protected $guarded = [];

    // eager loading mencegah kueri N+1, bersemangat memuat secara bawaan, ini penting untuk membuat api, jadi ketika aku mengambil setiap pengeluaran_detail maka detail_pengeluaran dan detail_penyuplai juga ikut terbawa
    // lindungi $dengan relasi pengeluaran dan penyuplai
    protected $with = ["pengeluaran"];

    // aku ambil

    // relasi
    // belongs to / satu pengeluaran_detail milik 1 pengeluaran
    public function pengeluaran()
    {
        // argumen pertama adalah berelasi dengan models/Pengeluaran
        // argumen kedua adalah foreign key di table pengeluaran_detail
        // argumen ketiga adalah primary key di table pengeluaran
        return $this->belongsTo(Pengeluaran::class, 'pengeluaran_id', 'pengeluaran_id');
    }


}
