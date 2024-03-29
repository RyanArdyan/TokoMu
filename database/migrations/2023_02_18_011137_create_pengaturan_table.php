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
        // buat table pengaturan
        Schema::create('pengaturan', function (Blueprint $table) {
            // buat tipe data big integer yang auto increment dan primary key atau kunci utama
            $table->bigIncrements('pengaturan_id');
            // buat tipe data string
            $table->string('nama_perusahaan');
            $table->string('alamat_perusahaan');
            $table->string('telepon_perusahaan');
            // enum itu string
            // enum karena hanya ada dua pilihan
            // 1 berarti nota kecil
            // 2 berarti nota besar
            $table->enum('tipe_nota_perusahaan', [1, 2]);
            // small integer karena isinya hanya 0 sampai 100, bawaannya adalah 0
            $table->smallInteger('diskon_perusahaan')->default(0);
            $table->string('logo_perusahaan');
            $table->string('kartu_member');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan');
    }
};
