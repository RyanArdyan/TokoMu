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
        // buat table pengeluaran
        Schema::create('pengeluaran', function (Blueprint $table) {
            // buat tipe data integer yang auto increment
            $table->bigIncrements('pengeluaran_id');
            // buat tipe data string
            // unique berarti value column nama_pengeluaran, tidak boleh sama
            $table->string('nama_pengeluaran')->unique();
            // bigInteger karena aku harus menghindari out of range jika valuenya sudah ada 1 M
            $table->bigInteger('total_pengeluaran');
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
