<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi
     */
    public function up(): void
    {
        // skema buat table keranjang, jalankan fungsi, Cetakbiru $meja
        Schema::create('keranjang', function (Blueprint $table) {
            // buat tipe data big integer yang auto increment dan primary key atau kunci utama
            $table->bigIncrements('keranjang_id');
            // foreign key atau kunci asing, relasinya adalah 1 keranjang milik 1 produk dan 1 produk memiliki banyak keranjang atau dimasukkan di banyak keranjang
            // buat foreign key
            // foreign artinya asing, constrained artinya dibatasi
            $table->foreignId('produk_id')->constrained('produk')
                // referensi column produk_id milik table produk
                ->references('produk_id')
                ->onUpdate('cascade')
                // ketika di hapus mengalir, jadi jika aku hapus produk maka semua keranjang terkait nya juga akan terhapus
                ->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')
                ->references('user_id')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('jumlah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keranjang');
    }
};
