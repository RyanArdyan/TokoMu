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
        // buat table kategori
        Schema::create('kategori', function (Blueprint $table) {
            // Metode ini bigIncrements membuat kolom setara penambahan otomatis UNSIGNED BIGINT(kunci utama):
            $table->bigIncrements('kategori_id');
            // Metode ini membuat VARCHAR kolom yang setara dengan panjang yang diberikan dan harus unique
            $table->string('nama_kategori')->unique();
            // column deskripsi_kategori yang tipe data nya string
            $table->string('deskripsi_kategori');
            // buat column created_at dan updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori');
    }
};
