<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    // secara bawaan nama table nya adalah menggunakan kata jamak di bahasa inggris, jadi aku mengatur nya lewat kode berikut
    // lindungi $meja = 'penjualan';
    protected $table = 'penjualan';
    // id bawaan nya adalah id, maka nya aku mengubahnya
    // lindungi $utamaKunci = 'penjualan_id'
    protected $primaryKey = 'penjualan_id';
    // create secara massal dan update secara masal butuh ini
    // lindungi $penjaga berisi array
    protected $guarded = [];

    // ingat, belongsTo harus menggunakan pemuatan bersemangat
    // 1 penjualan dapat memilih 1 member agar mendapatkan diskon
    // Jika yang membeli adalah member maka table penjualan, column member_id akan diisi 
    public function member()
    {
        // table penjualan milik table member, member_id adalah foreign key di table penjualan, member_id adalah primary key di table member
        return $this->belongsTo(Member::class, 'member_id', 'member_id');
    }

    // 1 penjualan dilakukan oleh satu user atau kasir
    // 1 penjualan milik 1 user
    public function user()
    {   
        // 1 penjualan milik 1 user
        // argumen pertama model relasi nya
        // argumen pertama adalah column foreign key di table penjualan
        // argumen ketiga adalah column primary key di table user
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // 1 penjualan punya banyak penjualan_detail
    public function penjualan_detail()
    {
        // kembalikkan 1 penjualan punya banyak penjualan_detail
        // argumen pertama adalah relasi model nya
        return $this->hasMany(PenjualanDetail::class);
    }
}
