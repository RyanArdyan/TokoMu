<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// gunakan model keranjang
use App\Models\Keranjang;

class KeranjangController extends Controller
{
    // method index untuk mnampilkan halaman keranjang
    public function index()
    {
        // ambil semua data dari table keranjang berdasarkan value column user_id
        $semua_data_keranjang = Keranjang::where('user_id', auth()->user()->user_id)->get();
        // kembalikkan ke tampilan pembeli.keranjang.index lalu kirimkan value variable $semua_data_keranjang
        return view('pembeli.keranjang.index', [
            'semua_data_keranjang' => $semua_data_keranjang
        ]);
    }

    // $request berisi data-data formulir atau value input attribute name="" yang dikirim oleh script
    public function store(Request $request)
    {
        // tangkap value produk_id yang dikirim oleh script ajax
        $produk_id = $request->produk_id;
        $user_id = $request->user_id;

        // simpan keranjang
        // keranjang::buat([])
        $keranjang = Keranjang::create([
            // isi column produk_id dengan angka 2 karena pembeli
            'produk_id' => $produk_id,
            // column nama berisi value input name="nama"
            'user_id' => $request->user_id,
            'jumlah' => 1
        ]);

        // kembalikkan tanggapan berupa json lalu kirimkan data
        return response()->json([
            // key status berisi value 200
            'status' => 200,
            // key message berisi value berikut
            'message' => 'Berhasil memasukkan ke keranjang'
        ]);
    }
}
