<?php

namespace App\Imports;

use App\Models\Pengeluaran;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PengeluaranImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Pengeluaran([
            'nama_pengeluaran' => $row['nama_pengeluaran'],
            'total_pengeluaran' => $row['total_pengeluaran']
        ]);
    }
}
