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
        // buat table produk
        Schema::create('produk', function (Blueprint $table) {
            // lakukan auto increment dan big integer
            $table->bigIncrements('produk_id');
            // foreign key atau kunci asing, relasinya adalah 1 produk milik 1 kategori
            // buat foreign key column di table produk yaitu kategori_id yang berelasi dengean column kategori_id milik table kategori, ketika kategori di hapus maka produk nya juga akan terhapus
            $table->foreignId('kategori_id')->constrained('kategori')
                ->references('kategori_id')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('penyuplai_id')->constrained('penyuplai')
                ->references('penyuplai_id')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            // column kode_produk tipe data string dan harus unique
            $table->string('kode_produk')->unique();
            // column nama_produk, tipe data string dan harus unique
            $table->string('nama_produk')->unique();
            // buat column string
            $table->string('merk');
            // big integer karena kalau integer tidak bisa diatas 1M
            $table->bigInteger('harga_beli');
            // bawaan 0
            $table->integer('diskon')->default(0);
            // big integer karena kalau integer tidak bisa diatas 1M
            $table->bigInteger('harga_jual');
            // buat column stok, tipe data integer
            $table->integer('stok');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
