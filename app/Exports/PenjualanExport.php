<?php

namespace App\Exports;

// ini digunakan untuk mencetak data table penjualan
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
// Ini untuk membuat thead, tr, th
use Maatwebsite\Excel\Concerns\WithHeadings;
// agar aku bisa mengirim value argument dari controller dan mengangkap nya
use Maatwebsite\Excel\Concerns\Exportable;
// agar lebar kolom nya pas
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// 2 baris dibawah ini digunakan untuk menghias tampilan di excel
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

// ada garis merah di bawah itu bukan error
class PenjualanExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    // agar aku bisa mengirim value argument dari controller dan menangkap nya
    use Exportable;

    // inisialisasi property agar aku bisa mengisi nya di method construct dan memanggil value nya di method collection
    // property tanggal_awal berisi string
    protected $tanggal_awal = "";
    protected $tanggal_akhir = "";

    // ini untuk membuat <thead> di excel
    public function headings(): array
    {
        // buat colum-column
        // kembalikkan array numeric
        return [
            'tanggal dan waktu',
            'Member',
            'Keterangan Pengeluaran',
            'Total Barang',
            'Total Harga',
            'Diskon',
            'Harus Bayar'
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
    public function __construct($tanggal_awal, $tanggal_akhir)
    {
        // panggil property tanggal_awal yang berada diluar diisi dengan value parameter $tanggal_awal
        $this->tanggal_awal = $tanggal_awal;
        $this->tanggal_akhir = $tanggal_akhir;
    }

    // ini untuk membuat <tbody> di excel
    // method koleksi akan mencetak data table penjualan
    public function collection()
    {
        // dapatkan data table penjualan berdasarkan range atau jangkauan yang dikirimkan
        // kembalikkan data penjualan, pilih kolom-kolom berikut
        return Penjualan::select('tanggal_dan_waktu', 'member.nama_member', 'keterangan_penjualan', 'total_barang', 'total_harga', 'diskon', 'harus_bayar')
            // berelasi dengan table member lewat column member_id
            // gabung table, value table member, column member_id sama dengan value table penjualan.member_id
            ->join('member', 'member.member_id', '=', 'penjualan.member_id')
            // misalnya user memasukkan tanggal awal nya adalah 01-01-2023 lalu tanggal_akhir nya adalah 01-01-2024 maka cetak beberapa baris penjualan dari periode itu
            // diamana antara value penjualan, column dibuat_pada, tanggal_awal sampai tanggal_akhir
            ->whereBetween('penjualan.tanggal_dan_waktu', [$this->tanggal_awal, $this->tanggal_akhir])
            ->get();


        // // dapatkan data table penjualan berdasarkan range atau jangkauan yang dikirimkan
        // // kembalikkan data penjualan, pilih kolom-kolom berikut dimanaAntara value column created_at berisi dari value $this->tanggal_awal sampai value $this->tanggal_akhir, dapatkan semua data terkait
        // return PenjualanDetail::select('', 'member.nama_member', 'users.name', 'total_barang', 'total_harga', 'diskon', 'harus_bayar', 'uang_diterima')
        //     // berelasi dengan table member lewat column member_id
        //     // gabung table, value table member, column member_id sama dengan value table penjualan.member_id
        //     ->join('member', 'member.member_id', '=', 'penjualan.member_id')
        //     ->join('penjualan', 'penjualan.penjualan_id', '=', 'penjualan_detail.penjualan_id')
        //     ->join('users', 'users.user_id', '=', 'penjualan.user_id')
        //     // misalnya user memasukkan tanggal awal nya adalah 01-01-2023 lalu tanggal_akhir nya adalah 01-01-2024 maka cetak beberapa baris penjualan dari periode itu
        //     // diamana antara value detail_penjualan, column dibuat_pada, tanggal_awal sampai tanggal_akhir
        //     ->whereBetween('penjualan.created_at', [$this->tanggal_awal, $this->tanggal_akhir])
        //     ->get();
    }
}
