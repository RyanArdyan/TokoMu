<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Pengaturan;
use App\Models\ReturPenjualan;
use App\Models\Member;
use Illuminate\Support\Facades\Validator;
// carbon digunakan di fitur retur, aku dapat dari internet
use Carbon\Carbon;
// package barryvdh/laravel-dompdf untuk mencetak pdf
use Barryvdh\DomPDF\Facade\Pdf;
// fitur export ke excel
use App\Exports\PenjualanExport;
use App\Exports\PenjualanDetailExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    // menampilkan halaman penjualan.index
    public function index()
    {
        // hapus beberapa baris dari table penjualan yang column total_barang nya sama dengan 0
        // penjualan dimana value column total_barang sama dengan 0 maka hapus
        Penjualan::where('total_barang', 0)->delete();
        
        // kembalikkan ke tampilan penjualan.index
        return view('penjualan.index');
    }

    // menampilkan data table penjualan ke dalam table 
    public function data()
    {
        // setiap penjualan bisa memilih member untuk mendapatkan diskon dari table pengaturan
        // berisi model penjualan berelasi dengan model member, urutkan data berdasarkan column updated_at dari z ke a, dapatkan beberapa datanya
        $semua_penjualan = Penjualan::with('member')->orderBy('penjualan_id', 'desc')->get();

        // kembalikkkan datatables 
        return datatables()
            // dari $semua_penjualan
            ->of($semua_penjualan)
            // mulai lakukan pengulangan
            // lakukan pengulangan nomor
            // tambahIndexColumn
            ->addIndexColumn()
            // lakukan pengulangan terhadap value column total_barang
            // tambahColumn('total_barang', jalankan fungsi berikut, parameter $penjualan berisi pengulangan detail penjualan
            ->addColumn('total_barang', function ($penjualan) {
                // kembalikkan panggil fungsi angka_bentuk milik helpers lalu kirimkan $penjualan->total_barang
                return angka_bentuk($penjualan->total_barang);
            })
            ->addColumn('total_harga', function ($penjualan) {
                return rupiah_bentuk($penjualan->total_harga);
            })
            ->addColumn('harus_bayar', function ($penjualan) {
                return rupiah_bentuk($penjualan->harus_bayar);
            })
            ->addColumn('tanggal', function ($penjualan) {
                // kembalikkan panggil fungsi tanggal_indonesia, lalu kirimkan dua argument berikut
                return tanggal_indonesia($penjualan->created_at, false);
            })
            ->addColumn('kode_member', function ($penjualan) {
                // berisi value detail_penjualan, column member_id, jika null berarti dia adalah pelanggan umum atau bukan member
                $member_id = $penjualan->member_id;


                // jika produk di jual bukan ke member maka pasti nya nilai pada table penjualan, column member_id berisi NULL
                // jika member_id sama dengan null atau tidak ada
                if ($member_id === null) {
                    return "<span class='badge badge-warning'>Pelanggan Umum</span>";
                };

                // jika produk di jual ke member maka pasti nya ada nilai di table penjualan, column member_id lalu aku ambil value nya,
                // jika ada member_id
                if ($member_id) {
                    // panggil value column kode_member milik detail table member yang berelasi dengan table penjualan
                    return "<span class='badge badge-success'>" . $penjualan->member->kode_member . "</span>";
                };
            })
            ->addColumn('diskon', function ($penjualan) {
                return $penjualan->diskon . '%';
            })
            ->addColumn('kasir', function ($penjualan) {
                return $penjualan->user->name;
            })
            // tombol-tombol
            ->addColumn('aksi', function ($penjualan) {
                // untuk detail penjualan, logikanya seperti ini, jika table penjualan, column penjualan_idnya 1 maka ambil beberapa baris data dari table penjualan_detail yang column penjualan_idnya berisi 1, anggaplah ada 3 baris data terkait
                // kirimkan penjualan_id lewat url

                // cek apakah waktu penjualan sudah lewat dua hari, Jika sudah lewat dua hari setelah barang di terima pembeli maka pembeli tidak akan bisa retur barang yang di beli nya
                // Fungsi Carbon::parse() adalah fungsi yang disediakan oleh library Carbon di Laravel, yang digunakan untuk mengubah string tanggal atau waktu menjadi objek Carbon. 
                // karbon::uraikan($penjualan->dibuat_pada)
                $tanggal_penjualan = Carbon::parse($penjualan->created_at);
                // jika sudah lewat dua hari setelah tanggal_penjualan atau di buat maka aku akan berikan attribute disabled atau matikan tombol nya
                if ($tanggal_penjualan->diffInDays(now()) > 2) {
                    $retur_penjualan = '<button data-toggle="keterangan_alat" data-placement="top" title="Retur Penjualan" class="btn btn-danger btn-sm" disabled>
                    <i class="mdi mdi-credit-card-refund"></i>
                </button>';
                } 
                // jika belum lewat dari dua hari setelah tanggal_penjualan di buat maka cek apakah penjualan belum pernah di retur, jika belum pernah di retur maka pelanggan boleh melakukan retur, jika sudah pernah retur maka pelanggan tidak boleh retur laig
                // ketika di click maka panggil fungsi data_retur
                else {
                    $retur_penjualan = '<button data-toggle="keterangan_alat" data-placement="top" title="Retur Penjualan" onclick="data_retur(`' . route('penjualan.data_retur', $penjualan->penjualan_id) . '`, `' . $penjualan->penjualan_id . '`)" class="btn btn-danger btn-sm">
                    <i class="mdi mdi-credit-card-refund"></i>';
                };

                // kembalikkan tombol-tombol
                return '
                <div class="btn-group">
                    <button data-toggle="keterangan_alat" data-placement="top" title="Detail Penjualan" onclick="tampilkan_detail_penjualan(`'. route('penjualan.show', $penjualan->penjualan_id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>

                    <button data-toggle="keterangan_alat" data-placement="top" title="Hapus Penjualan" onclick="hapus(`'. route('penjualan.destroy', $penjualan->penjualan_id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>

                    <a href="/penjualan/penjualan-detail/export-excel/'. $penjualan->penjualan_id . '" class="btn btn-sm btn-success">
                    <i class="mdi mdi-file-excel"></i></a>

                    ' . $retur_penjualan . '
                </div>
                ';
            })
            ->rawColumns(['aksi', 'kode_member'])
            ->make(true);
    }

    // menampilkan beberapa penjualan_detail setelah aku click tombol detail penjualan
    // anggaplah parameter $penjualan_id berisi 1
    public function show($penjualan_id)
    {
        // setiap penjualan detail dapat memilih 1 produk untuk dijual
        // ambil beberapa baris data dari table penjualan detail berdaasarkan penjualan_id yg dikirimkan dan setiap value detail_produk nyay
        $beberapa_penjualan_detail = PenjualanDetail::with('produk')->where('penjualan_id', $penjualan_id)->get();

        // kembalikkan datatables
        return datatables()
            // dari value $beberapa_penjualan_detail
            ->of($beberapa_penjualan_detail)
            // mulai lakukan pengulangan
            // looping nomor
            ->addIndexColumn()
            // pengulangan table produk, column kode_produk
            // $penjualan_detail berarti pengulangan terhadap detail penjualan_detail
            ->addColumn('kode_produk', function ($penjualan_detail) {
                // kembalikkan value detail penjualan_detail yang berelasi dengan table produk, column kode_produk
                return '<span class="badge badge-success">'. $penjualan_detail->produk->kode_produk .'</span>';
            })
            ->addColumn('nama_produk', function ($penjualan_detail) {
                return $penjualan_detail->produk->nama_produk;
            })
            ->addColumn('harga_jual', function ($penjualan_detail) {
                // kembalikkan panggil fungsi rupiah_bentuk milik helpers, lalu kirimkan value detail penjualan_detail yang berelasi dengan detail_produk, column harga_jual
                return rupiah_bentuk($penjualan_detail->produk->harga_jual);
            })
            ->addColumn('jumlah', function ($penjualan_detail) {
                return angka_bentuk($penjualan_detail->jumlah);
            })
            ->addColumn('subtotal', function ($penjualan_detail) {
                return rupiah_bentuk($penjualan_detail->subtotal);
            })
            // jika column berisi kode html maka harus dimasukkan ke mentahKolomKolom
            ->rawColumns(['kode_produk'])
            // buat
            ->make(true);
    }

    // method update untuk memperbarui detail table penjualan setelah aku click tombol Simpan Penjualan
    // parameter $request berisi value dari value attribute name milik penjualan_detail/form_penjualan
    public function update(Request $request)
    {
        // ambil detail_member
        // member dimana value column member_id sama degan value $request->member_id, ambil data baris pertama
        $detail_member = Member::where('member_id', $request->member_id)->first();

        // jika tidak ada detail_member karena $request->member_id berisi null karena user tidak memilih member di penjualan_detail/index maka
        if (!$detail_member) {
            // berisi 'Bukan Member';
            $nama_member = 'Bukan Member';
        } 
        // lain jika ada detail_member karena kasir memilih member di penjualan_detail/index
        else if ($detail_member) {
            // berisi value dari $detail_member, column nama_member
            $nama_member = $detail_member->nama_member;
        };

        // update 1 baris data table penjualan
        // ambil detail_penjualan berdasarkan column penjualan_id dengan cara penjualan dimana value column penjualan_id sama dengan value $permintaan->penjualan_id, pertama
        $detail_penjualan = Penjualan::where('penjualan_id', $request->penjualan_id)->first();
        // table penjualan, column member_id diisi dengan input name member_id
        $detail_penjualan->member_id = $request->member_id;
        $detail_penjualan->total_barang = $request->total_barang;
        $detail_penjualan->total_harga = $request->total_harga;
        $detail_penjualan->diskon = $request->diskon;
        $detail_penjualan->harus_bayar = $request->harus_bayar;
        $detail_penjualan->uang_diterima = $request->uang_diterima;
        $detail_penjualan->update();

        // ambil beberapa penjualan_detail terkait
        // penjualan_detail dimana value column penjualan_id sama dengan value parameter $detail_penjualan->penjualan_id, dapatkan beberapa data
        $beberapa_penjualan_detail = PenjualanDetail::where('penjualan_id', $detail_penjualan->penjualan_id)->get();
        // lakukan pengulangan kepada $beberapa_penjualan_detail
        foreach ($beberapa_penjualan_detail as $penjualan_detail) {
            // update penjualan detail
            $penjualan_detail->diskon = $request->diskon;
            $penjualan_detail->update();

            // update table produk
            // ambil detail produk
            // produk dimana value column produk_id sama dengan value $penjualan_detail->produk_id, data baris pertama
            $detail_produk = Produk::where('produk_id', $penjualan_detail->produk_id)->first();
            // logiknya kalo aku berjualan maka stok produk ku akan berkurang
            $detail_produk->stok -= $penjualan_detail->jumlah;
            // detail_produk diperbarui
            $detail_produk->update();
        };

        // kembalikkkan alihkan ke route penjualan.selesai
        return redirect()->route('penjualan.selesai');
    }

    public function selesai()
    {
        // ambil 1 baris data di table pengaturan
        $detail_pengaturan = Pengaturan::first();

        // kembalikkan ke tampilan('penjualan.selesai', kirimkan array yang berisi value $detail_pengaturan)
        return view('penjualan.selesai', ['detail_pengaturan' => $detail_pengaturan]);
    }

    // $request menangkap penjualan_id yang dikirimkan url
    public function nota_kecil($penjualan_id) {
        // ambil detail_penjualan berdasaran penjualan_id
        // berisi model penjualan dimana value column penjualan_id sama dengan value parameter $penjualan_id, ambil data baris pertama
        $detail_penjualan = Penjualan::where('penjualan_id', $penjualan_id)->first();
        // ambil beberapa data table penjualan_detail berdasarkan column penjualan_id
        // berisi model PenjualanDetail dimana value column penjualan_id sama dengan value variable $penjualan_id, ambil beberapa baris data
        $semua_penjualan_detail = PenjualanDetail::where('penjualan_id', $penjualan_id)->get();

        // kembalikkan ke tampilan penjualan.nota_kecil, kirimkan value variable $detail_penjualan
        return view('penjualan.nota_kecil', [
            // key detail_penjualan berisi value variable $detail_penjualan
            'detail_penjualan' => $detail_penjualan,
            // key semua_penjualan_detail berisi value variable $semua_penjualan_detail
            'semua_penjualan_detail' => $semua_penjualan_detail,
            // key nama_perusahaan berisi panggil database table pengaturan, ambil data baris pertama lalu ambil value column nama_perusahaan
            'nama_perusahaan' => DB::table('pengaturan')->first()->nama_perusahaan,
            'alamat_perusahaan' => DB::table('pengaturan')->first()->alamat_perusahaan,
        ]);
    }

    // untuk mencetak nota_besar setelah tombol cetak nota besar di click
    public function nota_besar()
    {
        // ambil value sesi('penjualan_id')
        $penjualan_id = session('penjualan_id');
        // ambil satu baris data pertama di table pengaturan
        // pengaturan::pertama()
        $detail_pengaturan = Pengaturan::first();
        // ambil detail_penjualan
        // Penjualan dimana value column penjualan_id sama dengan value $penjualan_id, data baris pertama
        $detail_penjualan = Penjualan::where('penjualan_id', $penjualan_id)->first();
        // jika tidak ada detail_penjualan karena mungkin user dari url /dashboard langsung ke url /penjualan/nota-besar
        if (!$detail_penjualan) {
            // abort(404)
            abort(404);
        };
        // ambil penjualan_detail terkait, anggaplah ada 3 baris
        // berisi penjualan_detail yang berelasi dengan table produk dimana value column penjualan_id sama dengan value $penjualan_id lalu dapatkan semua penjualan_detail terkait nya
        $beberapa_penjualan_detail = PenjualanDetail::with('produk')
            ->where('penjualan_id', $penjualan_id)
            ->get();

        // PDF::muatTampilan adalah package dari barryvdh/laravel-dompdf
        // berisi PDF::muatTampilan('penjualan.nota_besar')
        $pdf = PDF::loadView('penjualan.nota_besar', [
            'detail_pengaturan' => $detail_pengaturan,
            'detail_penjualan' => $detail_penjualan,
            'beberapa_penjualan_detail' => $beberapa_penjualan_detail
        ]);
        // $pdf->aturKertas(0,0,609,404, 'potret')
        $pdf->setPaper(0,0,609,440, 'potrait');
        // kembalikkan $pdf->mengalir('Transaksi-', tanggalHariINI.pdf)
        return $pdf->stream('Transaksi-'. date('Y-m-d-his') .'.pdf');
    }

    // hapus detail penjualan dan semua penjualan_detail terkait
    // publik fungsi hapus($penjualan_id), anggaplah $penjualan_id berisi angka 1
    public function destroy($penjualan_id)
    {
        // ambil detail penjualan
        // berisi Penjualan dimana value column penjualan_id sama dengan value parameter $penjualan_id, ambil data baris pertama
        $detail_penjualan = Penjualan::where('penjualan_id', $penjualan_id)->first();
        // ambil beberapa baris data dari table penjualan_detail berdasarkan column penjualan_id
        // berisi PenjualanDetail dimana value column penjualan_id sama dengan value $detail_penjualan->penjualan_id, dapatkan beberapa data, anggplah ada 3 baris data
        $beberapa_penjualan_detail = PenjualanDetail::where('penjualan_id', $detail_penjualan->penjualan_id)->get();

        // lakukan pengulangan
        // untuksetiap($beberapa_penjualan_detail sebagai $penjualan_detail)
        foreach ($beberapa_penjualan_detail as $penjualan_detail) {
            // berisi Produk dimana value column produk_id sama dengan $penjualan_detail->produk_id, ambil data baris pertama
            $detail_produk = Produk::where('produk_id', $penjualan_detail->produk_id)->first();
            // jika ada detail produk
            if ($detail_produk) {
                // panggil value $detail_produk, column stok, anggalah berisi 5 lalu di tambah value $penjualan_detail, column jumlah
                $detail_produk->stok += $penjualan_detail->jumlah;
                // detail_produk di perbarui
                $detail_produk->update();
            };

            // lakukan pengulangan penghapus penjualan_detail, anggaplah ada 3 baris penjualan_detail yang di hapus
            $penjualan_detail->delete();
        };

        // $detail_penjualan di hapus
        $detail_penjualan->delete();

        // kembalikkan tanggapan berupa json
        return response()->json([
            // key status berisi value 200
            'status' => 200,
            'message' => 'Berhasil menghapus 1 baris data milik table penjualan dan beberapa baris data milik table penjualan_detail'
        ]);
    }

    // method retur_penjualan agar aku bisa retur penjualan atau mengembalikkan penjualan
    // anggaplah parameter $penjualan_id berisi angka 1
    public function data_retur($penjualan_id)
    {   
        // karena relasi nya adalah belongsTo atau milik maka aku harus menggunakan pemuatan bersemangat menggunakan syntax ::with()
        // model PenjualanDetail berelasi dengan model produk jadi 1 penjualan detail hanya bisa membeli 1 produk
        // table PenjualanDetail yang berelasi dengan table produk dimana value column penjualan_id sama dengan $penjualan_id, dapatkan semua data terkait
        $beberapa_penjualan_detail = PenjualanDetail::with('produk')->where('penjualan_id', $penjualan_id)->get();

        // aku melakukan pengulangan disini
        // kembalikkan datatables dari beberapa_penjualan_detail
        return datatables()->of($beberapa_penjualan_detail)
            // lakukan pengulagan terhadap nomor
            ->addIndexColumn()
            // tambahKolom nama_produk, jalankan fungsi, parameter $penjualan_detail berisi setiap value detail_penjualan_detail
            ->addColumn('nama_produk', function (PenjualanDetail $penjualan_detail) {
                // kembalikan detail table penjualan_detail yang berelasi dengan detail table produk, lalu ambil value column nama_produk
                return $penjualan_detail->produk->nama_produk;
            })
            // penjelasan untuk attribute max, jadi jumlah retur tidak boleh melebihi jumlah penjualan, jadi anggaplah jumlah penjualan detail nya adalah 5 masa retur penjualan nya 10 dan minimal retur nya adalah 1
            ->addColumn('jumlah', function(PenjualanDetail $penjualan_detail) {
                // jika value $penjualan_detail, column retur_penjualan_id sama dengan NULL maka hapus attribute disabled agar aku bisa retur penjualan
                if ($penjualan_detail->retur_penjualan_id === NULL) {
                    // anggaplah berisi .jumlah_retur_1, .jumlah_retur_2, dst.
                    // untuk menampilkan efek input error, element input butuh .is-invalid
                    return "
                    <input name='jumlah_retur' type='number' class='jumlah_retur_$penjualan_detail->produk_id form-control input_$penjualan_detail->produk_id' value='$penjualan_detail->jumlah' max='$penjualan_detail->jumlah' min='1' autocomplete='off'>

                    <span class='jumlah_retur_error_$penjualan_detail->produk_id pesan_error_$penjualan_detail->produk_id text-danger'></span>
                    ";
                } 
                // lain jika value $penjualan_detail, column retur_penjualan_id tidak sama dengan NULL berarti sudah diisi dengan retur_penjualan_id maka berikan attribute disabled agar aku tidak bisa retur penjualan_detail lagi
                else if ($penjualan_detail->retur_penjualan_id !== NULL) {
                    // anggaplah berisi .jumlah_retur_1, .jumlah_retur_1, dst.
                    // untuk menampilkan efek input error, element input butuh .is-invalid
                    return "
                    <input name='jumlah_retur' type='number' class='jumlah_retur_$penjualan_detail->produk_id form-control input_$penjualan_detail->produk_id' value='$penjualan_detail->jumlah' max='$penjualan_detail->jumlah' min='1' autocomplete='off' disabled>
                    ";
                };
            })
            ->addColumn('keterangan', function (PenjualanDetail $penjualan_detail) {
                if ($penjualan_detail->retur_penjualan_id === NULL) {
                    return "<input name='keterangan' type='text' class='keterangan_$penjualan_detail->produk_id input_$penjualan_detail->produk_id form-control' autocomplete='off' autocomplete='off'>
                    <span class='keterangan_error_$penjualan_detail->produk_id pesan_error_$penjualan_detail->produk_id text-danger'></span>";
                } else if ($penjualan_detail->retur_penjualan_id !== NULL) {
                    return "<input name='keterangan' type='text' class='keterangan_$penjualan_detail->produk_id input_$penjualan_detail->produk_id form-control' autocomplete='off' autocomplete='off' disabled>";
                };
            })
            // tombol-rombol
            ->addColumn('action', function(PenjualanDetail $penjualan_detail) {
                // jika value penjualan_detail, column retur_penjualan_id sama dengan NULL atau kosong maka jangan kasi attribue disabled agar aku bisa retur pnejualan_detail
                if ($penjualan_detail->retur_penjualan_id === NULL) {
                    // jadi nanti ada id="tombol_retur_1", id="tombol_retur_2", dst.
                    return "
                    <button id='tombol_retur_$penjualan_detail->produk_id' data-toggle='keterangan_alat' data-placement='top' title='Retur penjualan' onclick='retur_penjualan($penjualan_detail->penjualan_detail_id, $penjualan_detail->produk_id, $penjualan_detail->penjualan_id)' class='btn btn-danger btn-sm ml-2' type='button'>
                    <i class='mdi mdi-credit-card-refund'></i>
                    </button>";
                }
                // lain jika value $penjualan-detail, column retur_penjualan_id tidak sama dengan kosong berarti sudah pernah retur maka kasi attribute disabeld agar aku tidak bisa retur penjualan_detail lagi
                else if ($penjualan_detail->retur_penjualan_id !== NULL) {
                    // jadi nanti ada id="tombol_retur_1", id="tombol_retur_2", dst.
                    return "
                    <button id='tombol_retur_$penjualan_detail->produk_id' data-toggle='keterangan_alat' data-placement='top' title='Retur penjualan' class='btn btn-danger btn-sm ml-2' type='button' disabled>
                    <i class='mdi mdi-credit-card-refund'></i>
                    </button>";
                };
                
            })
            // jika aku membuat element html di addColumn maka aku wajib memasukkan ke dalam rawColumns([]) atau mentahKolom
            // mentah column-column
            ->rawColumns(['jumlah', 'keterangan', 'action'])
            // buat nyata
            ->make(true);
    }

    // $request akan menangkap semua data yang dikirim oleh key data milik script
    public function retur_penjualan(Request $request)
    {
        // ambil detail penjualan_detail
        // berisi PenjualanDetail dimana value column penjualan_detail_id sama dengan value $request->penjualan_detail_id yang dikirim key data milik script, ambil rekaman baris pertama
        $detail_penjualan_detail = PenjualanDetail::where('penjualan_detail_id', $request->penjualan_detail_id)->first();
        // validasi semua input yang punya attribute name
        // berisi vaidator buat semua permintaan
        $validator = Validator::make($request->all(), [
            // input name="jumlah_retur" harus mengikuti aturan berikut
            // max berarti jika aku jual 10 produk maka maksimal produk yg bisa diretur adalah 10   
            'jumlah_retur' => "required|integer|min:1|max:$detail_penjualan_detail->jumlah",
            'keterangan' => 'required|max:255',
        ], [
            'jumlah_retur.max' => 'Tidak bisa retur melebihi jumlah jual'
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
        $penjualan_id = $request->penjualan_id;
        $produk_id = $request->produk_id;
        $penjualan_detail_id = $request->penjualan_detail_id;
        // intval() akan mengubah string menjadi integer, contohnya "100" menjadi 100
        $jumlah_retur = intval($request->jumlah_retur);
        $keterangan = $request->keterangan;

        // insert atau sisipkan rekaman ke dalam table Returpenjualan
        $detail_retur_penjualan = ReturPenjualan::create([
            // column penjualan_id diisi value variable $penjualan_id
            'penjualan_id' => $penjualan_id,
            'produk_id' => $produk_id,
            'jumlah_retur' => $jumlah_retur,
            // key tanggal_retur diisi tanggal dan waktu sekaang
            'tanggal_retur' => now(),
            'keterangan' => $keterangan
        ]);
        
        // value $detail_penjualan_detail, colum retur_penjualan_id diisi value $detail_retur_penjualan, column retur_penjualan_id
        $detail_penjualan_detail->retur_penjualan_id = $detail_retur_penjualan->retur_penjualan_id;
        // detail_penjualan_detail diperbarui
        $detail_penjualan_detail->update();

        // ambil detail produk
        // berisi produk dimana value column produk_id sama dengan value dari $detail_retur_penjualan, column produk_id, ambil rekaman baris pertama
        $detail_produk = Produk::where('produk_id', $detail_retur_penjualan->produk_id)->first();

        // berisi panggil detail_produk, column stok dikurangi value variable $jumlah_retur
        $detail_produk->stok = $detail_produk->stok - $jumlah_retur;
        $detail_produk->update();

        // kembalikkan tanggapan lalu kirimkan data berupa array
        return response()->json([
            // kirimkan key status yang berisi value 200
            'status' => 200,
            'message' => 'Berhasil melakukan retur penjualan'
        ]);
    }

    // export ke excel berdasarkan periode
    // $request akan menangkap data yang dikirim formulir
    public function export_excel(Request $request)
    {
        // dump and die atau cetak dan matikan semua value formulir
        // dd($request->all());
        // hasil dari dd adalah
        // "_token" => "g44mj6FNhUC8OcXibLgp8dBClErkjQ02bTaM67F7"
        // "tanggal_awal" => "2023-04-23"
        // "tanggal_hari_ini" => "2023-04-26"

        // berisi tangkap value input name="tanggal_awal"
        $tanggal_awal = $request->tanggal_awal;
        // berisi tangkap value input name="tanggal_akhir"
        $tanggal_akhir = $request->tanggal_akhir;

        // kembalikkan excel::unduh(new panggil PenjualanExport.php lalu kirimkan value parameter $tanggal_awal dan $tanggal_akhir, nama file nya adalah 'penjualan.xlsx')
        return Excel::download(new PenjualanExport($tanggal_awal, $tanggal_akhir), 'penjualan.xlsx');
    }

    // export ke excel semua penjualan_detail terkait nya
    public function export_excel_penjualan_detail($penjualan_id) {
        // kembalikkan excel::unduh(new panggil file PengeluaranDetailExport lalu kirimkan value parameter $penjualan_id, nama file nya adalah 'penjualan_detail.xlsx')
        return Excel::download(new PenjualanDetailExport($penjualan_id), 'penjualan_detail.xlsx');
    }
}