<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Pembelian;
use App\Models\Penyuplai;
use App\Models\PembelianDetail;
use App\Models\Produk;
use App\Models\ReturPembelian;
// carbon digunakan di fitur retur, aku dapat dari internet
use Carbon\Carbon;
// gunakan package datatable
use DataTables;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    /**
     * Ke tampilan pembelian.index
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // hapus beberapa baris dari table pembelian yang column total_barang nya sama dengan 0
        // pembelian dimana value column total_barang sama dengan 0 maka hapus
        Pembelian::where('total_barang', 0)->delete();

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
        // display all purchasse, select the value from the purchase_id, etc column, sort the data starting from the most recent
        // tampilkan semua pembelian, pilih value dari column pembelian_id dan lain-lain, urutkan data dimulai dari yang paling baru
        // contains purchases ordered by column updated-at, descending, get all data
        // berisi pembelian dipesan oleh colum updated_at, menurun, dapatkan semua data
        $semua_pembelian = Pembelian::orderBy('updated_at', 'desc')->get();
        // Yajra's syntax, here I repeat
        // syntax punya yajra, disini aku melakukan pengulangan
        // return datatables off all_purchases
        // kembalikkan datatables dari semua_pembelian
        return DataTables::of($semua_pembelian)
            // number repetition
            // pengulangan nomor
            ->addIndexColumn()
            // add date column, run the following function, purchase parameters contains each purchase details
            // tambahColumn tanggal, jalankan fungsi berikut, parameter pembelian berisi setiap pembelian detail
            ->addColumn('tanggal', function ($pembelian) {
                // // an example date is: tuesday, february, 7 2023
                // // contoh tanggal nya adalah: Selasa, 7 Februari 2023
                // return $pembelian->tanggal_dan_waktu->isoFormat('dddd, D MMMM Y');
                // create a CARBON object from the date you want to change
                // buat objek CARBON dari tanggal yang ingin diubah
                // karbom, createFromFormat, the first argument is time format, the second argument is the time you want to change, the third argument is time zone you want to use.
                // karbon, buatDariBentuk, argument pertama adalah format waktu, argument kedua adalah waktu yang ingin diubah, argument ketiga adalah zona waktu yang ingin dipakai
                $tanggal = Carbon::createFromFormat('Y-m-d H:i:s', $pembelian->tanggal_dan_waktu, 'Asia/Jakarta');
                // to change the format date and use indonesian
                // untuk mengubah format tanggal dan menggunakan bahasa indonesia
                // The first argument in the Translation format is the name of the day in the string, date, month and year, the second argument is to use Indonesian
                // argument pertama pada formatTerjemahan adalah nama hari dalam string, tanggal bulan dan tahun, argument kedua adalah menggunakan bahasa indonesia
                return $tanggal->translatedFormat('l, d F Y', 'id');
            })
            ->addColumn('total_barang', function ($pembelian) {
                // call the helpers form number function to change 1000 to 1.000, then pass $purchase->item_total as argument
                // panggil fungsi angka_bentuk milik helpers agar mengubah 1000 menjadi 1.000, lalu kirimkan $pembelian->total_barang sebagai argumen
                return angka_bentuk($pembelian->total_barang);
            })
            ->addColumn('total_harga', function ($pembelian) {
                return rupiah_bentuk($pembelian->total_harga);
            })
            // create buttons to view purchase details, delete and return purchases
            // Buat tombol lihat pembelian detail, hapus dan retur pembelian
            ->addColumn('action', function ($pembelian) {
                // Check whether two days have passed since the purchase time. If two days have passed after the buyer received the goods then I will not be able to return the goods purchased.
                // cek apakah waktu pembelian sudah lewat dua hari, Jika sudah lewat dua hari setelah barang di terima pembeli maka aku tidak akan bisa retur barang yang di beli nya
                // The Carbon::parse() function is a function provided by the Carbon library in Laravel, which is used to convert a date or time string into a Carbon object.
                // Fungsi Carbon::parse() adalah fungsi yang disediakan oleh library Carbon di Laravel, yang digunakan untuk mengubah string tanggal atau waktu menjadi objek Carbon. 
                // karbon::uraikan($pembelian->dibuat_pada)
                $tanggal_pembelian = Carbon::parse($pembelian->created_at);
                // jika sudah lewat dua hari setelah tanggal_pembelian atau di buat maka aku akan berikan attribute disabled atau matikan tombol nya
                if ($tanggal_pembelian->diffInDays(now()) > 2) {
                    $retur_pembelian = '<button data-toggle="keterangan_alat" data-placement="top" title="Retur Pembelian" class="btn btn-danger btn-sm" disabled>
                    <i class="mdi mdi-credit-card-refund"></i>
                </button>';
                }
                // jika belum lewat dari dua hari setelah tanggal_pembelian di buat maka cek apakah pembelian belum pernah di retur, jika belum pernah di retur maka aku boleh melakukan retur, jika sudah pernah retur maka pelanggan tidak boleh retur laig
                else {
                    $retur_pembelian = '<button data-toggle="keterangan_alat" data-placement="top" title="Retur Pembelian" onclick="data_retur(`' . route('pembelian.data_retur', $pembelian->pembelian_id) . '`, `' . $pembelian->pembelian_id . '`)" class="btn btn-danger btn-sm">
                    <i class="mdi mdi-credit-card-refund"></i>
                </button>';
                };

                // fitur lihat semua pembelian_detail yang terkait
                return '
                <div class="btn-group">
                    <button data-toggle="keterangan_alat" data-placement="top" title="Lihat semua pembelian detailnya" onclick="tampilkan_semua_pembelian_detail_terkait(`' . route('pembelian.tampilkan_semua_pembelian_detail_terkait', $pembelian->pembelian_id) . '`)" class="btn btn-info btn-sm">
                    <i class="fas fa-eye"></i>
                    </button>

                    <button data-toggle="keterangan_alat" data-placement="top" title="Hapus" onclick="hapus_data(`' . route('pembelian.hapus', $pembelian->pembelian_id) . '`)" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                    </button>
                    
                    ' . $retur_pembelian . '
                </div>
				  ';
            })
            // jika aku membuat sebuah element di dalam colum maka harus dimasukkan ke dalam rawColumns
            // mentah column-column action
            ->rawColumns(['tanggal', 'total_barang', 'total_harga', 'action'])
            // buat nyata
            ->make(true);
    }

    // untuk mengecek apakah ada penyuplai dan produk, jika tidak ada maka tampilkan notifikasi menggunakan sweetalert yang menyatakan "kamu harus menambahkan minimal 1 penyuplai terlebih dahulu" lalu arahkan ke menu penyuplai
    public function cek_penyuplai_dan_produk()
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

        // ambil detail_produk yang pertama
        $detail_produk_pertama = Produk::first();
        // lain jika detail_produk_pertama tidak ada atau null maka
        if ($detail_produk_pertama === null) {
            // kembalikkan tanggapan berupa json lalu kirimkan data
            return response()->json([
                // key message berisi value atau pesan berikut
                'pesan' => 'Anda harus menambahkan minimal 1 produk terlebih dahulu.'
            ]);
        };
    }

    /**
     * Setelah user memilih penyuplai maka simpan satu baris data ke table pembelian
     * $penyuplai_id berisi value column penyuplai_id yang didapatkan dari url anggaplah berisi angka 1
     */
    // public function create($penyuplai_id)
    // {
    //     // Simpan Pembelian secara sementara
    //     // berisi pembelian buat
    //     $detail_pembelian = Pembelian::create([
    //         // column penyuplai_id berisi penyuplai_id yang di kirimkan lewat url
    //         'penyuplai_id' => $penyuplai_id,
    //         // total_barang diisi 0 secara sementara 
    //         'total_barang' => 0,
    //         // total_harga diisi 0 secara sementara
    //         'total_harga' => 0
    //     ]);

    //     // Membuat Session
    //     // session berfungsi menyimpan data, jika browser ditutup maka data sessi nya hilang\
    //     // aku membuat ini agar aku bisa menampilkan detail penyuplai di halaman pembelian detail
    //     // sessi penyuplai_id berisi penyuplai_id anggaplah 1 yaitu smartfren
    //     session(['penyuplai_id' => $penyuplai_id]);
    //     // sessi pembelian_id berisi value dari detail_pembelian, column pembelian_id, anggaplah 1
    //     session(['pembelian_id' => $detail_pembelian->pembelian_id]);

    //     // kembalikkan alihkan ke route pembelian_detail.index
    //     // aku tak bisa mengirim session menggunakan route()->with('penyuplai_id' => $penyuplai_id)
    //     return redirect()->route('pembelian_detail.index');
    // }

    public function create()
    {
        // return to purchase_detail.index view
        // kembalikkan ke tampilan pembelian_detail.index
        return view('pembelian_detail.index');         
    }



    /**
     * Perbarui column total_barang dan total_harga di table pembelian ketika tombol Simpan Pembelian di click
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

        // update table produk 
        // karena aku membeli barang berarti stok nya bertambah
        foreach ($beberapa_pembelian_detail as $pembelian_detail) {
            // ambil detail_produk
            // berisi produk dimana value column nama_produk sama dengan value detail_produk_penyuplai, column nama_produk, data baris pertama
            $detail_produk = Produk::where('produk_id', $pembelian_detail->produk_id)->first();

            // panggil value detail_produk, column stok lalu value nya ditambah value $pembelian_detail->jumlah
            $detail_produk->stok += $pembelian_detail->jumlah;
            // detail_produk, diperbarui
            $detail_produk->update();
        };

        // kembali alihkan ke route pembelian.index
        return redirect()->route('pembelian.index');
    }

    // $request menangkap pembelian_id yang dikirimkan url, anggaplah berisi angka 1
    public function nota_kecil($pembelian_id) {
        // ambil detail_pembelian berdasaran pembelian_id
        // berisi model pembelian dimana value column pembelian_id sama dengan value parameter $pembelian_id, ambil data baris pertama
        $detail_pembelian = Pembelian::where('pembelian_id', $pembelian_id)->first();
        // ambil beberapa data table pembelian_detail berdasarkan column pembelian_id
        // berisi model pembelianDetail dimana value column pembelian_id sama dengan value variable $pembelian_id, ambil beberapa baris data
        $semua_pembelian_detail = PembelianDetail::where('pembelian_id', $pembelian_id)->get();

        // kembalikkan ke tampilan pembelian.nota_kecil, kirimkan value variable $detail_pembelian
        return view('pembelian.nota_kecil', [
            // key detail_pembelian berisi value variable $detail_pembelian
            'detail_pembelian' => $detail_pembelian,
            // key semua_pembelian_detail berisi value variable $semua_pembelian_detail
            'semua_pembelian_detail' => $semua_pembelian_detail,
            // key nama_perusahaan berisi panggil database table pengaturan, ambil data baris pertama lalu ambil value column nama_perusahaan
            'nama_perusahaan' => DB::table('pengaturan')->first()->nama_perusahaan,
            'alamat_perusahaan' => DB::table('pengaturan')->first()->alamat_perusahaan,
        ]);
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
     * Jika aku click tombol lihat semua pembelian detail terkait maka tampilkan 
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function tampilkan_semua_pembelian_detail_terkait($pembelian_id)
    {
        // model PembelianDetail berelasi dengan model produk jadi 1 pembelian detail hanya bisa membeli 1 produk
        // table PembelianDetail yang berelasi dengan table produk dimana value column pembelian_id sama dengan $pembelian_id, dapatkan semua data terkait
        $beberapa_pembelian_detail = PembelianDetail::with('produk')->where('pembelian_id', $pembelian_id)->get();

        // aku melakukan pengulangan disini
        // kembalikkan datatables dari beberapa_pembelian_detail
        return datatables()
            ->of($beberapa_pembelian_detail)
            // lakukan pengulagan terhadap nomor
            ->addIndexColumn()
            ->addColumn('nama_produk', function (PembelianDetail $pembelian_detail) {
                // kembalikan detail table pembelian_detail yang berelasi dengan detail table produk, lalu ambil value column nama_produk
                return $pembelian_detail->produk->nama_produk;
            })
            ->addColumn('kode_produk', function(PembelianDetail $pembelian_detail) {
                return $pembelian_detail->produk->kode_produk;
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


    // method retur_pembelian agar aku bisa retur pembelian atau mengembalikkan pembelian
    // anggaplah parameter $pembelian_id berisi angka 1
    public function data_retur($pembelian_id)
    {
        // model PembelianDetail berelasi dengan model produk jadi 1 pembelian detail hanya bisa membeli 1 produk
        // table PembelianDetail yang berelasi dengan table produk dimana value column pembelian_id sama dengan $pembelian_id, dapatkan semua data terkait
        $beberapa_pembelian_detail = PembelianDetail::with('produk')->where('pembelian_id', $pembelian_id)->get();

        // aku melakukan pengulangan disini
        // kembalikkan datatables dari beberapa_pembelian_detail
        return datatables()->of($beberapa_pembelian_detail)
            // lakukan pengulagan terhadap nomor
            ->addIndexColumn()
            ->addColumn('nama_produk', function (PembelianDetail $pembelian_detail) {
                // kembalikan detail table pembelian_detail yang berelasi dengan detail table produk, lalu ambil value column nama_produk
                return $pembelian_detail->produk->nama_produk;
            })
            // penjelasan untuk attribute max, jadi jumlah retur tidak boleh melebihi jumlah pembelian, jadi anggaplah jumlah pembelian detail nya adalah 5 masa retur pembelian nya 10
            ->addColumn('jumlah', function(PembelianDetail $pembelian_detail) {
                // jika value $pembelian_detail, column retur_pembelian_id sama dengan NULL maka pembelian nya bisa di retur dengan cara aku menghapus attribute disabled
                if ($pembelian_detail->retur_pembelian_id === NULL) {
                    // anggaplah berisi .jumlah_retur_1, .jumlah_retur_1, dst.
                    // untuk menampilkan efek input error, element input butuh .is-invalid
                    return "
                    <input name='jumlah_retur' type='number' class='jumlah_retur_$pembelian_detail->produk_id form-control input_$pembelian_detail->produk_id' value='$pembelian_detail->jumlah' max='$pembelian_detail->jumlah' min='1' autocomplete='off'>

                    <span class='jumlah_retur_error_$pembelian_detail->produk_id pesan_error_$pembelian_detail->produk_id text-danger'></span>
                    ";
                } else if ($pembelian_detail->retur_pembelian_id !== NULL) {
                    // anggaplah berisi .jumlah_retur_1, .jumlah_retur_1, dst.
                    // untuk menampilkan efek input error, element input butuh .is-invalid
                    return "
                    <input name='jumlah_retur' type='number' class='jumlah_retur_$pembelian_detail->produk_id form-control input_$pembelian_detail->produk_id' value='$pembelian_detail->jumlah' max='$pembelian_detail->jumlah' min='1' autocomplete='off' disabled>
                    ";
                };
            })
            ->addColumn('keterangan', function (PembelianDetail $pembelian_detail) {
                if ($pembelian_detail->retur_pembelian_id === NULL) {
                    return "<input name='keterangan' type='text' class='keterangan_$pembelian_detail->produk_id input_$pembelian_detail->produk_id form-control' autocomplete='off' autocomplete='off'>
                    <span class='keterangan_error_$pembelian_detail->produk_id pesan_error_$pembelian_detail->produk_id text-danger'></span>";
                } else if ($pembelian_detail->retur_pembelian_id !== NULL) {
                    return "<input name='keterangan' type='text' class='keterangan_$pembelian_detail->produk_id input_$pembelian_detail->produk_id form-control' autocomplete='off' autocomplete='off' disabled>";
                };
            })
            // tombol
            ->addColumn('action', function(PembelianDetail $pembelian_detail) {
                // jika value pembelian_detail, column retur_pembelian_id sama dengan NULL atau kosong maka
                if ($pembelian_detail->retur_pembelian_id === NULL) {
                    // jadi nanti ada id="tombol_retur_1", id="tombol_retur_2", dst.
                    return "
                    <button id='tombol_retur_$pembelian_detail->produk_id' data-toggle='keterangan_alat' data-placement='top' title='Retur Pembelian' onclick='retur_pembelian($pembelian_detail->pembelian_detail_id, $pembelian_detail->produk_id, $pembelian_detail->pembelian_id)' class='btn btn-danger btn-sm ml-2' type='button'>
                    <i class='mdi mdi-credit-card-refund'></i>
                    </button>";
                }
                // lain jika value $pembelian-detail, column retur_pembelian_id tidak sama dengan kosong
                else if ($pembelian_detail->retur_pembelian_id !== NULL) {
                    // jadi nanti ada id="tombol_retur_1", id="tombol_retur_2", dst.
                    return "
                    <button id='tombol_retur_$pembelian_detail->produk_id' data-toggle='keterangan_alat' data-placement='top' title='Retur Pembelian' onclick='retur_pembelian($pembelian_detail->pembelian_detail_id, $pembelian_detail->produk_id, $pembelian_detail->pembelian_id)' class='btn btn-danger btn-sm ml-2' type='button' disabled>
                    <i class='mdi mdi-credit-card-refund'></i>
                    </button>";
                };
                
            })
            // jika aku membuat element html di addColumn maka aku wajib memasukkan ke dalam rawColumns([])
            // mentah column-column
            ->rawColumns(['jumlah', 'keterangan', 'action'])
            // buat nyata
            ->make(true);
    }

    // $request akan menangkap semua data yang dikirim oleh property data milik script
    public function retur_pembelian(Request $request)
    {
        // ambil detail pembelian_detail
        // berisi PembelianDetail dimana value column pembelian_detail_id sama dengan valu $request->pembelian_detail_id yang dikirim key data milik script, ambil rekaman baris pertama
        $detail_pembelian_detail = PembelianDetail::where('pembelian_detail_id', $request->pembelian_detail_id)->first();
        // validasi semua input yang punya attribute name
        // berisi vaidator buat semua permintaan
        $validator = Validator::make($request->all(), [
            // input name="jumlah_retur" harus mengikuti aturan berikut
            'jumlah_retur' => "required|min:1|max:$detail_pembelian_detail->jumlah",
            'keterangan' => 'required|max:255',
        ]);

        // jika validator gagal maka
        if ($validator->fails()) {
            // kembalikkan tanggapan berupa json
            return response()->json([
                // key status berisi value 0
                'status' => 0,
                // key pesan beisi value error
                'pesan' => 'Validasi Input Error Menemukan Error.',
                // key errros berisi semua value attribute name yang error dan semua pesan error nya
                // key errors berisi validaror, kesalahan-kesalahan
                'errors' => $validator->errors()
            ]);
        };

        // tangkap data yang dikirim oleh property data milik script, itu data formulir atau input
        $pembelian_id = $request->pembelian_id;
        $produk_id = $request->produk_id;
        $pembelian_detail_id = $request->pembelian_detail_id;
        // intval() akan mengubah string menjadi integer, contohnya "100" menjadi 100
        $jumlah_retur = intval($request->jumlah_retur);
        $keterangan = $request->keterangan;

        // insert atau sisipkan rekaman ke dalam table ReturPembelian
        $detail_retur_pembelian = ReturPembelian::create([
            // column pembelian_id diisi value variable $pembelian_id
            'pembelian_id' => $pembelian_id,
            'produk_id' => $produk_id,
            'jumlah_retur' => $jumlah_retur,
            // key tanggal_retur diisi tanggal dan waktu sekaang
            'tanggal_retur' => now(),
            'keterangan' => $keterangan
        ]);
        
        // value $detail_pembelian_detail, colum retur_pembelian_id diisi value $detail_retur_pembelian, column retur_pembelian_id
        $detail_pembelian_detail->retur_pembelian_id = $detail_retur_pembelian->retur_pembelian_id;
        // berisi value $detail_pembelian_detail, column jumlah dikurangi value $jumlah_retur
        $detail_pembelian_detail->jumlah = $detail_pembelian_detail->jumlah - $jumlah_retur;
        // detail_pembelian_detail diperbarui
        $detail_pembelian_detail->update();

        // ambil detail produk
        // berisi produk dimana value column produk_id sama dengan value dari $detail_retur_pembelian, column produk_id, ambil rekaman baris pertama
        $detail_produk = Produk::where('produk_id', $detail_retur_pembelian->produk_id)->first();

        // berisi panggil detail_produk, column stok dikurangi value variable $jumlah_retur
        $pengurangan_stok = $detail_produk->stok - $jumlah_retur;

        // update stok produk
        Produk::where('produk_id', $detail_retur_pembelian->produk_id)
        ->update(['stok' => $pengurangan_stok]);

        // kembalikkan tanggapan lalu kirimkan data berupa array
        return response()->json([
            // kirimkan key status yang berisi value 200
            'status' => 200,
            'message' => 'Berhasil melakukan retur pembelian'
        ]);
    }
}
