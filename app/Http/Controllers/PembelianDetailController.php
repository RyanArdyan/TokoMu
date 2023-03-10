<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProdukPenyuplai;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Penyuplai;
// package yajra/laravel-datatable
use DataTables;

class PembelianDetailController extends Controller
{
    /**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		// tangkap nilai dari sessi penyuplai_id dan pembelian_id yang dikirim oleh PembelianController, method create
        // aku butuh penyuplai_id agar aku bisa mengambil detail penyuplai lalu menampilkan nya di tampilan pembelian_detail.index
		$penyuplai_id = session('penyuplai_id');
        // aku butuh pembelian_id agar aku bisa menampilkan detail pembelian
		$pembelian_id = session('pembelian_id');

		// ambil detail penyuplai
        // penyuplai dimana value column penyuplai_id sama dengan value $penyuplai_id yang dikirim, ambil data pertama 
		$detail_penyuplai = Penyuplai::where('penyuplai_id', $penyuplai_id)->first();
		// misalnya, penyuplai nya adalah smartfren maka ambil semua produk smartfren misalnya paket unlimited dan paket biasa, urutannya ascending
        // ProdukPenyuplai dimana value column penyuplai_id sama dengan penyuplai_id yang dikirimkan, ambil semua produk penyuplai_terkait
		$semua_produk_penyuplai_terkait = ProdukPenyuplai::where('penyuplai_id', $penyuplai_id)->get();

		$detail_total_harga_pembelian = Pembelian::where('pembelian_id', $pembelian_id)->total_harga;

        // kembalikkan ke tampilan pembelian_detail.index lalu kirimkan data
		return view('pembelian_detail.index', [
            // untuk di tampilkan di modal pilih produk
			// key semua_produk_penyuplai_terkait berisi semua_produk_penyuplai_terkait
			'semua_produk_penyuplai_terkait' => $semua_produk_penyuplai_terkait,
            // untuk menampilkan detail_penyuplai di tampilan pembelian_detail.index
			'detail_penyuplai' => $detail_penyuplai,
			'pembelian_id' => $pembelian_id,
			'detail_total_harga_pembelian' => $detail_total_harga_pembelian
		]);
	}

	public function data($pembelian_id)
	{
        // :with() berarti pemuatan bersemangat, aku melakukan itu karena 1 pembelian detail memiliki 1 pembelian atau 1 pembelian deteil milik 1 pembelian
		$beberapa_pembelian_detail = PembelianDetail::with(['produk'])->where('pembelian_id', $pembelian_id)->get();

        // $data = [];
		$data = array();
		// columns berikut milik table pembelian
		$total_item = 0;
		$total_harga = 0;

        // copas
        foreach ($beberapa_pembelian_detail as $pembelian_detail) {
			// $row = []
			// $row nantinya akan menjadi array assosiatif
			$row = array();
			// table pembelian_detail berelasi dengan table produk
			// $row = ['kode_produk' => '...', 'nama_produk' => '...', 'harga_beli' => '...']
			// membuat dan menambah array assosiatif
			$row['kode_produk'] = '<span class="badge badge-success">' . $pembelian_detail->produk['kode_produk'] . '</span>';
			$row['nama_produk'] = $pembelian_detail->produk['nama_produk'];
			$row['harga_beli'] = rupiah_bentuk($pembelian_detail->harga_beli);
			$row['jumlah'] = '
				<input type="number" class="form-control input-sm quantity" data-id="' . $pembelian_detail->pembelian_id_detail . '" value="' . $pembelian_detail->jumlah . '">';
			$row['subtotal'] = rupiah_bentuk($pembelian_detail->subtotal);
			$row['action'] = '
			<div class="btn btn-group">
				<button onclick="deleteData(`'. route('pembelian-detail.destroy', $pembelian_detail->pembelian_id_detail) . '`)" class="btn btn-sm btn-danger">Delete</button>
			</div>';
			// $data adalah array yang berisi array
			// $data = [
				// []
			// ]
            // $data berisi banyak <tr>
			$data[] = $row;

            // total_harga dan total_item akan masuk ke table pembelian, column total_harga dan total_item
			$total_harga += $pembelian_detail->harga_beli * $pembelian_detail->jumlah;
			$total_item += $pembelian_detail->jumlah; 
		};

		$data[] = [
			'kode_produk' => '
				<div class="total hide">' . $total_harga . '</div> 
				<div class="total_item hide">' . $total_item . '</div>',			
			'nama_produk' => '',
			'harga_beli' => '',
			'jumlah' => '',
			'subtotal' => '',
			'action' => ''
		];

		return datatables()
			->of($data)
			->addIndexColumn()
            // jika $row berisi element html maka aku harus memasukkannya kedalam rawColumns
			->rawColumns(['kode_produk', 'jumlah', 'action'])
			->make(true);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		

        // copas
        // return response()->json($request->all());
        $detail_produk = Produk::find($request->id_produk);
        // jika tidak ada produk
        if (!$detail_produk) {
            return response()->json('Data Gagal Disimpan', 400);
        };

        // Simpan Data Ke table pembelian_detail
		PembelianDetail::create([
			'pembelian_id' => $request->pembelian_id,
			'id_produk' => $detail_produk->id_produk,
			'harga_beli' => $detail_produk->harga_beli,
			'jumlah' => 1,
			'subtotal' => $detail_produk->harga_beli
		]);

        return response()->json('Data berhasil disimpan', 200);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $pembelian_id_detail)
	{
        // copas
        // Detail PembelianDetail
        $detail = PembelianDetail::where('pembelian_id_detail', $pembelian_id_detail)->first();
        // Update
        PembelianDetail::where('pembelian_id_detail', $pembelian_id_detail)->update([
        	'jumlah' => $request->jumlah,
        	'subtotal' => $detail->harga_beli * $request->jumlah
        ]);

        return response()->json([
			'status' => 200,
			'Berhasil Memperbarui Jumlah Dan Subtotal'
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
    {
        $detail = PembelianDetail::where('pembelian_id_detail', $id)->first();
        $detail->delete();
        return response()->json(null, 204);
    }

    public function loadForm($diskon = 0, $total)
	 {
		$bayar = $total - ($diskon / 100 * $total);
		$data = [
            // helpers rupiah_bentuk
			'total_rp' => rupiah_bentuk($total),
			'bayar' => $bayar,
			'bayar_rp' => rupiah_bentuk($bayar),
			'terbilang' => ucwords(terbilang($bayar)),
		];

		return response()->json($data);
	 }
}
