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
        // buat table retur_pembelian
        Schema::create('retur_pembelian', function (Blueprint $table) {
            // Metode ini bigIncrements membuat kolom setara penambahan otomatis UNSIGNED BIGINT(kunci utama):
            // $table->peningkatanBesar('return_pembelian_id');
            $table->bigIncrements('retur_pembelian_id');
            // buat column foreign key pembelian_id yang berelasi dengan table pembelian, column pembelian_id
            // onUpdate('cascade') berarti jika aku ubah suatu pembelian maka retur pembelian nya akan mengambil data yang berubah
            // onDelete('cascade') berarti jika aku hapus suatu pembelian maka retur pembelian nya yang terkait juga akan terhapus
            $table->foreignId('pembelian_id')->constrained('pembelian')
                ->references('pembelian_id')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            // Metode date membuat tanggal kolom yang setara:
            $table->date('tanggal_retur');
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
        Schema::dropIfExists('retur_pembelian');
    }
};
