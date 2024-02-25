<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi nya
     */
    public function up(): void
    {
        // skema buat table karyawan, jalankan
        Schema::create('karyawan', function (Blueprint $table) {
            // buat tipe data big integer yang auto increment dan primary key atau kunci utama
            $table->bigIncrements('karyawan_id');
            // foreign key atau kunci asing, relasinya adalah 1 karyawan milik 1 users dan 1 user mungkin adalah seorang karyawan
            // buat foreign key
            // foreign artinya asing, constrained artinya dibatasi
            $table->foreignId('user_id')->constrained('users')
                // referensi column users milik table kategori
                ->references('user_id')
                ->onUpdate('cascade')
                // ketika di hapus mengalir, jadi jika aku hapus kategori maka semua postingan terkait nya juga akan terhapus
                ->onDelete('cascade');
            $table->time('jam_masuk');
            $table->time('jam_keluar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};
