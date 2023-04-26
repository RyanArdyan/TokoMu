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
        // buat table retur_penjualan
        Schema::create('retur_penjualan', function (Blueprint $table) {
            // Metode ini bigIncrements membuat kolom setara penambahan otomatis UNSIGNED BIGINT(kunci utama):
            // $table->peningkatanBesar('return_penjualan_id');
            $table->bigIncrements('retur_penjualan_id');
            // buat column foreign key penjualan_id yang berelasi dengan table penjualan, column penjualan_id
            // onUpdate('cascade') berarti jika aku ubah suatu penjualan maka retur penjualan nya akan mengambil data yang berubah
            // onDelete('cascade') berarti jika aku hapus suatu penjualan maka retur penjualan nya yang terkait juga akan terhapus
            $table->foreignId('penjualan_id')->constrained('penjualan')
                ->references('penjualan_id')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            // tipe data foreign key, column produk_id, berelasi dengan table produk, jika produk di hapus maka baris retur_penjualan yang terkait juga akan terhapus, begitu juga update.
            $table->foreignId('produk_id')->constrained('produk')
                ->references('produk_id')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            // tipe data integer, column jumlah_retur
            $table->integer('jumlah_retur');
            // Metode date membuat tanggal kolom yang setara:
            $table->dateTime('tanggal_retur');
            $table->string('keterangan');
            // timestamps akan membuat column created_at dan updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retur__penjualan');
    }
};
