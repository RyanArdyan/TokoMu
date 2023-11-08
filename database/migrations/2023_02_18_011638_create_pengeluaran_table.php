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
        // buat table pengeluaran
        Schema::create('pengeluaran', function (Blueprint $table) {
            // buat tipe data big integer yang auto increment dan primary key
            $table->bigIncrements('pengeluaran_id');
            $table->datetime('waktu_pengeluaran');
            $table->string('diterima_oleh');
            // buat tipe data string
            $table->string('nama_pengeluaran');
            $table->integer('jumlah_pengeluaran');
            $table->integer('total_pengeluaran');
            $table->timestamps();
        });
    }




    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran');
    }
};
