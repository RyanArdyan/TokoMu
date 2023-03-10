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
        // buat table penjualan
        Schema::create('penjualan', function (Blueprint $table) {
            $table->bigincrements('penjualan_id');
            // boleh kosong karena pembelinya, belum tentu adalah member
            // foreign key
            $table->unsignedBigInteger('member_id')->nullable();
            // arti restrict adalah misalnya, table penjualan baris 1, punya id_member 1, lalu aku hapus id_member 1 maka akan ada error di penjualan
            $table->foreign('member_id')->references('member_id')->on('member')->restrictOnUpdate()->restrictOnDelete();

            // buat tipe data foreign key
            $table->unsignedBigInteger('user_id')->nullable();
            // arti restrict adalah misalnya, table penjualan baris 1, punya id_user 1, lalu aku hapus id_user 1 maka akan ada error di penjualan
            $table->foreign('user_id')->references('user_id')->on('users')->restrictOnUpdate()->restrictOnDelete();
            // tipe data integer karena sedikit
            $table->integer('total_barang');
            // big integer agar tidak out of range jika sudah lebih dari 1 m
            $table->bigInteger('total_harga');
            // tini integer karena hanay dari 0 sampai 100, dan bawaannnya adalah 0
            $table->tinyInteger('diskon')->default(0);
            $table->bigInteger('harus_bayar')->default(0);
            $table->bigInteger('uang_diterima')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
