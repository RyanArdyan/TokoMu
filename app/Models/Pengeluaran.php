<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    // nama table pengeluaran
    // lindungi $table berisi table pengeluaran
    protected $table = 'pengeluaran';
    // kunci utamanya adalah pengeluaran_id
    // agar aku bisa mengambil detail pengeluaran menggunakan pengeluaran::find()
    // lindungi kunci utama berisi pengeluaran_id
    protected $primaryKey = 'pengeluaran_id';
    // agar bisa buat data dan perbarui data secara massal
    // lindungi $penjaga berisi array kosong
    protected $guarded = [];

    // 1 pengeluaran punya banyak PengeluaranDetail
    public function pengeluaran_detail()
    {
        // kembalikkan class pengeluaran memiliki banyak PengeluaranDetail
        return $this->hasMany(PengeluaranDetail::class, 'pengeluaran_id', 'pengeluaran_detail_id');
    }
}
