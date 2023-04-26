<?php

namespace App\Exports;

use App\Models\Pengeluaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PengeluaranExport implements FromCollection, WithHeadingRow
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Pengeluaran::select('pengeluaran_id', 'nama_pengeluaran', 'total_pengeluaran')->get();
    }

    public function headings(): array {
        return ["pengeluaran_id", "nama_pengeluaran", "total_pengeluaran"];
    }
}
