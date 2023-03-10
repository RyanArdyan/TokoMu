<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    // nama table default nya adalah bahasa inggris makanya aku ubah
    protected $table = 'pengaturan';
    // aku butuh ini agar aku bisa pake Pengaturan::find()
    protected $primaryKey = 'pengaturan_id';
    // ini untuk create data dan update data secara massal
    protected $guarded = [];
}
