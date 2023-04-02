<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReturPembelian;

class ReturPembelianController extends Controller
{
    public function createReturPembelian(Request $request)
    {
        $returPembelian = new ReturPembelian();
        $returPembelian->id_pembelian = $request->input('id_pembelian');
        $returPembelian->tanggal_retur = $request->input('tanggal_retur');
        $returPembelian->jumlah_retur = $request->input('jumlah_retur');
        $returPembelian->alasan_retur = $request->input('alasan_retur');
        $returPembelian->save();

        return response()->json([
            'message' => 'Retur Pembelian berhasil ditambahkan',
            'data' => $returPembelian
        ]);
    }
}
