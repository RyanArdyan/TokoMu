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
        Schema::create('penyuplai', function (Blueprint $table) {
            // buat tipe data big integer yang auto increment dan primary key atau kunci utama
            $table->bigIncrements('penyuplai_id');
            // buat column varchar, nama columnya adalah nama_penyuplai dan value nya harus unik
            $table->string('nama_penyuplai')->unique();
            $table->string('telepon_penyuplai')->unique();
            $table->string('alamat_penyuplai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyuplai');
    }
};
