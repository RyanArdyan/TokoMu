<?php

namespace App\Exports;

// ini digunakan untuk mencetak data table penjualan
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Penjualan;
// Ini untuk membuat thead, tr, th
use Maatwebsite\Excel\Concerns\WithHeadings;
// agar aku bisa mengirim value argument dari controller dan mengangkap nya
use Maatwebsite\Excel\Concerns\Exportable;
// agar lebar kolom nya pas
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// ini digunakan untuk menghias tampilan di excel
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Illuminate\Support\Facades\DB;

// ketika pertama kali implementasi maka akan ada underline merah, itu bukan error
class Excel2Export implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    // agar aku bisa mengirim value argument dari controller dan menangkap nya
    use Exportable;

    // property tanggal_awal berisi string
    protected $tanggal_awal = "";
    protected $tanggal_akhir = "";

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
            'Uang Diterima',
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

    // ini untuk menangkap value argument yang dikirim PenjualanController, method excel
    public function __construct($tanggal_awal, $tanggal_akhir)
    {
        // panggil property tanggal_awal yang berada diluar diisi dengan value parameter $tanggal_awal
        $this->tanggal_awal = $tanggal_awal;
        // panggil property tanggal_akhir yang berada diluar lalu isi dengan value parameter $tanggal_akhir
        $this->tanggal_akhir = $tanggal_akhir;
    }

    // ini untuk membuat <tbody> di excel
    // method koleksi akan mencetak data table penjualan
    public function collection()
    {
        // dapatkan data table penjualan berdasarkan range atau jangkauan yang dikirimkan
        // kembalikkan data penjualan, pilih kolom-kolom berikut dimanaAntara value column created_at berisi dari value $this->tanggal_awal sampai value $this->tanggal_akhir, dapatkan semua data terkait
        return Penjualan::select('nama_member', 'name_user', 'total_barang', 'total_harga', 'diskon', 'harus_bayar', 'uang_diterima')->whereBetween('created_at', [$this->tanggal_awal, $this->tanggal_akhir])->get();
    }
}
