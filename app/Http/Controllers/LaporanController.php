<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Pembelian;
use App\Models\Pengeluaran;
use Barryvdh\DomPDF\Facade\Pdf;
use DataTables;
use PhpParser\Node\Stmt\Echo_;

class LaporanController extends Controller
{
    // hanya tampilan tanpa menampilkan data laporan
    // $request ada karena nanti method index akan dipaggil lagi ketika aku mengubah periode awal dan periode akhir misalnya dari tanggal 10/04/203 sampai 25/04/2023
    public function index(Request $request)
    {
        // tanggal awal pada bulan saat ini, dimulai dari 1
        // y = year, m = month, d = day
        $tanggal_awal = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $tanggal_hari_ini = date('Y-m-d');
        // echo $tanggal_hari_ini;
        
        // kembalikan ke tampilan laporan.index, kirimkan data berupa array
        return view('laporan.index', [
            // key tanggal_awal berisi value $tanggal_awal
            'tanggal_awal' => $tanggal_awal,
            'tanggal_hari_ini' => $tanggal_hari_ini
        ]);
    }

    // $request menangkap data formulir atau value dari value attribute name
    public function ubah_periode(Request $request)
    {
        // timpa value $tanggal awal dengan value $request->tanggal_awal
        $tanggal_awal = $request->tanggal_awal;
        // timpa value $tanggal_hari_ini dengan value $request->tanggal_hari_ini
        $tanggal_hari_ini = $request->tanggal_hari_ini;

         // kembalikan ke tampilan laporan.index, kirimkan data berupa array
        return view('laporan.index', [
            // key tanggal_awal berisi value $tanggal_awal
            'tanggal_awal' => $tanggal_awal,
            'tanggal_hari_ini' => $tanggal_hari_ini
        ]);
    }

    // berfungsi mendapatkan data
    // tangkap $tanggal_awal dan $tanggal_hari_ini dari method data 
    public function dapatkan_data($tanggal_awal, $tanggal_hari_ini)
    {
        // untuk looping
        $no = 1;
        // sebagai wadah
        $data = array();
        // Ini akan dipanggil berulang kali untuk dilakukan penjumlahan
        $pendapatan = 0;
        // ini juga akan dipanggil berulang kali untuk dilakukan penjumlahan
        $total_pendapatan = 0;

        // jika user memasukkan tanggal awal yang lebih kecil sama dengan dari tanggal hari ini maka while akan bernilai true, kalau while bernilai true maka akan terjadi pengulangan
        // strtotime berfungsi mengubah string ke time
        while(strtotime($tanggal_awal) <= strtotime($tanggal_hari_ini)) {
            // $tanggal akan berisi tanggal awal sampai tanggal hari ini
            $tanggal = $tanggal_awal;
            $tanggal_awal = date('Y-m-d', strtotime("+1 day", strtotime($tanggal_awal)));

            // Penjualan dimana value column created_at isinya seperti $tanggal lalu jumlahkan semua value column harus_bayar
            $total_penjualan = Penjualan::where('created_at', 'LIKE', "%$tanggal%")->sum('harus_bayar');
            // table pembelian dimana column created_at sepeti tanggal lalu jumlahkan column total_harga
            $total_pembelian = Pembelian::where('created_at', 'LIKE', "%$tanggal%")->sum('total_harga');
            // table pengeluaran dimana value column created_at seperti tanggal awal sampai tanggal hari ini lalu jumlah column total_pengeluran
            $total_pengeluaran = Pengeluaran::where('created_at', 'LIKE', "%$tanggal%")->sum('total_pengeluaran');

            // berisi value $total_penjualan dikurangi value $total_pembelian dikurangi value $total_pengeluran
            $pendapatan = $total_penjualan - $total_pembelian - $total_pengeluaran;
            // anggaplah 0 + 1000 + 2000 = 3000
            $total_pendapatan += $pendapatan;

            // buat array kosng
            $row = array();
            // mulai lakukan pengulangan
            // berisi panggil fungsi tanggal_indonesia yang berada di helpers, argument kedua nya false berarti aku tidak mencetak nama hari 
            $row['tanggal'] = tanggal_indonesia($tanggal, false);
            $row['penjualan'] = rupiah_bentuk($total_penjualan);
            $row['pembelian'] = rupiah_bentuk($total_pembelian);
            $row['pengeluaran'] = rupiah_bentuk($total_pengeluaran);
            $row['pendapatan'] = rupiah_bentuk($pendapatan);

            // $data[] diisi value $row
            $data[] = $row;
        };

        // buat tr terakhir untuk menyimpan Data Total Pendapatan
        $data[] = [
            'tanggal' => '',
            'penjualan' => '',
            'pembelian' => '',
            'pengeluaran' => 'Total Pendapatan',
            'pendapatan' => rupiah_bentuk($total_pendapatan)
        ];

        // return data yang berisi pengulangan atau anggaplah tr yang berulang
        return $data;
    }

    // tampilkan data laporan
    // tangkap $tanggal_awal dan tanggal_hari_ini dari url
    public function data($tanggal_awal, $tanggal_hari_ini)
    {
        // panggil method dapatkan_data yang berada diluar, lalu kirimkan $tanggal_awal dan $tanggal_hari_ini
        $data = $this->dapatkan_data($tanggal_awal, $tanggal_hari_ini);

        // kembalikan response berupa json dari $data
        return DataTables::of($data)
                ->make(true);
    }

    // fitur cetak laporan menggunakan pdf
    // parameter $tanggal_awal menangkap value $tanggal_awal yang dikirim lewat url
    public function cetak_pdf($tanggal_awal, $tanggal_hari_ini)
    {
        // panggil method dapatkan data yang berada diluar untuk mendapatkan pengulangan data / angaplah tr yang berulang
        $data = $this->dapatkan_data($tanggal_awal, $tanggal_hari_ini);
        // PDF::muatTampilan('laporan.cetak_pdf') kirimkan data berupa array
        $pdf = PDF::loadView('laporan.cetak_pdf', [
            'tanggal_awal' => $tanggal_awal,
            'tanggal_hari_ini' => $tanggal_hari_ini,
            'data' => $data
        ]);
        // atur kertas menggunkan A4, bentuk potrait atau lebih ke horizontal
        $pdf->setPaper('a4', 'potrait');
        // jika disimpan maka nama filenya adalah laporan-pendapatan-tahun-bulan-tanggal
        return $pdf->stream('Laporan-pendapatan-'. date('Y-m-d-his') .'.pdf');
    }
}
