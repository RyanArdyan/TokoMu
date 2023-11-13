<?php

namespace App\Exports;

// ini digunakan untuk mencetak data table pengeluaran
use Maatwebsite\Excel\Concerns\FromCollection;
// import model
use App\Models\PengeluaranDetail;
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
class PengeluaranExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'Waktu Pengeluaran',
            'Nama Admin',
            'Nama Pengeluaran',
            'Jumlah',
            'Harga Satuan',
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

    // ini untuk menangkap value argument yang dikirim pengeluaranController, method excel
    public function __construct($tanggal_awal, $tanggal_akhir)
    {
        // panggil property tanggal_awal yang berada diluar diisi dengan value parameter $tanggal_awal
        $this->tanggal_awal = $tanggal_awal;
        $this->tanggal_akhir = $tanggal_akhir;
    }
    
    // ini untuk membuat <tbody> di excel
    // method koleksi akan mencetak data table pengeluaran
    public function collection()
    {


        // dapatkan data table pengeluaran_detail berdasarkan range atau jangkauan waktu yang dikirimkan controller
        // kembalikkan data table PengeluaranDetail, pilih kolom-kolom berikut
        return PengeluaranDetail::select('pengeluaran.waktu_pengeluaran', 'pengeluaran.diterima_oleh', 'pengeluaran_detail.nama_pengeluaran', 'Jumlah', 'harga_satuan', 'Subtotal')
            // berelasi dengan table pengeluaran lewat column pengeluaran_id
            // gabung dengan table pengeluaran, value table pengeluaran, column pengeluaran_id sama dengan value table pengeluaran.pengeluaran_id
            ->join('pengeluaran', 'pengeluaran.pengeluaran_id', '=', 'pengeluaran_detail.pengeluaran_id')
            // misalnya user memasukkan tanggal awal nya adalah 01-01-2023 lalu tanggal_akhir nya adalah 03-01-2024 maka cetak beberapa baris pengeluaran dari periode itu
            // diamana antara value table pengeluaran, column waktu_pengeluaran, tanggal_awal sampai tanggal_akhir
            ->whereBetween('pengeluaran.waktu_pengeluaran', [$this->tanggal_awal, $this->tanggal_akhir])
            // dapatkan data nya
            ->get();
    }

    // misal aku ingin cetak tanggal 11/11/2023 saja maka tanggal 12/11/2023 tidak tercetak maka aku butuh data contoh dulu aku akan atur menjadi 12/11/2023

    
}
