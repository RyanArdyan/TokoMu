<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi
     */
    public function up(): void
    {
        //skema  buat table penjualan, jalankan fungsi, cetakBiru, $meja
        Schema::create('penjualan', function (Blueprint $table) {
            // buat tipe data big integer yang auto increment dan primary key atau kunci utama, column penjualan_Id
            $table->bigIncrements('penjualan_id');
            // boleh kosong karena pembelinya, belum tentu adalah member
            // foreign key
            $table->unsignedBigInteger('member_id')->nullable();
            // arti restrict adalah misalnya, table penjualan baris 1, punya id_member 1, lalu aku hapus id_member 1 maka akan ada error atau tidak bisa dihapus
            // column foreign key member_id referensi nya adalah column member_id milik table memeber,
            $table->foreign('member_id')->references('member_id')->on('member')
            // batasiSaatPembaruan
            ->restrictOnUpdate()
            // batasiSaatDihapus
            ->restrictOnDelete();
            // buat tipe data foreign key user_id, boleh kosong
            $table->unsignedBigInteger('user_id')->nullable();
            // arti restrict adalah misalnya, table penjualan baris 1, punya user_id 1, lalu aku hapus user_id 1 maka akan ada error di penjualan
            $table->foreign('user_id')->references('user_id')->on('users')->restrictOnUpdate()->restrictOnDelete();
            // tipe data smallInteger menyimpan value maksimal 32767, smallInteger jauh lebih mengehemat memori dibandingkan integer karena hanya menggunakan 2 BYTE, kalau integer 4 byte
            // $meja->kecil_integer, column total_barang
            $table->smallInteger('total_barang');
            // big integer agar tidak out of range jika sudah lebih dari 1 m
            $table->integer('total_harga');
            // nilai maksimal kecil_integer adalah 127
            // tinyInteger hanya menggunakan 1 byte memori, dibandingkan integer yaitu 4 jadi ini menghemat memory
            // tini integer karena hanya dari 0 sampai 100, dan bawaannnya adalah 0
            $table->tinyInteger('diskon')->default(0);
            $table->integer('harus_bayar')->default(0);
            $table->integer('uang_diterima')->default(0);
            $table->string('keterangan_penjualan');
            // tipe tanggalWaktu, column tanggal_dan_waktu
            $table->datetime("tanggal_dan_waktu");
            // $table->timestamps();
            // tipe data tanggal tanpa waktu, column created_at
            $table->date('created_at');
            // tipe data tanggal tanpa waktu, column updated_at
            $table->date('updated_at');
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
