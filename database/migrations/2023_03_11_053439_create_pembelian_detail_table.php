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
        // buat tble pembelian_detail
        // skema buat table pembelian_detail, jalankan fungsi, Cetak Biru, $table
        Schema::create('pembelian_detail', function (Blueprint $table) {
            // buat column pembelian_detail_id yang tipe datanya big increment dan bigINteger
            $table->bigIncrements('pembelian_detail_id');
            // buat tipe foreign key column pembelian_id yang berelasi dengan table pembelian, referensi nya adalah column pembelian_id milik table pembelian
            // onDelete('cascade') ketika suatu pembelian_id di hapus misalnya angka 1 maka hapus semua pembelian_detail yang terkait, maksud nya hapus semua pembelian_detail yang column pembelian_id nya adalah 1
            $table->foreignId('pembelian_id')->constrained('pembelian')->references('pembelian_id')->onDelete('cascade')->onUpdate('cascade');
            // buat foreign key column produk_id di table pembelan_detail yang berelasi dengan table produk, column produk_id
            // onDelete('cascade') berarti jika suatu produk dihapus maka semua pembelian_detail terkait juga akan terhapus
            // anggaplah aku hapus produk smartfren unlimited maka semua pembelian_detail yang terkait dengan smartfren unlimited juga akan terhapus
            $table->foreignId('produk_id')->constrained('produk')->references('produk_id')->onDelete('cascade')->onUpdate('cascade');
            // tipe asing id, column retur_pembelian_id, bolek kosong karena tidak semua pembelian_detail akan di retur
            $table->foreignId('retur_pembelian_id')->nullable();
            // tipe data string, column nama_produk
            $table->string('nama_produk');
            // buat tipe data big Integer
            $table->bigInteger('harga');
            $table->integer('jumlah');
            $table->bigInteger('subtotal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_detail');
    }
};
