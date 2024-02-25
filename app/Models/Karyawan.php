<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    // panggil table karyawan
    // lindungi table karyawan
    protected $table = 'karyawan';
    // aku butuh ini agar aku bisa menggunakan menggunakan karyawan::find()
    // kunci utamanya adalah karyawan_id
    protected $primaryKey = 'karyawan_id';
    // agar aku bisa melakukan create dan update data secara massal
    protected $guarded = [];

    // eager loading mencegah kueri N+1, bersemangat memuat secara bawaan, ini penting untuk membuat api, jadi ketika aku mengambil setiap karyawan maka detail_user juga ikut terbawa
    // lindungi $dengan relasi users dan penyuplai
    protected $with = ["user"];

    // relasi
    // belongs to / satu karyawan milik 1 users
    public function user()
    {
        // argumen pertama adalah berelasi dengan models/user
        // argumen kedua adalah foreign key di table karyawan
        // argumen ketiga adalah primary key di table users
        return $this->belongsTo(User::class, 'user_id', 'user_id');

    }
}
