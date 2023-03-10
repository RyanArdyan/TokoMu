<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProdukPenyuplai;
use App\Models\Kategori;
use App\Models\Penyuplai;
use Illuminate\Support\Facades\Validator;
// package laravel datatables
use DataTables;

class ProdukPenyuplaiController extends Controller
{
    /**
     * Ke tampilan produk_penyuplai.index
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // kembalikan ke tampilan produk_penyuplai.index
        return view('produk_penyuplai.index');
    }

    // untuk mengecek apakah ada kategori, jika tidak ada maka arahkan ke menu kategori
    public function cek_kategori_dan_penyuplai()
    {
        // ambil detail_kategori_pertama agar jika kategori pertama tidak ada maka kasi tau user, bahwa mereka harus membuat kategori terlebih dahulu
        // berisi ambil detail kategori pertama
        $detail_kategori_pertama = Kategori::first();

        // jika detail_kategori_pertama tidak ada atau NULL
        if ($detail_kategori_pertama === null) {
            // kembalikkan tanggapan berupa json
            return response()->json([
                // key message 
                'message' => 'Anda belum membuat satu pun kategori.'
            ]);
        };

        $detail_penyuplai_pertama = Penyuplai::first();
        // lain jika detail_penyuplai_pertama tidak ada atau null maka
        if ($detail_penyuplai_pertama === null) {
            // kembalikkan tanggapan berupa json
            return response()->json([
                // key message berisi pesan berikut
                'pesan' => 'Anda belum membuat satu pun penyuplai.'
            ]);
        };

        // $detail_kategori_pertama = Kategori::first();
        // $detail_penyuplai_pertama = Penyuplai::latest()->get();
        // return response()->json([
        //     'detail_kategori_pertama' => $detail_kategori_pertama,
        //     'detail_penyuplai_pertama' => $detail_penyuplai_pertama
        // ]);

    }

    // menampilkan daftar produk penyuplai
    public function read()
    {
        // Bersemangat memuat banyak hubungan dengan kategori dan penyuplai
        // Model ProdukPenyuplai berelasi dengan kategori dan penyuplai, ambil semuanya datanya, urutannya dari Z sampai A.
        $data_produk = ProdukPenyuplai::with(['kategori', 'penyuplai'])->latest()->get();
        // syntax punya yajra
        // kembalikkan datatables dari $data_produk
        return DataTables::of($data_produk)
            // fitur centang menggunakan checkbox agar aku bisa menghapus dan cetak barcode berdasarkan data yang dipilih
            // $produk_penyuplai berarti ulang detail produk
            ->addColumn('select', function (ProdukPenyuplai $produk_penyuplai) {
                // return element html
                // name="produk_penyuplai_ids[]" karena dia akan menyimpan banyak produk_penyuplai_id
                return '
                        <input name="produk_penyuplai_ids[]" value="' . $produk_penyuplai->produk_penyuplai_id . '" class="pilih input form-check-input mx-auto" type="checkbox">
                ';
            })
            // untuk membuat pengulangan nomor
            // tambah index column
            ->addIndexColumn()
            // tambah column nama_kategori, jalankan fungsi berikut dan lakukan pengulangan terhadap detail produk
            ->addColumn('nama_kategori', function ($produk_penyuplai) {
                // panggil semua value column nama_kategori milik table kategori yang berelsasi dengan table produk
                return $produk_penyuplai->kategori->nama_kategori;
            })
            // tambah column nama_penyuplai, jalankan fungsi berikut dan lakukan pengulangan terhadap detail produk
            ->addColumn('nama_penyuplai', function ($produk_penyuplai) {
                // panggil semua value column nama_penyuplai milik table penyuplai yang berelsasi dengan table produk
                return $produk_penyuplai->penyuplai->nama_penyuplai;
            })
            ->addColumn('harga', function ($produk_penyuplai) {
                // panggil fungsi rupiah_bentuk di helpers
                return rupiah_bentuk($produk_penyuplai->harga);
            })
            // buat tombol edit
            ->addColumn('action', function ($produk_penyuplai) {
                // attribute data-id berfungsi menyimpan semua value column produk_penyuplai_id
                $btn = '
                    <button data-id="' . $produk_penyuplai->produk_penyuplai_id . '" class="tombol_edit btn btn-warning btn-sm">
                        <i class="fas fa-pencil-alt"></i> Edit
                    </button>
                ';
                return $btn;
            })
            // jika column berisi elemnt html, relasi antar table, memanggil helpers dan melakukan concatenation
            ->rawColumns(['select', 'nama_kategori', 'nama_penyuplai', 'harga', 'action'])
            // buat nyata
            ->make(true);
    }

    // berfungsi untuk menampilkan semua kategori dan semua penyuplai di modal tambah produk
    // publik fungsi data_relasinya
    public function data_relasinya()
    {
        // berisi kategori pilih semua value column kategori_id dan nama_kategori milik table kategori
        $semua_kategori = Kategori::select('kategori_id', 'nama_kategori')->get();
        $semua_penyuplai = Penyuplai::select('penyuplai_id', 'nama_penyuplai')->get();
        // kembalikkan tanggapan berupa json
        return response()->json([
            // key status berisi value 200
            'status' => 200,
            // key semua_kategori berisi semua kategori
            'semua_kategori' => $semua_kategori,
            // key semua_penyuplai berisi semua penyuplai
            'semua_penyuplai' => $semua_penyuplai
        ]);
    }

    /**
     * Simpan produk penyuplai ke dalam table produk_penyuplai
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // $request berisi semua value element input dan element select yang punya attribute name
    public function store(Request $request)
    {
        // buat validasii kepada semua element input yang memiliki attribute name
        // berisi valitator buat semua permintaan
        $validator = Validator::make($request->all(), [
            // input name="nama_produk" berisi aturan berikut
            'nama_produk' => 'required|unique:produk_penyuplai|max:255|min:2',
            'merk' => 'required|min:2|max:20',
            'harga' => 'required',
        ]);

        // jika validator gagal maka 
        if ($validator->fails()) {
            // kembalikkan tanggapan berupa json
            return response()->json([
                // key status berisi value 0
                'status' => 0,
                // key pesan berisi value error
                'pesan' => 'Validasi formulir menemukan kesalahan atau errors input.',
                // key errrors berisi semua value attribute name yang error dan semua pesan error nya
                'errors' => $validator->errors()
            ]);
        }
        // lain jika validasi nya berhasil
        else {

            // Simpan produk penyuplai dengan cara ProdukPenyuplai buat
            ProdukPenyuplai::create([
                // column nama_produk di table produk diisi dengan value input name="nama_produk"
                'nama_produk' => $request->nama_produk,
                'kategori_id' => $request->kategori_id,
                'penyuplai_id' => $request->penyuplai_id,
                'merk' => $request->merk,
                'harga' => $request->harga
            ]);
            // kembalikkan tanggapan berupa json
            return response()->json([
                // key status berisi value 200
                'status' => 200,
                // key pesan berisi value misalnya "Produk smarfren unlimeted berhasil disimpan
                'pesan' => "Produk $request->nama_produk berhasil disimpan."
            ]);
        };
    }

    /**
     * Menampilkan produk yang spesifik di modal edit
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // $produk_penyuplai_id beisi value produk_penyuplai_id anggaplah 1
    public function show($produk_penyuplai_id)
    {
        // ambil detail_produk_penyuplai berdasarkan produk_penyuplai_id yang dikirimkan lewat url dan script
        // berisi produk_penyuplai dimana value column produk_penyuplai_id sama dengan $produk_penyuplai_id yang dikirimkan
        $detail_produk_penyuplai = ProdukPenyuplai::where('produk_penyuplai_id', $produk_penyuplai_id)->first();
        // ambil semua kategori
        // kategori pilih semua value dari column kategori_id dan nama_kategori
        $semua_kategori = Kategori::select('kategori_id', 'nama_kategori')->get();
        // ambil semua penyuplai
        // penyuplai pilih semua nilai dari column penyuplai_id dan column nama_penyuplai lalu dapatkan
        $semua_penyuplai = Penyuplai::select('penyuplai_id', 'nama_penyuplai')->get();
        // kembalikkan tanggapan berupa json
        return response()->json([
            // key detail_produk_penyuplai berisi detail_produk_penyuplai
            'detail_produk_penyuplai' => $detail_produk_penyuplai,
            'semua_kategori' => $semua_kategori,
            'semua_penyuplai' => $semua_penyuplai
        ]);
    }

    /**
     * Memperbarui detail produk_penyuplai di table produk_penyuplai
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $produk_penyuplai_id)
    {
        // ambil detail produk_penyuplai berdasarkan produk_penyuplai_id
        // produk_penyuplai dimana value column produk_penyuplai_id sama dengan produk_penyuplai_id yang dikirimkan
        $detail_produk_penyuplai = ProdukPenyuplai::where('produk_penyuplai_id', $produk_penyuplai_id)->first();

        // jika nilai input name="nama_produk" sama dengan nilai column nama_produk milik table produk
        if ($request->nama_produk === $detail_produk_penyuplai->nama_produk) {
            // input name="nama_peroduk" berisi atruan berikut
            $validasi_nama_produk = 'required|string|min:2|max:255';
        // lain jika input name="nama_produk" tidak sama dengan detail_produk->nama_produk
        } else if ($request->nama_produk !== $detail_produk_penyuplai->nama_produk) {
            $validasi_nama_produk = 'required|string|min:2|max:255|unique:produk_penyuplai';
        };

        // buat validasi untuk semua elemnt input yang memiliki attribute name
        // validator buat semua permintaan
        $validator = Validator::make($request->all(), [
            // input name="nama_produk" berisi aturan berikut
            'nama_produk' => $validasi_nama_produk,
            'merk' => 'required|max:20|min:2',
            'harga' => 'required'
        ]);

        // jika validator gagal
        if ($validator->fails()) {
            // kembalikkan tanggapan berupa json
            return response()->json([
                // key status berisi 0
                'status' => 0,
                // key errors berisi semua value attribute name yang error dan pesan errornya
                'errors' => $validator->errors()->toArray()
            ]);
        // jika validasi berhasil
        } else {
            // Perbarui produk_penyuplai
            // value detail produk, column kategori_id diisi dengan value input name="kategori_id"
            $detail_produk_penyuplai->kategori_id = $request->kategori_id;
            $detail_produk_penyuplai->penyuplai_id = $request->penyuplai_id;
            $detail_produk_penyuplai->nama_produk = $request->nama_produk;
            $detail_produk_penyuplai->merk = $request->merk;
            $detail_produk_penyuplai->harga = $request->harga;
            // detail_produk di update
            $detail_produk_penyuplai->update();

            // kembalikkan tanggapan berupa json
            return response()->json([
                // key status berisi 200
                'status' => 200,
                // key pesan berii pesan berikut, "Misalnya, paket smarfren reguler berhasil di perbarui"
                'pesan' => "$request->nama_produk berhasil di perbarui.",
            ]);
        };
    }

    /**
     * Menghapus data-data produk_penyuplai yang dipilih
     */
    public function destroy(Request $request)
    {
        // hapus data-data produk_penyuplai yang dipilih
        // produk_penyuplai di mana dalam column produk_penyuplai_id berisi value-value $request->produk_penyuplai_ids misalnya ["1", "2"] maka hapus
        ProdukPenyuplai::whereIn('produk_penyuplai_id', $request->produk_penyuplai_ids)->delete();

        // kembalikkan tangapan berupa json
        return response()->json([
            // key status berisi value 200
            'status' => 200,
            // key pesan berisi pesan berikut
            'pesan' => 'Berhasil menghapus produk penyuplai yang dipilih'
        ]);
    }
}
