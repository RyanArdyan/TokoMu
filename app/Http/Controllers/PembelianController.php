<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\Penyuplai;
use App\Models\PembelianDetail;
use App\Models\Produk;
use App\Models\ProdukPenyuplai;
use App\Models\ReturPembelian;
// gunakan package datatable
use DataTables;

class PembelianController extends Controller
{
    /**
     * Ke tampilan pembelian.index
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Pembelian::whereRaw('DATEDIFF(NOW(), created_at) > 1')->where('total_barang', 0)->delete();

        // hapus beberapa baris dari table pembelian yang column total_barang nya sama dengan 0 dan sudah lebih dari 60 menit
        Pembelian::where('total_barang', 0)->whereDate('created_at', '<=', now()->subMinute(60))->delete();

        // ambil semua penyuplai
        // berisi penyuplai di pesan oleh column nama_penyuplai, data nya dari A ke Z, lalu dapatkan semua data nya
        $semua_penyuplai = Penyuplai::orderBy('nama_penyuplai', 'asc')->get();
        // kembalikkan ke tampilan pembelian.index, kirimkan semua_penyuplai
        return view('pembelian.index', [
            'semua_penyuplai' => $semua_penyuplai
        ]);
    }

    // memanggil data milik table pembelian 
    public function data()
    {
        // tampilkan semua pembelian yang column total harga nya tidak sama dengan 0 lalu urutkan data dimulai dari yang paling baru
        // berisi pembelian dimana value column total_harga tidak sama dengan 0, dipesan oleh colum updated_at, menurun, dapatkan semua data
        $semua_pembelian = Pembelian::where('total_harga', '!=', 0)->orderBy('updated_at', 'desc')->get();
        // syntax punya yajra
        // disini aku melakukan pengulangan
        return DataTables::of($semua_pembelian)
            // nomor
            ->addIndexColumn()
            ->addColumn('tanggal', function ($pembelian) {
                // contoh tanggal nya adalah: Selasa, 7 Februari 2023
                return $pembelian->updated_at->isoFormat('dddd, D MMMM Y');
            })
            ->addColumn('penyuplai', function ($pembelian) {
                // panggil nama penyuplai yang berelesi dengan table pembelian
                return $pembelian->penyuplai->nama_penyuplai;
            })
            // ulang detail pembelian
            ->addColumn('total_barang', function ($pembelian) {
                return angka_bentuk($pembelian->total_barang);
            })
            ->addColumn('total_harga', function ($pembelian) {
                return rupiah_bentuk($pembelian->total_harga);
            })
            ->addColumn('status', function ($pembelian) {
                // jika value $pembelian->status sama dengan "Oke"
                if ($pembelian->status === "Oke") {
                    // return element p
                    return "<p>$pembelian->status</p>";
                }
                // lain jika value $pembelian->status sama dengan Retur
                else if ($pembelian->status === "Retur") {
                    // return element p
                    return "<p class='text-danger'>$pembelian->status</p>";
                }
            })
            // Buat tombol lihat pembelian detail, hapus dan retur pembelian
            // tambahColumn action, jalankan fungsi, parameter pembelian berisi semua pembelian detail
            ->addColumn('action', function ($pembelian) {
                // jika value $pembelian->status sama dengan 'Retur' maka kasi attribute disabled agar tidak bisa di click
                if ($pembelian->status === 'Retur') {
                    $tombol_retur = '
                    <button data-toggle="keterangan_alat" data-placement="top" title="Retur Pembelian" onclick="retur_pembelian(' . $pembelian->pembelian_id . ')" class="btn btn-danger btn-sm ml-2" disabled>
                    <i class="mdi mdi-credit-card-refund"></i>
                    </button>';
                }
                // jika value $pembelian->status sama dengan 'Oke' maka hapus attribute disabled agar tombol nya bisa di click
                else if ($pembelian->status === 'Oke') {
                    $tombol_retur = '
                    <button data-toggle="keterangan_alat" data-placement="top" title="Retur Pembelian" onclick="retur_pembelian(' . $pembelian->pembelian_id . ')" class="btn btn-danger btn-sm ml-2">
                    <i class="mdi mdi-credit-card-refund"></i>
                    </button>';
                };

                // data-toggle="keterangan_alat" adalah
                return '
                <div class="btn-group">
                    <button data-toggle="keterangan_alat" data-placement="top" title="Lihat semua pembelian detailnya" onclick="show_detail(`' . route('pembelian.show', $pembelian->pembelian_id) . '`)" class="btn btn-info btn-sm">
                    <i class="fas fa-eye"></i>
                    </button>

                    <button data-toggle="keterangan_alat" data-placement="top" title="Hapus" onclick="hapus_data(`' . route('pembelian.hapus', $pembelian->pembelian_id) . '`)" class="btn btn-danger btn-sm ml-2">
                        <i class="fas fa-trash"></i>
                    </button>
                    
                    ' . $tombol_retur . '
                </div>
				  ';
            })
            // jika aku membuat sebuah element di dalam colum maka harus dimasukkan ke dalam rawColumns
            // mentah column-column action
            ->rawColumns(['status', 'action'])
            // buat nyata
            ->make(true);
    }

    // untuk mengecek apakah ada penyuplai dan produk_penyuplai, jika tidak ada maka tampilkan notifikasi menggunakan sweetalert yang menyatakan "kamu harus menambahkan minimal 1 penyuplai terlebih dahulu" lalu arahkan ke menu penyuplai
    public function cek_penyuplai_dan_produk_penyuplai()
    {
        // ambil detail_penyuplai_pertama agar jika penyuplai pertama tidak ada maka kasi tau user, bahwa mereka harus menambahkan minimal 1 penyuplai terlebih dahulu
        // berisi ambil detail penyuplai pertama
        $detail_penyuplai_pertama = Penyuplai::first();

        // jika detail_penyuplai_pertama tidak ada atau NULL
        if ($detail_penyuplai_pertama === null) {
            // kembalikkan tanggapan berupa json lalu kirimkan data
            return response()->json([
                // key message berisi value atau pesan berikut
                'message' => 'Anda harus menambahkan minimal 1 penyuplai terlebih dahulu.'
            ]);
        };

        // ambil detail_produk_penyuplai yang pertama
        $detail_produk_penyuplai_pertama = ProdukPenyuplai::first();
        // lain jika detail_produk_penyuplai_pertama tidak ada atau null maka
        if ($detail_produk_penyuplai_pertama === null) {
            // kembalikkan tanggapan berupa json lalu kirimkan data
            return response()->json([
                // key message berisi value atau pesan berikut
                'pesan' => 'Anda harus menambahkan minimal 1 produk penyuplai terlebih dahulu.'
            ]);
        };
    }

    /**
     * Setelah user memilih penyuplai maka simpan satu baris data ke table pembelian
     * $penyuplai_id berisi value column penyuplai_id yang didapatkan dari url anggaplah berisi angka 1
     */
    public function create($penyuplai_id)
    {
        // Simpan Pembelian secara sementara
        // berisi pembelian buat
        $detail_pembelian = Pembelian::create([
            // column penyuplai_id berisi penyuplai_id yang di kirimkan lewat url
            'penyuplai_id' => $penyuplai_id,
            // total_barang diisi 0 secara sementara 
            'total_barang' => 0,
            // total_harga diisi 0 secara sementara
            'total_harga' => 0,
            // column status pada table pembelian menggunakan tipe data enum yang berisi pilihan 'retur', 'oke'
            'status' => 'Oke'
        ]);

        // Membuat Session
        // session berfungsi menyimpan data, jika browser ditutup maka data sessi nya hilang\
        // aku membuat ini agar aku bisa menampilkan detail penyuplai di halaman pembelian detail
        // sessi penyuplai_id berisi penyuplai_id anggaplah 1 yaitu smartfren
        session(['penyuplai_id' => $penyuplai_id]);
        // sessi pembelian_id berisi value dari detail_pembelian, column pembelian_id, anggaplah 1
        session(['pembelian_id' => $detail_pembelian->pembelian_id]);

        // kembalikkan alihkan ke route pembelian_detail.index
        // aku tak bisa mengirim session menggunakan route()->with('penyuplai_id' => $penyuplai_id)
        return redirect()->route('pembelian_detail.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Update pembelian
        // pembelian dimana value column pembelian_id sama dengan value $request->pembelian_id lalu perbarui value column total_barang dan total_harga
        Pembelian::where('pembelian_id', $request->pembelian_id)->update([
            'total_barang' => $request->total_barang,
            'total_harga' => $request->total_harga,
        ]);

        // detail pembelian
        // berisi pembelian dimana value column pembelian_id sama dengan value input name="pembelian_id", ambil data baris pertama
        $detail_pembelian = Pembelian::where('pembelian_id', $request->pembelian_id)->first();

        // beberapa pembelian detail
        // anggaplah berisi dua baris data jadi ambil beberapa pembelian detail berdasarkan value column pembelian_id yang sesuai
        $beberapa_pembelian_detail = PembelianDetail::where('pembelian_id', $detail_pembelian->pembelian_id)->get();

        // update table produk atau buat data di table produk
        // karena aku membeli barang berarti stok nya bertambah
        foreach ($beberapa_pembelian_detail as $pembelian_detail) {
            // detail produk_penyuplai
            // berisi ProdukPenyuplai dimana value column produk_penyuplai_id sama dengan value $pembelian_detail->produk_penyuplai lalu ambil data baris pertama
            $detail_produk_penyuplai = ProdukPenyuplai::where('produk_penyuplai_id', $pembelian_detail->produk_penyuplai_id)->first();

            // ambil detail_produk
            // berisi produk dimana value column nama_produk sama dengan value detail_produk_penyuplai, column nama_produk, data baris pertama
            $detail_produk = Produk::where('nama_produk', $detail_produk_penyuplai->nama_produk)->first();

            // jika tidak ada detail produk
            if (!$detail_produk) {
                // ambil satu baris data produk yang terakhir
                $baris_data_produk_yg_terakhir = produk::latest()->first();
                // jika tidak ada baris data produk yang terakhir karena belum ada produk maka $kode_produk_yg_terakhir diisi 00001
                if (!$baris_data_produk_yg_terakhir) {
                    $kode_produk = '00001';
                }
                // lain jika ada baris data produk
                else if ($baris_data_produk_yg_terakhir) {
                    // anggaplah berisi "P-00001"
                    $kode_produk_yg_terakhir = $baris_data_produk_yg_terakhir->kode_produk;
                    // anggaplah data terakhir berisi "P-00001"
                    // maka saya tidak akan bisa melakukan "P-00001" + 1 karena string + integer = string
                    // aku butuh explode agar bisa memecah menggunakan -

                    // anggaplah berisi ["P", "00001"]
                    $explode_kode_produk = explode("-", $kode_produk_yg_terakhir);
                    // "P-00001" akan menjadi P dan 00001 lalu di tambah 1 = 2
                    // berisi ubah isi $explode_kode_produk index 1 yang berisi "00001" menjadi 00001 lalu di tambah 1 maka akan menjadi 00002
                    $ubah_string_kode_produk_menjadi_integer = (int) $explode_kode_produk[1] + 1;

                    // panggil fungsi helper kode_berurutan
                    // 5 berarti jumlah digit kode_produknya
                    $kode_produk = kode_berurutan($ubah_string_kode_produk_menjadi_integer, 5);
                };
                // Produk buat data baru 
                Produk::create([
                    'kategori_id' => $detail_produk_penyuplai->kategori_id,
                    'penyuplai_id' => $detail_produk_penyuplai->penyuplai_id,
                    'kode_produk' => 'P-' . $kode_produk,
                    'nama_produk' => $detail_produk_penyuplai->nama_produk,
                    'merk' => $detail_produk_penyuplai->merk,
                    'harga_beli' => $detail_produk_penyuplai->harga,
                    'diskon' => 0,
                    'harga_jual' => 0,
                    'stok' => $pembelian_detail->jumlah
                ]);
            }
            // lain jika ada detail_produk
            else if ($detail_produk) {
                // panggil value detail_produk, column stok lalu value nya ditambah value $pembelian_detail->jumlah
                $detail_produk->stok += $pembelian_detail->jumlah;
                // detail_produk, diperbarui
                $detail_produk->update();
            };

            // karena aku membeli barang berarti stok nya bertambah
            // $detail_produk->stok += $pembelian_detail->jumlah;
            // $detail_produk->update();
        };

        // kembali alihkan ke route pembelian.index
        return redirect()->route('pembelian.index');
    }

    // mengambil daftar data penyuplai untuk di tampilkan di modal pilih penyuplai
    public function penyuplai()
    {
        // ambil semua value column penyuplai_id, nama_penyuplai, telepon_penyuplai, alamat_penyuplai
        // penyuplai pilih value dari column penyuplai_id, nama_penyuplai, telepon_penyuplai, alamant_penyuplai, Data yang terbaru akan tampil pertama, dapatkan semua penyuplai
        $semua_penyuplai = Penyuplai::select('penyuplai_id', 'nama_penyuplai', 'telepon_penyuplai', 'alamat_penyuplai')->latest()->get();
        // syntax punya yajra
        // kembalikkan datatable dari semua_penyuplai
        return DataTables::of($semua_penyuplai)
            // untuk pengulangan nomor
            // tambah index column
            ->addIndexColumn()
            // $penyuplai berarti ulang detail penyuplai
            // buat tombol pilih
            // tambah column action, jalankan fungsi, ambil semua detail_penyuplai
            ->addColumn('action', function (Penyuplai $penyuplai) {
                // ke route pembelian.create lalu kirimkan value column penyuplai_id agar aku bisa mengambil semua produk yang dijual oleh suatu penyuplai atau mengambil beberapa produk_penyuplai berdasarkan column foreign key penyuplai_id
                // anggaplah penyuplai nya adalah PT Smartfren maka ambil semua produk yang di jual PT Smartfren
                return '
                <a href="/pembelian/create/' . $penyuplai->penyuplai_id . '" class="btn btn-primary btn-sm"><i class="fa fa-truck"></i> Pilih</a>
            ';
            })
            // jika column, membuat elemnt html maka harus dimasukkan ke rawColumns
            ->rawColumns(['action'])
            // buat nyata
            ->make(true);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($pembelian_id)
    {
        // model PembelianDetail berelasi dengan model produk jadi 1 pembelian detail hanya bisa membeli 1 produk penyuplai
        // table PembelianDetail dengan table produk_penyuplai dimana value column pembelian_id sama dengan $pembelian_id, dapatkan semua data terkait
        $beberapa_pembelian_detail = PembelianDetail::with('produk_penyuplai')->where('pembelian_id', $pembelian_id)->get();

        // aku melakukan pengulangan disini
        // kembalikkan datatables dari beberapa_pembelian_detail
        return datatables()
            ->of($beberapa_pembelian_detail)
            // lakukan pengulagan terhadap nomor
            ->addIndexColumn()
            ->addColumn('nama_produk', function (PembelianDetail $pembelian_detail) {
                return $pembelian_detail->produk_penyuplai->nama_produk;
            })
            ->addColumn('harga', function (PembelianDetail $pembelian_detail) {
                return rupiah_bentuk($pembelian_detail->harga);
            })
            ->addColumn('jumlah', function (PembelianDetail $pembelian_detail) {
                return angka_bentuk($pembelian_detail->jumlah);
            })
            ->addColumn('subtotal', function (PembelianDetail $pembelian_detail) {
                return rupiah_bentuk($pembelian_detail->subtotal);
            })
            // mentah column-column
            ->rawColumns(['kode_produk'])
            // buat nyata
            ->make(true);
    }

    /**
     * hapus pembelian berdasarkan pembelian_id yang dikirim
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function hapus($pembelian_id)
    {
        // ambil detail pembelian
        // pembelian dimana value column pembelian_id sama dengan value paramter $pembelian_id, baris data pertama
        $detail_pembelian = Pembelian::where('pembelian_id', $pembelian_id)->first();
        // detail pembelian di hapus
        $detail_pembelian->delete();

        // 
        return response()->json('Berhasil menghapus 1 baris di table pembelian dan beberapa baris data di table pembelian_detail');
    }

    public function kembali($pembelian_id)
    {
        // ambil detail pembelian
        // pembelian dimana value column pembelian_id sama dengan value paramter $pembelian_id, baris data pertama
        $detail_pembelian = Pembelian::where('pembelian_id', $pembelian_id)->first();

        // jika value detail_pembelian, column total_harga sama dengan - maka
        if ($detail_pembelian->total_harga === 0) {
            // detail_pembelian dihapus
            $detail_pembelian->delete();
        };

        // kembalikan lalu alihkan ke route pembelian.index
        return redirect()->route('pembelian.index');
    }

    // method retur_pembelian agar aku bisa retur pembelian atau mengembalikkan pembelian
    public function retur_pembelian(Request $request)
    {
        // kembalikkan tanggapan berupa json yang berisi semua value dari semua permintaan
        // return response()->json($request->all());

        // ambil detail pembelian
        // pembelian dimana value column pembelian_id sama dengan value paramter $pembelian_id, baris data pertama
        $detail_pembelian = Pembelian::where('pembelian_id', $request->pembelian_id)->first();
        // panggil detail_pembelian, value column status, ditimpa dengan "Retur"
        $detail_pembelian->status = "Retur";
        // detail_pembelian di perbarui
        $detail_pembelian->update();

        // buat data ke tabel ReturPembelian
        // ReturPembelian::buat
        ReturPembelian::create([
            // column pembelian_id diisi dengan value input name="pembelian_id"
            'pembelian_id' => $request->pembelian_id,
            'tanggal_retur' => now(),
            'keterangan' => $request->keterangan
        ]);

        // misalnya $request->pembelian_id berisi angka 1 maka ambil semua PembelianDetail yang column pembelian_id berisi 1
        $semua_pembelian_detail_terkait = PembelianDetail::where('pembelian_id', $request->pembelian_id)->get();

        // lakukan pengulangan terhadap semua_pembelian_detail_terkait
        foreach ($semua_pembelian_detail_terkait as $pembelian_detail) {
            // berisi ambil setiap data table ProdukPenyuplai dimana value column produk_penyuplai_id sama dengan value $pembelian_detail->produk_penyuplai_id
            $setiap_produk_penyuplai = ProdukPenyuplai::where('produk_penyuplai_id', $pembelian_detail->produk_penyuplai_id)->first();
            // produk dimana value column nama_produk sama dengan value $setiap_produk_penyuplai->nama_produk, pertama
            $setiap_produk = Produk::where('nama_produk', $setiap_produk_penyuplai->nama_produk)->first();
            $setiap_produk->stok -= $pembelian_detail->jumlah;
            $setiap_produk->update();
        };

        // kembalikkan tanggapan berupa json lalu kirimkan data berupa object
        return response()->json([
            // key status berisi value 200
            'status' => 200,
            'message' => 'Berhasil retur pembelian'
        ]);
    }
}
