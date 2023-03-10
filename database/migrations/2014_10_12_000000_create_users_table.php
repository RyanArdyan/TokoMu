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
        // buat table string
        Schema::create('users', function (Blueprint $table) {
            // buat tipe data big integer yang auto increment
            $table->bigIncrements('user_id');
            // defaultnya adalah 0, 0 berarti kasir, 1 berarti admin
            $table->tinyInteger('is_admin')->default(0);
            // tipe data text karena kemungkinan nama gambar nya panjang
            $table->text('gambar')->nullable();
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
