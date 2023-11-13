<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.z
     */
    public function up(): void
    {
        // buat table pembelian
        Schema::create('pembelian', function (Blueprint $table) {
            // buat tipe data big integer yang auto increment dan primary key atau kunci utama
            $table->bigIncrements('pembelian_id');
            // foreign key atau kunci asing, relasinya adalah 1 pembelian dilakukan oleh 1 admin dan 1 admin bisa melakukan banyak pembelian
            // buat foreign key
            // foreign artinya asing, constrained artinya dibatasi
            $table->foreignId('user_id')->constrained('users')
                // referensi column user_id milik table user atau parent
                ->references('user_id')
                // ketika di hapus mengalir, jadi jika aku hapus user maka semua pembelian terkait nya juga akan terhapus
                ->onDelete('cascade')
                ->onUpdate('cascade');
            // tipe data smallInteger menyimpan value maksimal 32767, smallInteger jauh lebih mengehemat memori dibandingkan integer karena hanya menggunakan 2 BYTE, kalau integer 4 byte
            // $meja->kecil_integer, column total_barang
            $table->smallInteger('total_barang');
            $table->integer('total_harga');
            // string type, purchase_description column
            // tipe string, column keterangan_pembelian
            $table->string('keterangan_pembelian');
            // dateTime type, date_and_time column
            // tipe tanggalWaktu, column tanggal_dan_waktu
            $table->dateTime('tanggal_dan_waktu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian');
    }
};
