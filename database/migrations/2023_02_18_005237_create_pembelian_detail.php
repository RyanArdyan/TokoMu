<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // buat table pembelian detail
        Schema::create('pembelian_detail', function (Blueprint $table) {
            // buat column pembelian_detail_id yang tipe datanya big increment dan bigINteger
            $table->bigIncrements('pembelian_detail_id');
            // buat foreign key
            $table->foreignId('pembelian_id');
            $table->unsignedBigInteger('produk_id');
            // misalnya table pembelian_detail, baris ke 1, column produk_id nya adalah 1, lalu aku hapus produk_id 1 maka pembelian_detail akan error makanya aku pake restrict
            $table->foreign('produk_id')->references('produk_id')->on('produk')->restrictOnUpdate()->restrictOnDelete();
            // buat tipe data string
            $table->bigInteger('harga_beli');
            $table->integer('jumlah');
            $table->bigInteger('subtotal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_detail');
    }
};
