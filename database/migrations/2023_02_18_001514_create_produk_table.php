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
            // buat tipe data big integer yang auto increment dan primary key atau kunci utama
            $table->bigIncrements('produk_id');
            // foreign key atau kunci asing, relasinya adalah 1 produk milik 1 kategori dan 1 kategori memiliki banyak produk
            // buat foreign key
            // foreign artinya asing, constrained artinya dibatasi
            $table->foreignId('kategori_id')->constrained('kategori')
                // referensi column kategori_id milik table kategori
                ->references('kategori_id')
                ->onUpdate('cascade')
                // ketika di hapus mengalir, jadi jika aku hapus kategori maka semua produk terkait nya juga akan terhapus
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
            $table->integer('harga_beli');
            // bawaan 0
            // kecil_integer agar menghemat memory
            $table->tinyInteger('diskon')->default(0);
            $table->integer('harga_jual');
            // tipe data smallInteger menyimpan value maksimal 32767, smallInteger jauh lebih mengehemat memori dibandingkan integer karena hanya menggunakan 2 BYTE, kalau integer 4 byte
            // $meja->kecil_integer, column stok
            $table->smallInteger('stok');
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
