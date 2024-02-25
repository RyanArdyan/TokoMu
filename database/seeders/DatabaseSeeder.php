<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// import class yang dibutuhkan
use App\Models\Pengaturan;
use App\Models\User;
use App\Models\Penyuplai;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Pengeluaran;
use App\Models\PengeluaranDetail;
use App\Models\Member;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Support\Facades\Hash;
use App\Models\Karyawan;
// agar bisa mendapatkan waktu menggunakan package Carbon
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Mengirimkan database aplikasi
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // tambah data di table user
        // user::pabrik()->buat([])
        User::factory()->create([
            // 1 adalah true berarti dia adalah admin, kalau 0 berarti false berarti dia adalah kasir
            // column is_admin diisi 1
            'is_admin' => 1,
            // column gambar diisi 'gambar_default.jpg'
            'gambar' => 'gambar_default.png',
            'name' => 'Admin',
            'email' => 'admin123@gmail.com',
            'password' => Hash::make('pontianak1104')
        ]);

        User::create([
            // 1 adalah true berarti dia adalah admin, kalau 0 berarti false berarti dia adalah kasir
            // column is_admin diisi 0
            'is_admin' => 0,
            // column gambar diisi 'gambar_default.jpg'
            'gambar' => 'gambar_default.png',
            'name' => 'Ardyan',
            'email' => 'ryanardyan437@gmail.com',
            'password' => Hash::make('pontianak1104')
        ]);

        Karyawan::create([
            'user_id' => 2,
            'jam_masuk' => '16:00:00',
            'jam_keluar' => '23:59:00'
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
            'nama_produk' => 'Nasi Gigit',
            'merk' => 'None',
            'harga_beli' => 3000,
            'diskon' => 0,
            'harga_jual' => 5000,
            'stok' => 100
        ]);

        Produk::create([
            'kategori_id' => 1,
            'penyuplai_id' => 1,
            'kode_produk' => 'P-00002',
            'nama_produk' => 'Pisang Krispi',
            'merk' => 'None',
            'harga_beli' => 4000,
            'diskon' => 0,
            'harga_jual' => 7000,
            'stok' => 100
        ]);

        Pembelian::create([
            'user_id' => 1,
            'total_barang' => 10,
            'total_harga' => 1050000,
            'keterangan_pembelian' => 'Pembelian Peralatan',
            'tanggal_dan_waktu' => now()
        ]);

        PembelianDetail::create([
            'pembelian_id' => 1,
            'produk_id' => 1,
            'penyuplai_id' => 1,
            'jumlah' => 10,
            'subtotal' => 770000
        ]);

        PembelianDetail::create([
            'pembelian_id' => 1,
            'produk_id' => 1,
            'penyuplai_id' => 1,
            'jumlah' => 10,
            'subtotal' => 770000
        ]);

        Pengeluaran::create([
            'waktu_pengeluaran' => now(),
            'diterima_oleh' => "Admin",
            'nama_pengeluaran' => 'Belanja Persediaan',
            'jumlah_pengeluaran' => 5,
            'total_pengeluaran' => 15000
        ]);

        PengeluaranDetail::create([
            'pengeluaran_id' => 1,
            'nama_pengeluaran' => 'Beli Pulpen Standard',
            'jumlah' => 5,
            'harga_satuan' => 3000,
            'subtotal' => 15000
        ]);


        Member::create([
            'kode_member' => 'M-00001',
            'nama_member' => 'John Doe',
            'telepon_member' => '088705968716',
            'alamat_member' => 'Jalan Tanjung Raya 1'
        ]);

        // untuk mendapatkan tahun-bulan-tanggal jam:menit:detik
        // berisi karbon::sekarang()->lokal('id')->bentukIso('tahun-bulan-tanggal jam:menit:detik');
        $waktu_saat_ini = Carbon::now()->locale('id')->isoFormat('YYYY-MM-DD HH:mm:ss');

        Penjualan::create([
            'member_id' => 1,
            'user_id' => 2,
            // 'produk_id' => 1,
            'total_barang' => 10,
            'total_harga' => 60000,
            'diskon' => 0,
            'harus_bayar' => 60000,
            'uang_diterima' => 60000,
            'keterangan_penjualan' => 'Penjualan seperti biasa',
            // berisi value variable $waktu_saat_ini
            'tanggal_dan_waktu' => $waktu_saat_ini
        ]);

        PenjualanDetail::create([
            'penjualan_id' => 1,
            'produk_id' => 1,
            'jumlah' => 5,
            'subtotal' => 25000,
        ]);

        PenjualanDetail::create([
            'penjualan_id' => 1,
            'produk_id' => 2,
            'jumlah' => 5,
            'subtotal' => 35000,
        ]);
    }
}


// buat model pengeluaran_detail dan relasi nya adalah 1 pengeluaran_detail milik 1 pengeluaran, kalau 1 pengeluaran punya banyak pengeluaran detail itu manual saja lebih gampang
