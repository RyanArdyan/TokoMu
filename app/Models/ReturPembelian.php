<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturPembelian extends Model
{
    use HasFactory;

    protected $table = 'retur_pembelian';
    protected $primary_key = 'id';
    protected $fillable = [];

    
    public function pembelian()
    {
        // argumen kedua adaah foreign key milik table return_pembelian
        // argumen ketiga adalah primary key di table pembelian
        return $this->belongsTo(Pembelian::class, 'pembelian_id', 'pembelian_id');
    }
}
