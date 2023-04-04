<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturPembelian extends Model
{
    use HasFactory;

    // default table nya adalah jamak
    // lindungi $table = 'return_pembelian'
    protected $table = 'retur_pembelian';

    protected $primaryKey = 'retur_pembelian_id';
    // agar bisa create data dan update secara massal
    // lindungi $penjaga = []
    protected $guarded = [];

    // 1 retur pembelian milik 1 pembelian
    public function pembelian()
    {
        // argumen kedua adaah foreign key milik table return_pembelian
        // argumen ketiga adalah primary key di table pembelian
        return $this->belongsTo(Pembelian::class, 'pembelian_id', 'pembelian_id');
    }
}
