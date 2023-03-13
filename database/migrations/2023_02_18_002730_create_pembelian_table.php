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
            // buat column pembelian_id yang tipe datanya big increment dan auto increment
            $table->bigIncrements('pembelian_id');

            // buat column foreign key penyuplai_id yang berelasi dengan table penyuplai, column penyuplai_id
            // onUpdate('cascade') berarti jika aku ubah suatu penyuplai maka pembelian nya akan mengambil data yang berubah
            // onDelete('cascade') berarti jika aku hapus suatu penyuplai maka pembelian nya yang terkait juga akan terhapus
            $table->foreignId('penyuplai_id')->constrained('penyuplai')
                ->references('penyuplai_id')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('total_barang');
            $table->bigInteger('total_harga');
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
