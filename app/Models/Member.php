<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    // nama table member
    // lindungi $table berisi table member
    protected $table = 'member';
    // kunci utamanya adalah member_id
    // agar aku bisa mengambil detail member menggunakan Member::find()
    // lindungi kunci utama berisi member_id
    protected $primaryKey = 'member_id';
    // agar bisa buat data dan perbarui data secara massal
    // lindungi $penjaga berisi array kosong
    protected $guarded = [];
}
