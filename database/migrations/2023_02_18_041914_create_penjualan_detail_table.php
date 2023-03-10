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
        // buat table penjualan_detail
        Schema::create('penjualan_detail', function (Blueprint $table) {
            $table->bigIncrements('penjualan_detail_id');
            // logikanya adalah aku hapus penjualan_id 1 maka baris data table penjualan_detail yang punya penjualan_id 1 akan terhapus
            $table->foreignId('penjualan_id');
            // foreign key
            $table->unsignedBigInteger('produk_id')->nullable();
            // arti restrict adalah misalnya, table penjualan_detail baris 1, punya id_produk 1, lalu aku hapus id_produk 1 maka akan ada error di penjualan_detail
            $table->foreign('produk_id')->references('produk_id')->on('produk')->restrictOnUpdate()->restrictOnDelete();
            // bigInteger agar menghindari out of range jika angka usdha menyentuh m
            $table->bigInteger('harga_jual');
            $table->integer('jumlah');
            // tiny integer karena isinya berisi 0 sampai 100 dan bawaanya adalah 0
            $table->tinyInteger('diskon')->default(0);
            $table->integer('subtotal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan_detail');
    }
};