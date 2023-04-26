<?php

namespace App\Exports;

// ini digunakan untuk mencetak data table penjualan_detail
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\PenjualanDetail;
// Ini untuk membuat thead, tr, th
use Maatwebsite\Excel\Concerns\WithHeadings;
// agar aku bisa mengirim value argument dari controller dan mengangkap nya
use Maatwebsite\Excel\Concerns\Exportable;
// agar lebar kolom nya pas
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// ini digunakan untuk menghias tampilan di excel
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


// ketika pertama kali implementasi maka akan ada underline merah, itu bukan error
class PenjualanDetailExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    // agar aku bisa mengirim value argument dari controller dan menangkap nya
    use Exportable;

    // property penjualan_id berisi string
    protected $penjualan_id = '';

    // ini untuk membuat <thead> di excel
    public function headings(): array
    {
        // buat colum-column
        // kembalikkan array numeric
        return [
            'Kode Produk',
            'Nama Produk',
            'Harga',
            'Jumlah',
            'Diskon',
            'Subtotal'
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

    // ini untuk menangkap value argument yang dikirim PenjualanController, method export_excel_penjualan_detail
    public function __construct($penjualan_id)
    {
        // panggil value property penjualan_id diisi value parameter $penjualan_id
        $this->penjualan_id = $penjualan_id;
    }

    // ini untuk membuat <tbody> di excel
    // method koleksi akan mencetak data table penjualan
    public function collection()
    {
        // kembalikkan Data table penjualan_detail, pilih value dari column berikut, dimana value column penjualan_id sama dengan value $this->penjualan_id, data baris pertama
        return PenjualanDetail::select('kode_produk', 'name_produk', 'harga_jual', 'jumlah', 'diskon', 'subtotal')->where('penjualan_id', $this->penjualan_id)->get();
    }
}
