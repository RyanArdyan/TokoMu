<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pengaturan;
use App\Models\User;
use App\Models\Penyuplai;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Pengeluaran;
use App\Models\Member;
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
            'email' => 'admin@egmail.com',
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

        Kategori::create([
            'nama_kategori' => 'Paket Internet',
            'deskripsi_kategori' => '-'
        ]);

        Penyuplai::create([
            'nama_penyuplai' => 'PT Smartfren Telecom TBK',
            'telepon_penyuplai' => '088705968716',
            'alamat_penyuplai' => 'Jalan A'
        ]);

        Produk::create([
            'kategori_id' => 1,
            'penyuplai_id' => 1,
            'kode_produk' => 'P-00001',
            'nama_produk' => 'Paket Smartfren Unlimited 2 GB Perhari    Selama Sebulan',
            'merk' => 'Smartfren',
            'harga_beli' => 77000,
            'diskon' => 0,
            'harga_jual' => 82000,
            'stok' => 100
        ]);

        Produk::create([
            'kategori_id' => 1,
            'penyuplai_id' => 1,
            'kode_produk' => 'P-00002',
            'nama_produk' => 'Paket Smartfren 6 GB Selama Sebulan',
            'merk' => 'Smartfren',
            'harga_beli' => 28000,
            'diskon' => 0,
            'harga_jual' => 33000,
            'stok' => 100
        ]);

        Pembelian::create([
            'penyuplai_id' => 1,
            'total_barang' => 10,
            'total_harga' => 1050000
        ]);

        PembelianDetail::create([
            'pembelian_id' => 1,
            'produk_id' => 1,
            'nama_produk' => 'Paket Smartfren Unlimited 2 GB Perhari    Selama Sebulan',
            'harga' => 77000,
            'jumlah' => 10,
            'subtotal' => 770000
        ]);

        PembelianDetail::create([
            'pembelian_id' => 1,
            'produk_id' => 2,
            'nama_produk' => 'Paket Smartfren 6 GB Selama Sebulan',
            'harga' => 28000,
            'jumlah' => 10,
            'subtotal' => 280000
        ]);

        Pengeluaran::create([
            'nama_pengeluaran' => 'Menggaji Semua Karyawan',
            'total_pengeluaran' => 200000000
        ]);

        Pengeluaran::create([
            'nama_pengeluaran' => 'Mendekorasi Kantor',
            'total_pengeluaran' => 100000000
        ]);

        Member::create([
            'kode_member' => 'M-00001',
            'nama_member' => 'Ardyan',
            'telepon_member' => '088705968716',
            'alamat_member' => 'Jalan Tanjung Raya 1'
        ]);
    }
}
