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
            $table->bigIncrements('penyuplai_id');
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
