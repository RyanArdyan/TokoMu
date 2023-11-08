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
        // skema buat table pengeluaran_detail, jalankan fungsi, Cetakbiru, $meja
        Schema::create('pengeluaran_detail', function (Blueprint $table) {
            // buat tipe data big integer yang auto increment dan primary key
            $table->bigIncrements('pengeluaran_detail_id');
            // foreign key atau kunci asing, relasinya adalah 1 pengeluaran_detail milik 1 pengeluaran dan 1 pengeluaran memiliki banyak pengeluaran_detail
            // buat foreign key
            // foreign artinya asing, constrained artinya dibatasi
            $table->foreignId('pengeluaran_id')->constrained('pengeluaran')
                // referensi column pengeluaran_id milik table pengeluaran
                ->references('pengeluaran_id')
                ->onUpdate('cascade')
                // ketika di hapus mengalir, jadi jika aku hapus pengeluaran maka semua pengeluaran_detail terkait nya juga akan terhapus
                ->onDelete('cascade');
            $table->string('nama_pengeluaran');
            $table->integer('jumlah');
            $table->integer('harga_satuan');
            $table->integer('subtotal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_detail');
    }
};
