<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Penyuplai;
use App\Models\Member;
use App\Models\Penjualan;
use App\Models\Pembelian;
use App\Models\Pengeluaran;

class DashboardController extends Controller
{
    // method index untuk menampilkan halaman dashboard
    public function index()
    {
        // jika yang login value column level nya adalah 0 berarti dia adalah kasir maka ke tampilan dashboard.kasir
        if (auth()->user()->is_admin === 0) {
            // kembalikan ke tampilan dashboard.kasir
            return view('dashboard.kasir');
        };

        // hitung semua jumlah baris data di table kategori
        $jumlah_kategori = Kategori::count();
        $jumlah_produk = Produk::count();
        $jumlah_penyuplai = Penyuplai::count();
        $jumlah_member = Member::count();

        // cetak tanggal 1 pada bulan saat ini
        // Y itu tahun, m itu bulan, lalu sebelahnya adalah tanggal
        $tanggal_awal = date('Y-m-01');
        // cetak tanggal hari ini
        $tanggal_hari_ini = date('Y-m-d');
        // $data_tanggal = array();
        // $data_pendapatan = array();
        // berisi inisialisasi array
        $data_tanggal = [];
        $data_pendapatan = [];

        // string ke waktu
        // pengulangan dari tanggal 1 sampai tanggal hari ini
        // tanggal awal akan menjadi index pengulangan, anggaplah dia akan mengulang 1, 2, 3
        while (strtotime($tanggal_awal) <= strtotime($tanggal_hari_ini)) {
            // push data ke array data_tanggal
            $data_tanggal[] = (int) substr($tanggal_awal, 8, 2);

            // misalnya hari ini ada 2 baris data di table penjualan maka lakukan pertambahan untuk value column harus bayar
            $total_penjualan = Penjualan::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('harus_bayar');
            $total_pembelian = Pembelian::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('total_harga');
            $total_pengeluaran = Pengeluaran::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('total_pengeluaran');

            // misalnya pendapatan hari ini 5 juta
            $pendapatan = $total_penjualan - $total_pembelian - $total_pengeluaran;
            // push data ke array $data_pendapatan
            // misalnya hari senin pendapatannya 5 juta, hari selasa pendapatannya 5 juta maka lakukan penjumlahan
            $data_pendapatan[] += $pendapatan;
            // tanggal awal berarti tanggal 1
            $tanggal_awal = date("Y-m-d", strtotime("+1 day", strtotime($tanggal_awal)));
        };


        // jika yg login adalah admin
        // jika value column is_admin dari user yang login sama dengan 1 maka 
        if (auth()->user()->is_admin === 1) {
            // kembalikan ke tampilan admin.index dan kirimkan data
            return view('dashboard.admin', [
                'jumlah_kategori' => $jumlah_kategori,
                'jumlah_produk' => $jumlah_produk,
                'jumlah_penyuplai' => $jumlah_penyuplai,
                'jumlah_member' => $jumlah_member,
                // panggil fungsi tanggal_indonesia di helpers
                'tanggal_awal' => tanggal_indonesia(date('Y-m-01')),
                'tanggal_hari_ini' => tanggal_indonesia($tanggal_hari_ini),
                'data_tanggal' => $data_tanggal,
                'data_pendapatan' => $data_pendapatan
            ]);
            // jika yang login ada kasir
        };
    }
}
