<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyuplai extends Model
{
    use HasFactory;

    // nama table penyuplai
    protected $table = 'penyuplai';
    // kunci utamanya adalah penyuplai_id
    protected $primaryKey = 'penyuplai_id';
    // agar buat data dan perbarui data secara massal berhasil
    protected $guarded = [];
}
