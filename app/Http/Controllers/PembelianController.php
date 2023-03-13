<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\Penyuplai;
use App\Models\PembelianDetail;
use App\Models\Produk;
use App\Models\ProdukPenyuplai;
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
        // ambil semua penyuplai
        // berisi penyuplai di pesan oleh column nama_penyuplai, data nya dari A ke Z, lalu dapatkan semua data nya
        $semua_penyuplai = Penyuplai::orderBy('nama_penyuplai', 'asc')->get();
        // kembalikkan ke tampilan pembelian.index, kirimkan semua_penyuplai
        return view('pembelian.index', [
            'semua_penyuplai' => $semua_penyuplai
        ]);
    }

    public function data()
    {
        // tampilkan semua pembelian dimulai dari yang paling baru
        $pembelian = Pembelian::orderBy('updated_at', 'desc')->get();
        // syntax punya yajra
        // disini aku melakukan pengulangan
        return DataTables::of($pembelian)
            // nomor
            ->addIndexColumn()
            ->addColumn('tanggal', function ($pembelian) {
                // contoh tanggal nya adalah: Selasa, 7 Februari 2023
                return $pembelian->updated_at->isoFormat('dddd, D MMMM Y');
            })
            ->addColumn('penyuplai', function ($pembelian) {
                // panggil nama penyuplai yang berelesi dengan table pembelian
                return $pembelian->penyuplai->nama;
            })
            // ulang detail pembelian
            ->addColumn('total_item', function ($pembelian) {
                return angka_bentuk($pembelian->total_item);
            })
            ->addColumn('total_harga', function ($pembelian) {
                return rupiah_bentuk($pembelian->total_harga);
            })
            ->addColumn('diskon', function ($pembelian) {
                return $pembelian->diskon . "%";
            })
            ->addColumn('action', function ($pembelian) {
                $btn = '
                <div class="btn-group">
                    <button onclick="showDetail(`' . route('pembelian.show', $pembelian->pembelian_id) . '`)" class="btn btn-info btn-sm">
                    <i class="fas fa-eye"></i>
                    </button>
                    <button onclick="deleteData(`' . route('pembelian.destroy', $pembelian->pembelian_id) . '`)" class="btn btn-danger btn-sm ml-2">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
				  ';
                return $btn;
            })
            ->rawColumns(['action'])
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
     * $penyuplai_id berisi value column penyuplai_id yang didapatkan dari url
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
            'total_harga' => 0
        ]);

        // Membuat Session
        // session berfungsi menyimpan data, jika browser ditutup maka data sessi nya hilang\
        // aku membuat ini agar aku bisa menampilkan detail penyuplai di halaman pembelian detail
        // sessi penyuplai_id berisi penyuplai_id anggaplah 1 yaitu smartfren
        session(['penyuplai_id' => $penyuplai_id]);
        // sessi pembelian_id berisi value dari detail_pembelian, column pembelian_id, anggaplah 1
        session(['pembelian_id' => $detail_pembelian->pembelian_id]);

        // kembalikkan alihkan ke route pembelian_detal.index
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
        // Update
        Pembelian::where('pembelian_id', $request->pembelian_id)->update([
            'total_item' => $request->total_item,
            'total_harga' => $request->total_harga,
            'diskon' => $request->diskon,
        ]);

        // detail pembelian
        $detail_pembelian = Pembelian::find($request->pembelian_id);

        // beberapa pembelian detail
        // anggaplah berisi dua baris data
        $beberapa_pembelian_detail = PembelianDetail::where('pembelian_id', $detail_pembelian->pembelian_id)->get();

        // update table produk
        // karena aku membeli barang berarti stok nya bertambah
        foreach ($beberapa_pembelian_detail as $pembelian_detail) {
            $detail_produk = Produk::find($pembelian_detail->id_produk);
            // karena aku membeli barang berarti stok nya bertambah
            $detail_produk->stok += $pembelian_detail->jumlah;
            $detail_produk->update();
        };

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
        ->addColumn('action', function(Penyuplai $penyuplai) {
            // ke route pembelian.create lalu kirimkan value column penyuplai_id agar aku bisa mengambil semua produk yang dijual oleh suatu penyuplai atau mengambil beberapa produk_penyuplai berdasarkan column foreign key penyuplai_id
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
        // model PembelianDetail berelasi dengan model produk
        // 1 pembelian detail hanya bisa membeli 1 produk
        $beberapa_pembelian_detail = PembelianDetail::with('produk')->where('pembelian_id', $pembelian_id)->get();

        // aku melakukan pengulangan disini
        return datatables()
            ->of($beberapa_pembelian_detail)
            // nomor
            ->addIndexColumn()
            ->addColumn('kode_produk', function (PembelianDetail $pembelian_detail) {
                return '<span class="badge badge-success">' .  $pembelian_detail->produk->kode_produk . '</span>';
            })
            ->addColumn('nama_produk', function (PembelianDetail $pembelian_detail) {
                return $pembelian_detail->produk->nama_produk;
            })
            ->addColumn('harga_beli', function (PembelianDetail $pembelian_detail) {
                return rupiah_bentuk($pembelian_detail->harga_beli);
            })
            ->addColumn('jumlah', function (PembelianDetail $pembelian_detail) {
                return angka_bentuk($pembelian_detail->jumlah);
            })
            ->addColumn('subtotal', function (PembelianDetail $pembelian_detail) {
                return rupiah_bentuk($pembelian_detail->subtotal);
            })
            ->rawColumns(['kode_produk'])
            ->make(true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($pembelian_id) 
	{
		$pembelian = Pembelian::find($pembelian_id);
		$beberapa_pembelian_detail = PembelianDetail::where('pembelian_id', $pembelian->pembelian_id)->get();
		foreach($beberapa_pembelian_detail as $pembelian_detail) {
			$produk = Produk::find($pembelian_detail->id_produk);
			if ($produk) {
				$produk->stok -= $pembelian_detail->jumlah;
				$produk->update();
			};
			$pembelian_detail->delete();
		};
		$pembelian->delete();

		return response()->json('Berhasil menghapus 1 baris di table pembelian dan beberapa baris data di table pembelian_detail');
	 }
}
