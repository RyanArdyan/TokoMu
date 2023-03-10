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
        // buat table produknya_penyuplai
        Schema::create('produk_penyuplai', function (Blueprint $table) {
            // lakukan auto increment dan big integer
            $table->bigIncrements('produk_penyuplai_id');
            // foreign key atau kunci asing, relasinya adalah 1 produk_penyuplai milik 1 penyuplai
            // buat foreign key column di table produk yaitu penyuplai_id yang berelasi dengean column penyuplai_id milik table penyuplai
            // onDelete('cascade') artinya jika aku hapus penyuplai nya maka semua produk_penyuplai yang terkait juga akan terhapus
            $table->foreignId('penyuplai_id')->constrained('penyuplai')
                ->references('penyuplai_id')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            // buat foreign key column di table produk yaitu kategori_id yang berelasi dengan column kategori_id milik table kategori
            $table->foreignId('kategori_id')->constrained('kategori')
                ->references('kategori_id')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            // column nama_produk, tipe data string dan harus unique
            $table->string('nama_produk')->unique();
            // buat column string
            $table->string('merk');
            // big integer karena kalau integer tidak bisa diatas 1M
            $table->bigInteger('harga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produknya_penyuplai');
    }
};
