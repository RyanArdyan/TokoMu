<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi
     */
    // publik fungsi naik, void berarti tidak return value
    public function up(): void
    {
        // skema buat table penjualan_harian, jalankan fungsi berikut (Cetakbiru $meja)
        Schema::create('penjualan_harian', function (Blueprint $table) {
            // buat tipe data big integer yang auto increment dan primary key atau kunci utama
            $table->bigIncrements('penjualan_harian_id');
            // foreign key atau kunci asing, relasinya adalah 1 penjualan_harian milik 1 produk dan 1 produk memiliki banyak penjualan_harian
            // buat foreign key
            // foreign artinya asing, constrained artinya dibatasi
            $table->foreignId('produk_id')->constrained('produk')
                // referensi column produk_id milik table produk
                ->references('produk_id')
                // ketika di hapus mengalir, jadi jika aku hapus produk maka semua postingan terkait nya juga akan terhapus
                ->onDelete('cascade')
                ->onUpdate('cascade');
            // buat tipe data integer, column jumlah
            $table->integer('jumlah');
            $table->integer('total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan_harian');
    }
};
