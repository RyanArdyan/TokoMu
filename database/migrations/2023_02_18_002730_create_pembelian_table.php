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
        // buat table pembelian
        Schema::create('pembelian', function (Blueprint $table) {
            // buat tipe data big integer yang auto increment dan primary key atau kunci utama
            $table->bigIncrements('pembelian_id');
            // buat column foreign key penyuplai_id yang berelasi dengan table penyuplai, column penyuplai_id
            // onUpdate('cascade') berarti jika aku ubah suatu penyuplai maka pembelian nya akan mengambil data yang berubah
            // onDelete('cascade') berarti jika aku hapus suatu penyuplai maka pembelian nya yang terkait juga akan terhapus
            $table->foreignId('penyuplai_id')->constrained('penyuplai')
                ->references('penyuplai_id')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            // tipe data smallInteger menyimpan value maksimal 32767, smallInteger jauh lebih mengehemat memori dibandingkan integer karena hanya menggunakan 2 BYTE, kalau integer 4 byte
            // $meja->kecil_integer, column total_barang
            $table->smallInteger('total_barang');
            $table->integer('total_harga');
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
