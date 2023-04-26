<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Member;
use App\Models\Pengaturan;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;

class PenjualanDetailController extends Controller
{
    // menampilkan halaman penjualan detail
    public function index()
    {
        // ambil semua produk, urutkan data dari a ke z
        // produk di pesan oleh column nama_produk, menaik, dapatkan semua data
        $semua_produk = Produk::orderBy('nama_produk', 'asc')->get();
        // berisi member di pesan oleh column nama_member, urutkan dari a ke z, ambil semua data
        $semua_member = Member::orderBy('nama_member', 'asc')->get();
        // ambil value column diskon dari 1 baris data pertama di table Pengaturan
        // jika tidak ada value di table Pengaturan, column diskon maka diisi 0, kalau ada diisi value column diskon_perusahaan
        $diskon = Pengaturan::first()->diskon_perusahaan ?? 0;

        // Cek apakah ada session penjualan_id
        // jika session('penjualan_id') tidak ada, berarti user dari url /dashboard langsung ke url /penjualan-detail
        if (session('penjualan_id')) {
            // tangkap sesi penjualan_id
            $penjualan_id = session('penjualan_id');
            // dd($penjualan_id);
            // ambil detail penjualan berdasarkan penjualan_id
            // penjualan dimana value column penjualan_id sma dengan vaue $penjualan_id, data baris pertama
            $detail_penjualan = Penjualan::where('penjualan_id', $penjualan_id)->first();
            // 1 penjualan dapat memeiliki 1 member untuk diberikan diskon berkat relasi belongsTo
            // ambil detail member menggunakan table penjualan karena ada relasi, kalau tidak ada maka kosongkan
            $detail_member = $detail_penjualan->member ?? new Member();

            // kirim semua produk, semua member, diskon, penjualan_id, detail penjualan
            return view('penjualan_detail.index', [
                'semua_produk' => $semua_produk,
                'semua_member' => $semua_member,
                'diskon' => $diskon,
                'penjualan_id' => $penjualan_id,
                'detail_penjualan' => $detail_penjualan,
                'detail_member' => $detail_member
            ]);
        } 
        // lain jika tidak ada session penjualan_id karena mungkin user dari url /dashboard langsung lompat ke url /penjualan-detail
        else if (!session('penjualan_id')) {
            // kembalikkan alihkan ke route('dashboard.index')
            return redirect()->route('dashboard.index');
        }
    }

    // tampilkan data table penjualan_detail
    public function data($penjualan_id)
    {
        // karena relasi belongsTo maka aku harus memnggunakan pemuatan bersemangat
        // ambil beberapa baris data dari table penjualan_detail yang sesuai dengan penjualan_id 
        // logikanya seperti ini, table penjualan_detail, column penjualan_detail_id value 1, menjual produk_id 1
        // berisi table penjualan_detail yang berelasi dengan table produk, dimana value column penjualan_id sama dengan value parameter $penjualan_id, dapatkan semua data
        $beberapa_penjualan_detail = PenjualanDetail::with('produk')
            ->where('penjualan_id', $penjualan_id)
            ->get();

        // berisi inisialisasi array
        $data = array();
        // nanti value nya akan di tambah misalnya, 0 + 100 = 100 lalu di tambah lagi 100 menjadi 200
        $total_harga = 0;
        $total_barang = 0;

        // lakukan pengulangan kepada value $bberapa_penjualan_detail
        foreach ($beberapa_penjualan_detail as $penjualan_detail) {
            // berisi inisialisasi array
            $row = array();
            // 1 penjualan detail bisa menjual 1 produk
            // table penjualan detail, column id_penjualan_detail misalnya 1 milik column id_produk misalnya 1
            $row['kode_produk'] = '<span class="badge badge-success">' . $penjualan_detail->produk['kode_produk'] . '</span';
            $row['nama_produk'] = $penjualan_detail->produk['nama_produk'];
            $row['harga_jual']  = rupiah_bentuk($penjualan_detail->harga_jual);
            $row['jumlah']      = '<input type="number" class="form-control input-sm jumlah" data-id="' . $penjualan_detail->penjualan_detail_id . '" value="' . $penjualan_detail->jumlah . '">';
            $row['diskon']      = $penjualan_detail->diskon . '%';
            $row['subtotal']    = rupiah_bentuk($penjualan_detail->subtotal);
            $row['aksi']        = '<div class="btn-group">
                                    <button onclick="hapus_data(`' . route('penjualan_detail.destroy', $penjualan_detail->penjualan_detail_id) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            // push $row ke $data
            $data[] = $row;

            // table penjualan_detail column subtotal + column subtotal
            $total_harga += $penjualan_detail->harga_jual * $penjualan_detail->jumlah - (($penjualan_detail->diskon * $penjualan_detail->jumlah) / 100 * $penjualan_detail->harga_jual);
            // jumlah pembelian_detail baris 1 adalah 5, di tambah jumlah pembelian_detail baris 2 berisi 5, berarti jumlahnya 10
            $total_barang += $penjualan_detail->jumlah;
        };
        // buat tr terakhir untuk menyimpan $total_harga dan $total_barang, lalu sembunyikan
        $data[] = [
            'kode_produk' => '',
            'nama_produk' => '',
            'harga_jual'  => '',
            'jumlah'      => '
                <div class="total_barang hide">' . $total_barang . '</div>
            ',
            'diskon'      => '',
            'subtotal'    => '
                <div class="total_harga hide">' . $total_harga . '</div>
            ',
            'aksi'        => '',
        ];

        // kembalikkan datatables
        return datatables()
            // dari value $data yang berisi tbody, tr, td dan data table pembelian_detail terkait
            // dari $data
            ->of($data)
            // lakukan pengulangan nomor
            // tambahIndexKolom
            ->addIndexColumn()
            // jika
            // mentahKolomKolom dari kode_produk, jumlah dan aksi
            ->rawColumns(['kode_produk', 'jumlah', 'subtotal', 'aksi',])
            // buat benar
            ->make(true);
    }

    // simpan pembelian_detail setelah aku memilih produk di modal pilih produk
    public function store(Request $request)
    {
        // ambil detail produk berdasarkan value name produk_id yang dikirimkan
        // berisi produk dimana value column produk_id sama dengan value $request->produk_id, data baris pertama
        $detail_produk = Produk::where('produk_id', $request->produk_id)->first();

        // jika tidak ada produk, sudah pasti ada
        if (! $detail_produk) {
            // kembalikkan tanggapan berupa json
            return response()->json([
                // key status berisi value 400
                'status' => 400,
                'message' => 'Data Gagal Disimpan.'
            ]);
        };

        // simpan data ke table penjualan_detail
        // PenjualanDetail::buat([])
        PenjualanDetail::create([
            // key penjualan_id berisi value $request->penjualan_id
            'penjualan_id' => $request->penjualan_id,
            // key produk_id berisi value detail_produk, column produk_id
            'produk_id' => $detail_produk->produk_id,
            'kode_produk' => $detail_produk->kode_produk,
            'nama_produk' => $detail_produk->nama_produk,
            // key harga_jual berisi value detail_produk, column harga_jual
            'harga_jual' => $detail_produk->harga_jual,
            // column jumlah akan diiisi angka 1 secara bawaan
            'jumlah' => 1,
            // key diskon berisi value detail_produk, column diskon
            'diskon' => $detail_produk->diskon,
            // KUKABATAKU, kurung, kali, bagi, tambah, kurang
            'subtotal' => $detail_produk->harga_jual - ($detail_produk->diskon / 100 * $detail_produk->harga_jual)
        ]);

        // kembalikkan tanggapan berupa json
        return response()->json([
            // key status berisi value 200
            'status' => 200,
            // key message berisi value Pembelian Detail Berhasil Disimpan
            'message' => 'Pembelian Detail Berhasil Disimpan'
        ]);
    }

    // ada value bawaan di setiap parameter jadi jika aku tidak mengirim argument maka dia akan menggunakan nilai bawaan
    public function muat_ulang_form($diskon = 0, $total_harga = 0, $uang_diterima = 0)
    {
        // kukabataku (kurung, kali, bagi, tambah, kurang)
        $harus_bayar = $total_harga - ($diskon / 100 * $total_harga);
        // jika value $uang_diterima tidak sama kosong maka value uang_diterima anggaplah 10.000.000 di kurangi value $harus_bayar anggaplah 10.000.000, kalau value uang_Diterima sama dengan 0 maka isi 0
        $kembali = ($uang_diterima != 0) ? $uang_diterima - $harus_bayar : 0;
        $data = [
            // berisi memanggil helpers fungsi rupiah_bentuk lalu mengirim value $total_harga sebagai argument
            'total_rp' => rupiah_bentuk($total_harga),
            'harus_bayar' => $harus_bayar,
            'bayar_rp' => rupiah_bentuk($harus_bayar),
            // ucwords akan membuat semua kalimat menjadi huruf kecil semua
            // fungsi terbilang akan mengubah 1000 menjadi seribu
            'terbilang' => ucwords(terbilang($harus_bayar)),
            'kembali_rp' => rupiah_bentuk($kembali),
            'kembali_terbilang' => ucwords(terbilang($kembali)),
        ];

        // kembalikkan tanggapan berupa json
        return response()->json([
            // key status berisi 200
            'status' => 200,
            // key message berisi value berikut
            'message' => 'Berhasil muat ulang formulir',
            'data' => $data
        ]);
    }

    public function update(Request $request, $penjualan_detail_id)
    {
        $detail_penjualan_detail = PenjualanDetail::find($penjualan_detail_id);
        $detail_penjualan_detail->jumlah = $request->jumlah;
        $detail_penjualan_detail->subtotal = $detail_penjualan_detail->harga_jual * $request->jumlah - (($detail_penjualan_detail->diskon * $request->jumlah) / 100 * $detail_penjualan_detail->harga_jual);;
        $detail_penjualan_detail->update();
    }

    public function destroy($penjualan_detail_id)
    {
        $penjualan_detail = PenjualanDetail::find($penjualan_detail_id);
        $penjualan_detail->delete();

        return response('Berhasil menghapus 1 baris data di table penjualan_detail');
    }
}
