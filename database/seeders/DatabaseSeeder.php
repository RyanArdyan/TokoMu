<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pengaturan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // tambah data di table user
        // user pbarik buat
        User::factory()->create([
            // 1 adalah true berarti dia adalah admin, kalau 0 false berarti dia adalah kasir
            'is_admin' => 1,
            'gambar' => 'gambar_default.png',
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('pontianak')
        ]);

        // tambahkan 1 baris data ke table pengaturan menggunakan perintah php artisan db:seed
        Pengaturan::create([
            'pengaturan_id' => 1,
            'nama_perusahaan' => 'Toko Mu',
            'alamat_perusahaan' => 'Jl. Kibandang Samaran Ds. Slangit',
            'telepon_perusahaan' => '0123456789',
            // 1 berarti nota kecil
            // 2 berarti nota besar 
            'tipe_nota_perusahaan' => 1,
            // default diskon untuk pelanggan adalah 5%
            'diskon_perusahaan' => 5,
            'logo_perusahaan' => 'logo_perusahaan.png',
            'kartu_member' => 'kartu_member.png'
        ]);
    }
}
