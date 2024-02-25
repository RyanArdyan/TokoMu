<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
// agar aku bisa mengirim value argument atau data dari controller dan mengangkap nya
use Maatwebsite\Excel\Concerns\Exportable;
// ini digunakan untuk mencetak data table penjualan
use App\Models\PenjualanDetail;
use Illuminate\Support\Facades\Auth;

class TutupPenjualanVersi2Export implements FromView
{
    // agar aku bisa mengirim value argument dari controller dan menangkap nya
    use Exportable;

    // inisialisasi property agar aku bisa mengisi nya di method construct dan memanggil value nya di method collection
    // property waktu_jam_7 berisi string
    protected $waktu_jam_7 = "";
    protected $waktu_tutup = "";

    // ini untuk menangkap value argument yang dikirim PenjualanController, method excel
    public function __construct($waktu_jam_7, $waktu_tutup)
    {
        // panggil property waktu_jam_7 yang berada diluar diisi dengan value parameter $waktu_jam_7
        $this->waktu_jam_7 = $waktu_jam_7;
        $this->waktu_tutup = $waktu_tutup;
    }

    public function view(): view
    {
        // dapatkan data table PenjualanDetail berdasarkan range atau jangkauan yang dikirimkan
        // kembalikkan data PenjualanDetail, pilih kolom-kolom berikut seperti table produk column nama_produk dan harga_jual dan table penjualan_detail, column jumlah dan subtotal
        $beberapa_penjualan_detail = PenjualanDetail::select('produk.nama_produk', 'produk.harga_jual', 'penjualan_detail.jumlah', 'penjualan_detail.subtotal')
        // berelasi dengan table produk lewat column produk_id
        // table penjualan_detail digabung dengan table produk, value table produk, column produk_id sama dengan value table penjualan_detail, column produk_id
        ->join('produk', 'produk.produk_id', '=', 'penjualan_detail.produk_id')
        // ambil beberapa table penjualan_detail, yg column updated_at nya berada di jangkauan $permintaan->waktu_jam_7 sampai $permintaan->waktu_tutup
        // diamana antara value penjualan_detail, column updated_at, waktu_jam_7 sampai waktu_tutup
        ->whereBetween('penjualan_detail.updated_at', [$this->waktu_jam_7, $this->waktu_tutup])
        // dapatkan data
        ->get();

        // jadi $this->waktu_jam_7 yg berisi 2024-02-18 20:15:36 atau tahun-bulan-tanggal jam:menit:detik akan dipisah berdasarkan spasi, jadi 2024-02-18 akan masuk ke variable $tanggal dan 20:15:36akan masuk ke variable $jam
        list($tanggal, $jam) = explode(" ", $this->waktu_tutup);

        // kembalikkan ke tampilan penjualan.excel_tutup_laporan lalu kirimkan data berupa array
        return view('penjualan.excel_tutup_laporan', [
            // key berikut berisi value variable berikut
            'beberapa_penjualan_detail' => $beberapa_penjualan_detail,
            'nama_penjual' => Auth::user()->name,
            'jam_tutup' => $jam,
            'tanggal_tutup' => $tanggal
        ]);
    }
}
