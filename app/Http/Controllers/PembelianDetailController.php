<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProdukPenyuplai;
use App\Models\Produk;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Penyuplai;
// package yajra/laravel-datatable
use DataTables;

class PembelianDetailController extends Controller
{
    /**
	 *  Menampilkan detail_penyuplai, beberapa pembelian_detail dan detail pembelian
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

		// misalnya, user berada di halaman dashboard lalu user memaksa masuk ke halaman pembelian_detail lewat url /pembelian-detail maka pastinya dia tidak punya session('pembelian_id') maka arahkan ke halaman dashboard lagi atau ke url sebelum nya
		if (!$pembelian_id) {
			// kembali ke url sebelum nya
			return back();
		};

		// anggaplah aku di menu dashboard lalu aku memaksa ke halaman pembelian_detail lewat url /pembelian-detail, ada session('pembelian_id') Yang berisi 1 lalu detail pembelian_id 1 aku hapus maka akan ada error jadi aku harus arahkan user ke url sebelumnya yaitu dashboard
		// jika tidak ada detail pembelian berdasarkan value column pembelian_id sama dengan $pembelian_id, data baris pertama
		if (!Pembelian::where('pembelian_id', $pembelian_id)->first()) {
			// kembali ke url sebelum nya
			return back();
		};

		// ambil detail penyuplai berdasarkan value $penyuplai_id
		// penyuplai dimana value column penyuplai_id sama dengan value $penyuplai_id yang dikirim, ambil data pertama, anggaplah $penyuplai_id berisi 1
		$detail_penyuplai = Penyuplai::where('penyuplai_id', $penyuplai_id)->first();
		// misalnya, penyuplai nya adalah smartfren maka ambil semua produk smartfren misalnya paket unlimited dan paket biasa, urutannya ascending atau naik atau dari a ke z
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
        // :with() berarti pemuatan bersemangat, aku melakukan itu karena 1 pembelian detail milik 1 pembelian
		// misalnya $pembelian_id berisi angka 1 maka ambil semua pembelian_detail yang column pembelian_id nya berisi angka 1
		// berisi table pembelian_detail berelasi dengan table produk_penyuplai, dimana value column pembelian_id sama dengan value $pembelian_id, lalu dapatkan semua data nya
		$beberapa_pembelian_detail = PembelianDetail::with(['produk'])->where('pembelian_id', $pembelian_id)->get();

        // $data = [];
		// buat array kosong
		$data = array();
		// columns berikut milik table pembelian
		// $total_barang berisi value 0
		$total_barang = 0;
		$total_harga = 0;

        // lakukan pengulangan terhadap $beberapa_pembelian_detail
		// untuk setiap $beberapa_pembelian_detail sebagai $pembelian_detail
        foreach ($beberapa_pembelian_detail as $pembelian_detail) {
			// $row = []
			// $row nantinya akan menjadi array assosiatif
			$row = array();
			// table pembelian_detail berelasi dengan table produk
			// contoh $row = ['nama_produk' => '...', 'harga' => '...']
			// membuat dan menambah array assosiatif
			// berisi pembelian_detail memanggil relasi nya yaitu produk lalu value column nama_produk
			$row['nama_produk'] = $pembelian_detail->produk->nama_produk;
			$row['harga'] = rupiah_bentuk($pembelian_detail->harga_beli);
			$row['jumlah'] = '
				<input type="number" class="form-control input-sm jumlah" data-pembelian-detail-id="' . $pembelian_detail->pembelian_detail_id . '" value="' . $pembelian_detail->jumlah . '">';
			$row['subtotal'] = rupiah_bentuk($pembelian_detail->subtotal);
			// buat attribute data-pembelian-detail-id yang menyimpan value column pembelian_detail_id
			$row['action'] = '
			<div class="btn btn-group">
				<button data-pembelian-detail-id="' . $pembelian_detail->pembelian_detail_id . '" class="tombol_hapus btn btn-sm btn-danger">Delete</button>
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
				<div class="total_harga hide">' . $total_harga . '</div>
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

	// mengambil daftar data table produk yang terkait penyuplai nya untuk di tampilkan di modal pilih produk misalnya penyuplai nya adalah perusahaan smartfren maka ambil semua produk perusahaan smartfren
	// anggaplah parameter $penyuplai_id berisi angka 1
	public function produk($penyuplai_id)
	{
		// misalnya parameter $penyuplai_id berisi 1 maka ambil semua produk yang value column penyuplai_id berisi 1
		// produk dimana value column penyuplai_id sama dengan value parameter $penyuplai_id, urutan data nya dari A ke Z atau dipesan oleh column nama_produk dan menaik lalu dapatkan semua data nya
        $semua_produk_terkait = Produk::where('penyuplai_id', $penyuplai_id)->orderBy('nama_produk', 'asc')->get();
        // syntax punya yajra
        // kembalikkan datatable dari semua_produk_terkait
        return DataTables::of($semua_produk_terkait)
        // untuk pengulangan nomor
        // tambah index column
        ->addIndexColumn()
		// aku akan mengubah 1000 menjadi Rp 1.000 menggunakan bantuan helpers rupiah_bentuk()
		// tambah column harga, jalankan fungsi, Produk $produk berisi pengulangan detail_produk
		->addColumn('harga_beli', function(Produk $produk) {
			// ubah 1000 menjadi Rp 1.000
			// kembalikkan panggil fungsi rupiah_bentuk di helpers.php lalu kirimkan $produk->harga_beli sebagai argumen
			return rupiah_bentuk($produk->harga_beli);
		})
        // $produk berarti ulang detail penyuplai
        // buat tombol pilih
        // tambah column action, jalankan fungsi, ambil semua detail
        ->addColumn('action', function(Produk $produk) {
            // buat attribute data-produk-id untuk menyimpan value detail produk, column produk_id
			// alasan aku menggunakan class="pilih_produk" daripada id="pilih_produk" karena id="" tidak boleh diulang
            return '
				<button data-produk-id='. $produk->produk_id .' data-harga-beli='. $produk->harga_beli .' type="button" class="pilih_produk btn btn-success btn-sm"><i class="fa fa-hand-point-right"></i> Pilih</button>
            ';
        })
        // jika column berisi membuat elemnet html maka harus dimasukkan ke rawColumns
        ->rawColumns(['harga_beli', 'action'])
        // buat nyata
        ->make(true);
	}

	/**
	 * Simpan detail pembelian_detail
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	// $request berisi semua value dari key data milik script js
	public function store(Request $request)
	{
        // return response()->json($request->all());

		// ambil detail_produk berdasarkan produk_id
		// berisi Produk dimana value column produk_id sama dengan value input name="produk_id", pertama
        $detail_produk = Produk::where('produk_id', $request->produk_id)->first();

        // jika tidak ada detail_produk
        if (!$detail_produk) {
			// kembalikkan tanggapan berupa json, kirimkan 2 data
            return response()->json([
				'pesan' => 'Produk Tidak Ada.'
			]);
        };

        // Simpan Data Ke table pembelian_detail
		// PembelianDetail buat
		PembelianDetail::create([
			// panggil column nama_produk di table pembelian_detail lalu diisi dengan 
			'nama_produk' => $detail_produk->nama_produk,
			// column pembelian_id diisi value $request->pembelian_id
			'pembelian_id' => $request->pembelian_id,
			'produk_id' => $request->produk_id,
			'harga' => $detail_produk->harga_beli,
			// bawaan jumlah pasti nya adalah 1
			'jumlah' => 1,
			// bawaan subtotal adalah sama dengan column harga_beli
			'subtotal' => $detail_produk->harga_beli
		]);

		// // kembalikkan tanggapan berupa json lalu kirimkan data berupa array assosiatif
        return response()->json([
			// key status berisi value 200
			'status' => 200,
			'pesan' => 'Pembelian Detail Berhasil Disimpan.'
		]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $pembelian_detail_id)
	{
        // Detail PembelianDetail
		// PembelianDetail dimana value column pembelian_detail_id sama dengan value parameter $pembelian_detail_id, data baris pertama
        $detail = PembelianDetail::where('pembelian_detail_id', $pembelian_detail_id)->first();
		// panggil detail PembelianDetail dimana value column jumlah di update dengan $request->jumlah, $request->jumlah didapatkan dari script milik pembelian_detail/index
		$detail->jumlah = $request->jumlah;
		// panggil detail PembelianDetail, column subtotal diisi dengan value detail harga * $request->jumlah
		$detail->subtotal = $detail->harga * $request->jumlah;
		// detail di update
		$detail->update();

        return response()->json([
			'status' => 200,
			'Berhasil Memperbarui Jumlah Dan Subtotal'
		]);
	}

	/**
	 * Menghapus 1 baris data pembelian_detail
	 *
	 * @param  int  $pembelian_detail_id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($pembelian_detail_id)
    {
		// ambil detail_pembelian_detail berdasarkan pembelian_detail_id
		// berisi PembelianDetail dimana value column pembelian_detail_id sama dengan value parameter $pembelian_detail_id, baris data pertama
        $detail_pembelian_detail = PembelianDetail::where('pembelian_detail_id', $pembelian_detail_id)->first();
		// hapus detail_pembelian_detail yang dipilih
		// detail_pembelian_detail dihapus
        $detail_pembelian_detail->delete();
        return response()->json([
			// key status berisi value 200, ini akan di pakai script
			'status' => 200,
			// key message akan memberi tau backend developer yang sedang menguji API bahwa pembelian detail berhasil dihapus.
			'message' => 'Detail Pembelian Berhasil Dihapus.'
		]);
    }

    public function reload_form($total_harga)
	{
		// kembalikkan tanggapan berupa json lalu kirimkan data
		return response()->json([
            // helpers rupiah_bentuk
			// key total_rp berisi panggil fungsi rupiah_bentuk di helpers.php lalu kirimkan $total_harga seagai argumen
			// anggalpah berisi Rp 300.000
			'total_harga' => $total_harga,
			// anggaplah berisi Rp 300.000
			'bayar_rp' => rupiah_bentuk($total_harga),
			// anggaplah berisi Senilai tiga ratus ribu rupiah 
			'terbilang' => ucwords(terbilang($total_harga)),
		]);
	}
}
