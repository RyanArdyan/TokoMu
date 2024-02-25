<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
// ini digunakan untuk mencetak data table penjualan
use App\Models\PenjualanDetail;
// Ini untuk membuat thead, tr, th
use Maatwebsite\Excel\Concerns\WithHeadings;
// agar aku bisa mengirim value argument atau data dari controller dan mengangkap nya
use Maatwebsite\Excel\Concerns\Exportable;
// agar lebar kolom nya pas
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// 2 baris dibawah ini digunakan untuk menghias tampilan di excel
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TutupPenjualanExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    // agar aku bisa mengirim value argument dari controller dan menangkap nya
    use Exportable;

    // inisialisasi property agar aku bisa mengisi nya di method construct dan memanggil value nya di method collection
    // property waktu_jam_7 berisi string
    protected $waktu_jam_7 = "";
    protected $waktu_tutup = "";

    // ini untuk membuat <thead> di excel
    public function headings(): array
    {
        // buat colum-column
        // kembalikkan array numeric
        return [
            'Nama Produk',
            'Harga Satuan',
            'Jumlah Barang',
            'Subtotal'
        ];
    }

    // method gaya untuk menghias tampilan
    public function styles(Worksheet $sheet)
    {
        return [
            // Mmeberi gaya pada baris pertama
            // baris pertama, font nya bold benar
            1    => [
                'font' => ['bold' => true],
            ],
        ];
    }

    // ini untuk menangkap value argument yang dikirim PenjualanController, method excel
    public function __construct($waktu_jam_7, $waktu_tutup)
    {
        // panggil property waktu_jam_7 yang berada diluar diisi dengan value parameter $waktu_jam_7
        $this->waktu_jam_7 = $waktu_jam_7;
        $this->waktu_tutup = $waktu_tutup;
    }

    // ini untuk membuat <tbody> di excel
    // method koleksi akan mencetak data table penjualan
    public function collection()
    {
        // dapatkan data table PenjualanDetail berdasarkan range atau jangkauan yang dikirimkan
        // kembalikkan data PenjualanDetail, pilih kolom-kolom berikut seperti table produk column nama_produk dan harga_jual dan table penjualan_detail, column jumlah dan subtotal
        return PenjualanDetail::select('produk.nama_produk', 'produk.harga_jual', 'penjualan_detail.jumlah', 'penjualan_detail.subtotal')
        // berelasi dengan table produk lewat column produk_id
        // table penjualan_detail digabung dengan table produk, value table produk, column produk_id sama dengan value table penjualan_detail, column produk_id
        ->join('produk', 'produk.produk_id', '=', 'penjualan_detail.produk_id')
        // ambil beberapa table penjualan_detail, yg column tanggal_dan_waktu nya berada di jangkauan $permintaan->waktu_jam_7 sampai $permintaan->waktu_tutup
        // diamana antara value penjualan_detail, column tanggal_dan_waktu, waktu_jam_7 sampai waktu_tutup
        ->whereBetween('penjualan_detail.tanggal_dan_waktu', [$this->waktu_jam_7, $this->waktu_tutup])
        // dapatkan data
        ->get();
    }
}
