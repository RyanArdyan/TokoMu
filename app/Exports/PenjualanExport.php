<?php

namespace App\Exports;

use App\Models\Penjualan;
// ini digunakan untuk mencetak data table penjualan_detail
use Maatwebsite\Excel\Concerns\FromCollection;
// Ini untuk membuat thead, tr, th
use Maatwebsite\Excel\Concerns\WithHeadings;
// agar lebar kolom nya pas
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// ini digunakan untuk menghias tampilan di excel
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

// ada garis merah di bawah itu bukan error
class PenjualanExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    // ini untuk membuat <thead> di excel
    public function headings(): array
    {
        // buat colum-column
        // kembalikkan array numeric
        return [
            'Nama Member',
            'Nama Kasir',
            'Total Barang',
            'Total Harga',
            'Diskon',
            'Harus Bayar',
            'Uang Diterima'
        ];
    }

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

    // ini untuk membuat <tbody> di excel
    // method koleksi akan mencetak data table penjualan
    public function collection()
    {
        // kembalikkan semua data penjualan, pilih value column
        return Penjualan::select('nama_member', 'name_user', 'total_barang', 'total_harga', 'diskon', 'harus_bayar', 'uang_diterima')->get();
    }
}
