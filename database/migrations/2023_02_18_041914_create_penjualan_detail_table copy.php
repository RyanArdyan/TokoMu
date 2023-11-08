<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // public function up(): void
    // {
    //     // buat table penjualan_detail
    //     Schema::create('penjualan_detail', function (Blueprint $table) {
    //         // buat tipe data big integer yang auto increment dan primary key atau kunci utama
    //         $table->bigIncrements('penjualan_detail_id');
    //         // foreign key atau kunci asing, relasinya adalah 1 penjualan_detail milik 1 penjualan dan 1 penjualan memiliki banyak penjualan_detail
    //         // buat foreign key column di table penjualan_detail yaitu penjualan_id yang berelasi dengean column penjualan_id milik table penjualan, ketika penjualan di hapus maka penjualan_detail nya juga akan terhapus
    //         // foreign artinya asing, constrained artinya dibatasi
    //         $table->foreignId('penjualan_id')->constrained('penjualan')
    //             // referensi column penjualan_id milik table penjualan
    //             ->references('penjualan_id')
    //             // ketika di hapus mengalir
    //             ->onDelete('cascade')
    //             ->onUpdate('cascade');
    //         // foreign key
    //         $table->foreignId('produk_id')->constrained('produk')
    //             // referensi column produk_id milik table produk
    //             ->references('produk_id')
    //             // ketika di hapus mengalir
    //             ->onDelete('cascade')
    //             ->onUpdate('cascade');
    //         // tipe asing id, column retur_penjualan_id, bolek kosong karena tidak semua penjualan_detail akan di retur
    //         $table->foreignId('retur_penjualan_id')->nullable();
    //         // kedua baris kode berikut untuk mencetak laporan excel 
    //         $table->string('kode_produk');
    //         $table->string('nama_produk');
    //         $table->integer('harga_jual');
    //         // tipe data smallInteger menyimpan value maksimal 32767, smallInteger jauh lebih mengehemat memori dibandingkan integer karena hanya menggunakan 2 BYTE, kalau integer 4 byte
    //         // $meja->kecil_integer, column jumlah
    //         $table->smallInteger('jumlah');
    //         $table->integer('subtotal');
            
    //         $table->timestamps();
    //     }); 
    // }

    // /**
    //  * Reverse the migrations.
    //  */
    // public function down(): void
    // {
    //     Schema::dropIfExists('penjualan_detail');
    // }
};
