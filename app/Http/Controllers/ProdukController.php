<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Penyuplai;
use Illuminate\Support\Facades\Validator;
// package laravel datatables
use DataTables;
// untuk mencetak pdf
use Barryvdh\DomPDF\Facade\Pdf;

class ProdukController extends Controller
{
    /**
     * Menampikan data produk
     *
     * @return \Illuminate\Http\Response
     */
    // $request aku gunakan untuk mengambil permintaan ajax milik script
    public function index(Request $request)
    {
        // jika yang login value column is_admin nya adalah 1 berarti dia adalah admin maka ke tampilan produk.index
        if (auth()->user()->is_admin === 1) {
            // jika yang login adalah admin maka
            // jika $permintaan memiliki ajax
            if ($request->ajax()) {
                // syntax punya laravel
                // Bersemangat memuat banyak hubungan dengan kategori dan penyuplai, , ini wajib jika relasi nya adalah belongsTo atau miliki
                // produk berelasi dengan kategori dan penyuplai, ambil semuanya datanya, urutannya dari Z sampai A.
                $data_produk = Produk::with(['kategori', 'penyuplai'])->latest()->get();
                // syntax punya yajra
                // kembalikkan datatables dari $data_produk
                return DataTables::of($data_produk)
                    // fitur centang menggunakan checkbox agar aku bisa menghapus dan cetak barcode berdasarkan data yang dipilih
                    // $produk berarti ulang detail produk
                    ->addColumn('select', function (Produk $produk) {
                        // return element html
                        // name="produk_ids[]" karena dia akan menyimpan banyak produk_id
                        return '
                                <input name="produk_ids[]" value="' . $produk->produk_id . '" class="pilih input form-check-input mx-auto" type="checkbox">
                        ';
                    })
                    // untuk membuat pengulangan nomor
                    // tambah index column
                    ->addIndexColumn()
                    ->addColumn('kode_produk', function (Produk $produk) {
                        // $produk->kode_produk ambil value column kode_produk dari table produk
                        return '<span class="badge badge-success">' . $produk->kode_produk . '</span>';
                    })
                    // relasi table produk ke table kategori
                    // berdasarkan addColumn('nama_kategori')
                    // tambah column nama_kategori, jalankan fungsi berikut dan lakukan pengulangan terhadap detail produk
                    ->addColumn('nama_kategori', function (Produk $produk) {
                        // panggil semua value column nama_kategori milik table kategori yang berelasi dengan table produk
                    // panggil Models/Produk, method kategori
                        return $produk->kategori->nama_kategori;
                    })
                    // tambah column nama_penyuplai, jalankan fungsi berikut dan lakukan pengulangan terhadap detail produk
                    ->addColumn('nama_penyuplai', function (Produk $produk) {
                        // panggil semua value column nama_penyuplai milik table penyuplai yang berelsasi dengan table produk
                        return $produk->penyuplai->nama_penyuplai;
                    })
                    ->addColumn('harga_beli', function (Produk $produk) {
                        // panggil fungsi rupiah_bentuk di helpers
                        return rupiah_bentuk($produk->harga_beli);
                    })
                    ->addColumn('harga_jual', function (Produk $produk) {
                        return rupiah_bentuk($produk->harga_jual);
                    })
                    ->addColumn('diskon', function (Produk $produk) {
                        return $produk->diskon . "%";
                    })
                    ->addColumn('stok', function (Produk $produk) {
                        return angka_bentuk($produk->stok);
                    })
                    // buat tombol edit
                    ->addColumn('action', function (Produk $produk) {
                        $btn = '
                            <button data-id="' . $produk->produk_id . '" class="tombol_edit btn btn-warning btn-sm">
                                <i class="fas fa-pencil-alt"></i> Edit
                            </button>
                        ';
                        return $btn;
                    })
                    // jika column berisi elemnt html, relasi antar table, memanggil helpers dan melakukan concatenation
                    ->rawColumns(['select', 'kode_produk', 'nama_kategori', 'nama_penyuplai', 'harga_beli', 'harga_jual', 'stok', 'action'])
                    // buat nyata
                    ->make(true);
            };

            // jika $request tidak memiliki ajax maka kembalikan ke tampilan produk.index
            return view('produk.index');
        }
        // lain jika autentikasi pengguna, value column is_admin nya adalah 2 berarti dia adalah pembeli maka
        elseif (auth()->user()->is_admin === 2) {
            // jika yang login adalah pembeli maka
            // jika $permintaan memiliki ajax
            if ($request->ajax()) {
                // syntax punya laravel
                // Bersemangat memuat banyak hubungan dengan kategori, ini wajib jika relasi nya adalah belongsTo atau miliki
                // produk berelasi dengan kategori d ambil semuanya datanya, urutannya dari Z sampai A.
                $data_produk = Produk::with(['kategori'])->latest()->get();
                // syntax punya yajra
                // kembalikkan datatables dari $data_produk
                return DataTables::of($data_produk)
                    // untuk membuat pengulangan nomor
                    // tambah index column
                    ->addIndexColumn()
                    // relasi table produk ke table kategori
                    // berdasarkan addColumn('nama_kategori')
                    // tambah column nama_kategori, jalankan fungsi berikut dan lakukan pengulangan terhadap detail produk
                    ->addColumn('nama_kategori', function (Produk $produk) {
                        // panggil semua value column nama_kategori milik table kategori yang berelasi dengan table produk
                        // panggil Models/Produk, method kategori
                        return $produk->kategori->nama_kategori;
                    })
                    ->addColumn('harga_jual', function (Produk $produk) {
                        // kembalikkan panggil helper rupiah_bentuk untuk membuat format rupiah lalu kirimkan value column harga_jual dari detail produk
                        return rupiah_bentuk($produk->harga_jual);
                    })
                    ->addColumn('diskon', function (Produk $produk) {
                        // kembalikkan value detail produk, column diskon lalu gabungkan dengan string persen
                        return $produk->diskon . "%";
                    })
                    ->addColumn('stok', function (Produk $produk) {
                        // kembalikkan panggil helper angka_bentuk lalu kirimkan value detail produk, column stok
                        return angka_bentuk($produk->stok);
                    })
                    // buat tombol edit
                    ->addColumn('action', function (Produk $produk) {
                        // jika ingin membuat attribute maka gunakan attribute data-nama-attribute-yg-diinginkan
                        $btn = '
                            <button data-produk-id="' . $produk->produk_id . '" data-user-id="' . auth()->user()->user_id . '" class="tombol_edit btn btn-success btn-sm mb-1 tombol_keranjang">
                                <i class="mdi mdi-cart-plus"></i>
                            </button>
                            <button data-id="' . $produk->produk_id . '" class="tombol_edit btn btn-purple btn-sm">
                                <i class="mdi mdi-cash"></i>
                            </button>
                        ';
                        // jika menggunakan variable maka aku bisa gabungkan sintax maka ini bagus
                        return $btn;
                    })
                    // jika column berisi elemnt html, relasi antar table, memanggil helpers dan melakukan concatenation
                    ->rawColumns(['nama_kategori', 'harga_jual', 'diskon', 'stok', 'action'])
                    // buat nyata
                    ->make(true);
            };

            // kembalikkan ke tampilan pembeli.produk.index
            return view('pembeli.produk.index');
        }


    }

    // berfungsi untuk menampilkan semua kategori dan penyuplai di modal tambah produk
    public function data_relasinya()
    {
        // berisi kategori pilih semua value column karegori_id dan nama_kategori
        $semua_kategori = Kategori::select('kategori_id', 'nama_kategori')->get();
        $semua_penyuplai = Penyuplai::select('penyuplai_id', 'nama_penyuplai')->get();
        // kembalikkan tanggapan berupa json
        return response()->json([
            // key status berisi 200
            'status' => 200,
            // key semua_kategori berisi semua kategori
            'semua_kategori' => $semua_kategori,
            // key semua_penyuplai berisi semua penyuplai
            'semua_penyuplai' => $semua_penyuplai
        ]);
    }

    /**
     * Simpan produk
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // parameter $permintaan berisi semua value data yang dikirim oleh ajax javascript
    public function store(Request $request)
    {
        // buat validasii kepada semua element input yang memiliki attribute name
        // berisi valitator buat semua permintaan
        $validator = Validator::make($request->all(), [
            // input name="nama_produk" berisi atturan berikut
            'nama_produk' => 'required|unique:produk|max:255|min:2',
            'merk' => 'required|min:2|max:20',
            'harga_beli' => 'required',
            // gt berarti greater than atau harus lebih besar dari input name="harga_beli"
            'harga_jual' => 'required|gt:harga_beli',
        ],
        // terjemahan khusus untuk error .unique
        [
            'nama_produk.unique' => 'Produk sudah ada',
        ]);


        // jika validator gagal maka
        if ($validator->fails()) {
            // kembalikkan tanggapan berupa json
            return response()->json([
                // key status berisi value 0
                'status' => 0,
                // key message berisi value error
                'Message' => 'Harap isi formulir dengan benar',
                // key errrors berisi semua value attribute name dan pesan eror
                'errors' => $validator->errors()
            ]);
        }
        // lain jiak validasi nya berhasil atau formulir nya diisi dengan benar
        else {
            // ambil satu baris data produk yang terakhir
            $baris_data_produk_yg_terakhir = Produk::orderBy('produk_id', 'desc')->first();
            // jika baris data produk yang terakhir tidak ada karena belum ada produk maka $kode_produk_yg_terakhir diisi P-00001
            // jika tidak ada baris data produk yang terakhir
            if (!$baris_data_produk_yg_terakhir) {
                // Simpan produk dengan cara produk buat
                produk::create([
                    // column kategori_id di table produk diisi dengan value select name="kategori_id"
                    'kategori_id' => $request->kategori_id,
                    'penyuplai_id' => $request->penyuplai_id,
                    // diisi dengan "p" digabung value variabel kode_produk
                    'kode_produk' => 'P-00001',
                    'nama_produk' => $request->nama_produk,
                    'merk' => $request->merk,
                    'harga_beli' => $request->harga_beli,
                    'diskon' => 0,
                    'harga_jual' => $request->harga_jual,
                    'stok' => 0
                ]);
            }
            // jiKA ada baris data produk yang terakhir, anggaplah ada P-00001
            else if ($baris_data_produk_yg_terakhir) {
                // berisi mengambil detail_produk yang terakhir, column kode_produk, anggaplah berisi "P-00001"
                $kode_produk_yg_terakhir = $baris_data_produk_yg_terakhir->kode_produk;
                // anggaplah data terakhir berisi "P-00001"
                // maka saya tidak akan bisa melakukan "P-00001" + 1 karena string + integer = string
                // aku butuh explode agar bisa memecah menggunakan -

                // explode akan memecah string berdasarkan argumen pertama yang dikirim
                // anggaplah menghasilkan ["P", "00001"]
                $explode_kode_produk = explode("-", $kode_produk_yg_terakhir);
                // "00001" akan menjadi 1 lalu di tambah 1 maka menjadi 2 untuk data selanjutnya
                $ubah_string_kode_produk_menjadi_integer = (int) $explode_kode_produk[1] + 1;

                // panggil fungsi helper kode_berurutan agar menjadi P-00002
                // 5 adalah jumlah angka nya, jadi contohnya adalah 10000
                $kode_produk = kode_berurutan($ubah_string_kode_produk_menjadi_integer, 5);

                // Simpan produk dengan cara produk buat
                produk::create([
                    // column kategori_id di table produk diisi dengan value select name="kategori_id"
                    'kategori_id' => $request->kategori_id,
                    'penyuplai_id' => $request->penyuplai_id,
                    // diisi dengan "p" digabung value variabel kode_produk
                    'kode_produk' => 'P-' . $kode_produk,
                    'nama_produk' => $request->nama_produk,
                    'merk' => $request->merk,
                    'harga_beli' => $request->harga_beli,
                    'diskon' => 0,
                    'harga_jual' => $request->harga_jual,
                    'stok' => 0
                ]);
            };


            // kembalikkan tanggapan berupa json
            return response()->json([
                // key status berisi value 200
                'status' => 200,
                // key pesan berisi value misalnya "Produk paket smartfren 6 GB sebulan berhasil disimpan
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
    // $produk_id beisi value produk_id anggaplah 1 yang dikirim lewat url
    public function show($produk_id)
    {
        // ambil detail produk berdasarkan produk_id yang dikirimkan
        // berisi produk dimana value column produk_id sama dengan value $produk_id yang dikirimkan, ambil data baris pertama
        $detail_produk = Produk::where('produk_id', $produk_id)->first();
        // ambil semua kategori
        // kategori pilih semua value dari column kategori_id dan nama_kategori
        $semua_kategori = Kategori::select('kategori_id', 'nama_kategori')->get();
        // ambil semua penyuplai
        // penyuplai pilih semua nilai dari column penyuplai_id dan column nama_penyuplai lalu dapatkan
        $semua_penyuplai = Penyuplai::select('penyuplai_id', 'nama_penyuplai')->get();
        // kembalikkan tanggapan berupa json
        return response()->json([
            // key produk_id berisi memanggil fungsi angka_bentuk milik helpers lalu di dalamnya ada detail_produk, value column produk_id
            'produk_id' => $detail_produk->produk_id,
            'harga_beli' => rupiah_bentuk($detail_produk->harga_beli),
            'harga_jual' => rupiah_bentuk($detail_produk->harga_jual),
            'stok' => angka_bentuk($detail_produk->stok),
            'detail_produk' => $detail_produk,
            'semua_kategori' => $semua_kategori,
            'semua_penyuplai' => $semua_penyuplai
        ]);
    }

    // Memperbarui produk yang spesifik di penyimpanan
    // parameter $request berisi semua value input
    // parameter $produk_id berisi produk_id yang dikirim lewat url, anggaplah berisi angka 1
    public function update(Request $request, $produk_id)
    {
        // ambil detail produk berdasarkan produk_id
        // produk dimana value column produk_id sama dengan produk_id yang dikirimkan
        $detail_produk = Produk::where('produk_id', $produk_id)->first();

        // jika nilai input name="nama_produk" sama dengan nilai column nama_produk milik table produk
        if ($request->nama_produk === $detail_produk->nama_produk) {
            // input name="nama_peroduk" berisi atruan berikut
            $validasi_nama_produk = 'required|string|min:2|max:255';
        // lain jika iput name="nama_produk" tidak sama dengan detail_produk->nama_produk
        } else if ($request->nama_produk !== $detail_produk->nama_produk) {
            $validasi_nama_produk = 'required|string|min:2|max:255|unique:produk';
        };

        // buat validasi untuk semua elemnt input yang memiliki attribute name
        // validator buat semua permintaan
        $validator = Validator::make($request->all(), [
            // input name="nama_produk" berisi aturan berikut
            'nama_produk' => $validasi_nama_produk,
            'merk' => 'required|max:20|min:2',
            'harga_beli' => 'required',
            // diskon tidak harus diisi karena defaultnya adalah 0
            'diskon' => 'required|integer|min:0|max:100',
            // gt adalah singkatan dari greater than, berarti harga jual harus lebih tinggi dari harga beli
            'harga_jual' => 'required|gt:harga_beli',
            'stok' => 'required|integer|min:1'
        ], [
            'nama_produk.unique' => 'produk sudah ada',
        ]);

        // jika validasi gagal
        if ($validator->fails()) {
            // kembalikkan tanggapan berupa json
            return response()->json([
                // key status berisi 0
                'status' => 0,
                // key errors berisi semua value attribute name yang error dan pesan errornya
                'errors' => $validator->errors()->toArray()
            ]);
        }
        // jika validasi berhasil
        else {
            // Perbarui produk
            // value detail produk, column kategori_id diisi dengan value input name="kategori_id"
            $detail_produk->kategori_id = $request->kategori_id;
            $detail_produk->penyuplai_id = $request->penyuplai_id;
            $detail_produk->nama_produk = $request->nama_produk;
            $detail_produk->merk = $request->merk;
            $detail_produk->harga_beli = $request->harga_beli;
            $detail_produk->diskon = $request->diskon;
            $detail_produk->harga_jual = $request->harga_jual;
            $detail_produk->stok = $request->stok;
            // detail_produk di update
            $detail_produk->update();

            // kembalikkan tanggapan berupa json
            return response()->json([
                // key status berisi 200
                'status' => 200,
                // key pesan berii pesan berikut
                'pesan' => "Produk $request->nama_produk berhasil diperbarui.",
            ]);
        };
    }

    // menghapus data-data yang di pilih
    public function destroy(Request $request)
    {
        // return response()->json($request);
        // hapus produk yang dipilih
        // produk di mana dalam column produk_id berisi value value berikut maka hapus
        produk::whereIn('produk_id', $request->produk_ids)->delete();

        return response()->json([
            'status' => 200,
            'pesan' => 'Berhasil menghapus produk yang dipilih'
        ]);
    }

    // request berisi beberapa value dari column produk_id,
    public function cetak_barcode(Request $request)
    {
        // dd($request);
        // buat array kosong
        $beberapa_produk = array();

        // lakukan pengulangan pada array produk_ids
        // untuk setiap $permintaan produk_ids sebagai $produk_id
        foreach ($request->produk_ids as $produk_id) {
            // berisi mengambil detail_produk secara berulang, anggaplah ada 2 detail_produk
            // produk dimana value column produk_id sama dengan produk_id, baris pertama
            $detail_produk = Produk::where('produk_id', $produk_id)->first();
            // push data ke array $beberapa_produk
            $beberapa_produk[] = $detail_produk;
        };

        // return $beberapa_produk;

        $no = 1;
        // menggunakan package dompdf
        $pdf = Pdf::loadView('produk.barcode', [
            'beberapa_produk' => $beberapa_produk,
            'no' => $no
        ]);
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('produk.pdf');
    }
}
