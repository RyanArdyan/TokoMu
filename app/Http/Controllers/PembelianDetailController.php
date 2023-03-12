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
	 * Display a listing of the resource. Menampilkan detail_penyuplai, beberapa pembelian_detail dan detail pembelian
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

		// misalnya, user berada di halaman dashboard lalu user memaksa masuk ke halaman pembelian_detail lewat url /pembelian-detal maka arahkan ke halaman dashboard lagi atau ke url sebelum nya
		if (!$pembelian_id) {
			// kembali ke url sebelum nya
			return back();
		};

		// anggaplah aku di menu dashboard lalu aku memaksa ke halaman pembelian_detail lewat url /pembelian-detail, ada session('pembelian_id') Yang berisi 1 lalu detail pembelian_id 1 aku hapus maka akan ada error jadi aku harus arahkan user ke url dashbard
		// jika tidak ada detail pembelian berdasarkan pembelian_id
		if (!Pembelian::where('pembelian_id', $pembelian_id)->first()) {
			// kembali ke url sebelum nya
			return back();
		};

		// ambil detail penyuplai
		// penyuplai dimana value column penyuplai_id sama dengan value $penyuplai_id yang dikirim, ambil data pertama, anggaplah $penyuplai_id berisi 1
		$detail_penyuplai = Penyuplai::where('penyuplai_id', $penyuplai_id)->first();
		// misalnya, penyuplai nya adalah smartfren maka ambil semua produk smartfren misalnya paket unlimited dan paket biasa, urutannya ascending
		// ProdukPenyuplai dimana value column penyuplai_id sama dengan penyuplai_id yang dikirimkan, ambil semua produk penyuplai_terkait
		$semua_produk_penyuplai_terkait = ProdukPenyuplai::where('penyuplai_id', $penyuplai_id)->get();

		// ambil value dari detail_pembelian, column total_harga
		// pembelian dimana value column pembelian_id sama dengan pembelian_id, baris data pertama, column total_harga
		$detail_total_harga_pembelian = Pembelian::where('pembelian_id', $pembelian_id)->first()->total_harga;

		// kembalikkan ke tampilan pembelian_detail.index lalu kirimkan data
		return view('pembelian_detail.index', [
			// untuk menampilkan detail_penyuplai di tampilan pembelian_detail.index
			'detail_penyuplai' => $detail_penyuplai,
			// anggaplah key pembelian_id berisi value 1
			'pembelian_id' => $pembelian_id,
			// detail_total_harga_pembelian diisi 0 secara sementara
			'detail_total_harga_pembelian' => $detail_total_harga_pembelian
		]);
	}

	// untuk menampilkan data table pembelian_detail berdasarkan pembelian_id
	// misalnya $pembelian_id berisi angka 1 maka ambil semua pembelian_detail yang column pembelian_id nya berisi angka 1
	public function data($pembelian_id)
	{
        // :with() berarti pemuatan bersemangat, aku melakukan itu karena 1 pembelian detail memiliki 1 pembelian atau 1 pembelian deteil milik 1 pembelian
		// misalnya $pembelian_id berisi angka 1 maka ambil semua pembelian_detail yang column pembelian_id nya berisi angka 1
		// berisi table pembelian_detail berelasi dengan table produk_penyuplai
		$beberapa_pembelian_detail = PembelianDetail::with(['produk_penyuplai'])->where('pembelian_id', $pembelian_id)->get();

        // $data = [];
		// buat array kosong
		$data = array();
		// columns berikut milik table pembelian
		$total_barang = 0;
		$total_harga = 0;

        // lakukan pengulangan terhadap $beberapa_pembelian_detail
		// untuk setiap $beberapa_pembelian_detail sebagai $pembelian_detail
        foreach ($beberapa_pembelian_detail as $pembelian_detail) {
			// $row = []
			// $row nantinya akan menjadi array assosiatif
			$row = array();
			// table pembelian_detail berelasi dengan table produk
			// contoh $row = ['nama_produk' => '...', 'harga_beli' => '...']
			// membuat dan menambah array assosiatif
			// berisi pembelian_detail memanggil relasi nya yaitu produk_penyuplai
			$row['nama_produk'] = $pembelian_detail->produk_penyuplai['nama_produk'];
			$row['harga'] = rupiah_bentuk($pembelian_detail->harga);
			$row['jumlah'] = '
				<input type="number" class="form-control input-sm quantity" data-id="' . $pembelian_detail->pembelian_detail_id . '" value="' . $pembelian_detail->jumlah . '">';
			$row['subtotal'] = rupiah_bentuk($pembelian_detail->subtotal);
			$row['action'] = '
			<div class="btn btn-group">
				<button onclick="deleteData(`'. route('pembelian-detail.destroy', $pembelian_detail->pembelian_detail_id) . '`)" class="btn btn-sm btn-danger">Delete</button>
			</div>';
			// $data adalah array yang berisi array
			// $data = [
				// []
			// ]
            // $data berisi banyak <tr>
			$data[] = $row;

            // total_harga dan total_barang akan masuk ke table pembelian, column total_harga dan total_barang
			// anggaplah total_harga berisi 0 lalu di tambah (100.000 * 2)
			$total_harga += $pembelian_detail->harga * $pembelian_detail->jumlah;
			// anggaplah total_barang berisi 0 lalu di tambah 5 berarti jadi 5
			$total_barang += $pembelian_detail->jumlah; 
		};

		// tambahkan array di dalam array
		$data[] = [		
			'nama_produk' => '',
			'harga' => '',
			// simpan total_barang misalnya 10 di column jumlah
			'jumlah' => '
				<div class="total_barang hide">' . $total_barang . '</div>
			',
			// simpan total_harga misalnya Rp 1.000.000 di column total_harga
			'subtotal' => '
				<div class="total hide">' . $total_harga . '</div>
			',
			'action' => ''
		];

		// kembalikkan datatables dari $data
		return datatables()
			->of($data)
			// lakukan pengulangan nomor
			->addIndexColumn()
            // jika $row berisi element html maka aku harus memasukkannya kedalam rawColumns
			->rawColumns(['jumlah', 'subtotal', 'action'])
			// buat nyata
			->make(true);
	}

	// mengambil daftar data table produk_penyuplai untuk di tampilkan di modal pilih produk_penyuplai
	// anggaplah parameter $penyuplai_id berisi angka 1
	public function produk_penyuplai($penyuplai_id)
	{
		// misalnya parameter $penyuplai_id berisi 1 maka ambil semua produk_penyuplai yang value column penyuplai_id berisi 1
		// produk_penyuplai dimana value column penyuplai_id sama dengan value parameter $penyuplai_id, urutan data nya dari A ke Z atau dipesan oleh column nama_produk dan menaik lalu dapatkan semua data nya
        $semua_produk_penyuplai = ProdukPenyuplai::where('penyuplai_id', $penyuplai_id)->orderBy('nama_produk', 'asc')->get();
        // syntax punya yajra
        // kembalikkan datatable dari semua_produk_penyuplai
        return DataTables::of($semua_produk_penyuplai)
        // untuk pengulangan nomor
        // tambah index column
        ->addIndexColumn()
		// aku akan mengubah 1000 menjadi Rp 1.000 menggunakan bantuan helpers rupiah_bentuk()
		// tambah column harga, jalankan fungsi, ProdukPenyuplai $produk_penyuplai berisi pengulangan detail_produk_penyuplai
		->addColumn('harga', function(ProdukPenyuplai $produk_penyuplai) {
			// ubah 1000 menjadi Rp 1.000
			// kembalikkan panggil fungsi rupiah_bentuk di helpers.php lalu kirimkan $produk_penyuplai->harga sebagai argumen
			return rupiah_bentuk($produk_penyuplai->harga);
		})
        // $produk_penyuplai berarti ulang detail penyuplai
        // buat tombol pilih
        // tambah column action, jalankan fungsi, ambil semua detail_penyuplai
        ->addColumn('action', function(ProdukPenyuplai $produk_penyuplai) {
            // buat attribute data-produk-penyuplai-id untuk menyimpan value detail produk_penyuplai, column produk_penyuplai_id
            return '
				<button data-produk-penyuplai-id='. $produk_penyuplai->produk_penyuplai_id .' data-harga='. $produk_penyuplai->harga .' type="button" class="pilih_produk_penyuplai btn btn-success btn-sm"><i class="fa fa-hand-point-right"></i>Pilih</button>
            ';
        })
        // jika column berisi membuat elemnet html maka harus dimasukkan ke rawColumns
        ->rawColumns(['harga', 'action'])
        // buat nyata
        ->make(true);
	}

	/**
	 * Simpan detail pembelian_detail
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	// $request berisi semua value input dari #form_produk_penyuplai di file form_produk_penyuplai.blade
	public function store(Request $request)
	{
        // return response()->json($request->all());

		// ambil detail_produk_penyuplai berdasarkan produk_penyuplai_id
		// berisi ProdukPenyuplai dimana value column produk_penyuplai_id sama dengan value input name="produk_penyuplai_id", pertama
        $detail_produk_penyuplai = ProdukPenyuplai::where('produk_penyuplai_id', $request->produk_penyuplai_id)->first();

        // jika tidak ada detail_produk_penyuplai
        if (!$detail_produk_penyuplai) {
			// kembalikkan tanggapan berupa json, kirimkan 2 data
            return response()->json([
				'pesan' => 'Produk Penyuplai Tidak Ada.'
			]);
        };

        // Simpan Data Ke table pembelian_detail
		// PembelianDetail buat
		PembelianDetail::create([
			// column pembelian_id diisi input name="pembelian_id"
			'pembelian_id' => $request->pembelian_id,
			'produk_penyuplai_id' => $request->produk_penyuplai_id,
			'harga' => $detail_produk_penyuplai->harga,
			// bawaan jumlah pasti nya adalah 1
			'jumlah' => 1,
			// bawaan subtotal adalah sama dengan column harga
			'subtotal' => $detail_produk_penyuplai->harga
		]);

		// // kembalikkan tanggapan berupa json lalu kirimkan data berupa array assosiatif
        return response()->json([
			// key status berisi value 200
			'status' => 200,
			'pesan' => 'Pembelian Detail Berhasil Disimpan.'
		]);
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

    public function reload_form($total)
	{	
		// misalnya, 300.000 - (100 * 300.000)
		$bayar = $total - (100 * $total);

		// kembalikkan tanggapan berupa json lalu kirimkan data
		return response()->json([
            // helpers rupiah_bentuk
			// key total_rp berisi panggil fungsi rupiah_bentuk di helpers.php lalu kirimkan $total seagai argumen
			// anggalpah berisi Rp 300.000
			'total_rp' => rupiah_bentuk($total),
			// anggaplah berisi 300000
			'bayar' => $bayar,
			// anggaplah berisi Rp 300.000
			'bayar_rp' => rupiah_bentuk($bayar),
			// anggaplah berisi Senilai tiga ratus ribu rupiah 
			'terbilang' => ucwords(terbilang($bayar)),
		]);
	}
}
