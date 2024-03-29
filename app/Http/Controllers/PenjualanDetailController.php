<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// perluas kelas dasar
use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Member;
use App\Models\Pengaturan;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\TutupPenjualanExport;
use App\Exports\TutupPenjualanVersi2Export;
// agar bisa berhubungan dengan excel
use Maatwebsite\Excel\Facades\Excel;
// untuk membuat fitur yang berkatiran dengan waktu seperti tanggal dan jam
use Carbon\Carbon;

class PenjualanDetailController extends Controller
{
    // menampilkan halaman penjualan detail
    public function index()
    {
        // ambil semua produk, urutkan data dari a ke z
        // produk di pesan oleh column nama_produk, menaik, dapatkan semua data
        $semua_produk = Produk::select('produk_id', 'kategori_id', 'penyuplai_id', 'kode_produk', 'nama_produk', 'harga_beli', 'diskon', 'harga_jual', 'stok')->orderBy('nama_produk', 'asc')->get();
        // berisi member di pesan oleh column nama_member, urutkan dari a ke z, ambil semua data
        $semua_member = Member::orderBy('nama_member', 'asc')->get();
        // ambil value column diskon_perusahaan dari 1 baris data pertama di table Pengaturan
        // jika tidak ada value di table Pengaturan, column diskon maka diisi 0, kalau ada diisi value column diskon_perusahaan
        $diskon = Pengaturan::first()->diskon_perusahaan ?? 0;

        // kirim semua produk, semua member, diskon, penjualan_id, detail penjualan
        return view('penjualan_detail.index', [
            'semua_produk' => $semua_produk,
            'semua_member' => $semua_member,
            'diskon' => $diskon
        ]);
    }

    // parameter $request menangkap value dari key produk_id milik script
    public function ambil_detail_produk(Request $request)
    {
        // berisi ambil value dari key produk_id yang di kirim script
        $produk_id = $request->produk_id;

        // ambil detail_produk dan relasi nya seperti kategori dan penyuplai nya, relasi sudah pemuatan bersemangat karena aku sudah atur di models/produk
        // berisi table produk, pilih value column produk_id, dan lain-lain,, dimana value column produk_id sama dengan value $produk_Id, ambil data baris pertama
        $detail_produk = Produk::select('produk_id', 'kategori_id', 'penyuplai_id', 'kode_produk', 'nama_produk', 'harga_beli', 'diskon', 'harga_jual', 'stok')->where('produk_id', $produk_id)->first();

        // kembalikkan tanggapan berupa json lalu kirimkan data berupa array
        return response()->json($detail_produk);
    }

    // simpan detail penjualan dan simpan beberapa penjualan_detail
    public function store(Request $request)
    {
        // berisi ambil value semua_produk_id nama yang didapatkan dari script, angapalh berisi [1, 2]
        // $semua_produk_id = $permintaan->semua_produk_id
        $semua_produk_id = $request->semua_produk_id;
        $semua_kode_produk = $request->semua_kode_produk;
        $semua_nama_produk = $request->semua_nama_produk;
        $semua_harga_jual = $request->semua_harga_jual;
        $semua_jumlah = $request->semua_jumlah;
        $semua_subtotal = $request->semua_subtotal;

        // $penjualan_id = $request->penjualan_id;
        // jika aku click tombol pilih di modal pilih member maka $member_id akan diisi member_id, jika tidak maka akan diisi NULL
        $member_id = $request->member_id;
        $total_barang = $request->total_barang;
        $total_harga = $request->total_harga;
        $diskon = $request->diskon;
        $harus_bayar = $request->harus_bayar;
        $uang_diterima = $request->uang_diterima;
        $keterangan_penjualan = $request->keterangan_penjualan;
        $tanggal_dan_waktu = $request->tanggal_dan_waktu;

        // jika ada value di #member_id atau ada angka atau aku memilih member maka ambil detail_member
        if ($member_id) {
            // ambil nama_member
            // berisi table member dimana value column member_id sama dengan value variable member_id, ambil data baris pertama, column nama_member
            $nama_member = Member::where('member_id', $member_id)->first()->nama_member;
            // berisi 5
            $diskon = 5;
        }
        // lain jika tidak ada nilai di #member_id atau input member_id
        else if (!$member_id) {
            // $nama_member diisi NULL
            $nama_member = NULL;
            $diskon = 0;
        };


        // simpan detail_penjualan secara sementara
        // berisi Penjualan::buat([])
        $detail_penjualan = Penjualan::create([
            // column member_id berisi value key member_id yang dikirim script
            'member_id' => $request->member_id,
            // berisi id user yang login
            'user_id' => Auth::id(),
            'total_barang' => $total_barang,
            'total_harga' => $total_harga,
            'diskon' => $diskon,
            'harus_bayar' => $harus_bayar,
            'uang_diterima' => $uang_diterima,
            'keterangan_penjualan' => $keterangan_penjualan,
            'tanggal_dan_waktu' => $tanggal_dan_waktu
        ]);

        // lakukan pengulangan
        // selama 0 lebih kecil dari panjang value array semua_produk_id maka lakukan pengulangan, anggaplah ada 3 pengulangan
        for ($i = 0; $i < count($semua_produk_id); $i++) {
            // inisialisasi model PenjualanDetail
            $penjualan_detail = new PenjualanDetail();
            // panggil table penjualan_detail secara berulang lalu isi dengan value $penjualan_id, anggaplah berisi angka 1
            $penjualan_detail->penjualan_id = $detail_penjualan->penjualan_id;
            // misalnya panggil table penjualan_detail, column produk_id diisi array semua_produk_id, index 0, index 1, dst.
            // column semua_produk_id di table penjualan_detail diisi $semua_produk_id[$i]
            $penjualan_detail->produk_id = $semua_produk_id[$i];
            $penjualan_detail->jumlah = $semua_jumlah[$i];
            $penjualan_detail->subtotal = $semua_subtotal[$i];
            // penjualan_detail disimpan
            $penjualan_detail->save();

            // berisi ambil detail produk
            // berisi produk dimana value atau nilai column produk_id sama dengan nilai $penjualan_detail, column produk_id, data baris pertama
            $detail_produk = Produk::where('produk_id', $penjualan_detail->produk_id)->first();
            $detail_produk->stok = $detail_produk->stok - $penjualan_detail->jumlah;
            $detail_produk->update();
        };


        // kembalikkan tanggapan berupa json lalu kirimkan data berupa array
        return response()->json([
            // key status berisi value 200
            'status' => 200,
            // key message berisi value berikut
            'message' => 'Berhasil menyimpan beberapa data ke table penjualan_detail lalu berhasil memperbarui table penjualan',
            // kirimkan key penjualan_id berisi value detail_penjualan, column penjualan_id anggaplah berisi 1
            'penjualan_id' => $detail_penjualan->penjualan_id
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

    // method update akan dijalankan setelah aku mengubah jumlah penjualan_detail
    // $request akan berisi value key jumlah
    public function update(Request $request, $penjualan_detail_id)
    {
        // ambil detail penjualan_detail
        // berisi penjualan_detail dimana value column penjualan_detail_id sama dengan vaue $penjualan_detail_id, ambil data baris pertama
        $detail_penjualan_detail = PenjualanDetail::where('penjualan_detail_id', $penjualan_detail_id)->first();
        // detail_penjualan_detail, column jumlah diisi dengan value input jumlah
        $detail_penjualan_detail->jumlah = $request->jumlah;
        // detail_penjualan_detail, column subtotal diisi dengan berikut ini
        $detail_penjualan_detail->subtotal = $detail_penjualan_detail->harga_jual * $request->jumlah - (($detail_penjualan_detail->diskon * $request->jumlah) / 100 * $detail_penjualan_detail->harga_jual);
        // detal_penjualan_detail di perbarui
        $detail_penjualan_detail->update();
    }

    public function destroy($penjualan_detail_id)
    {
        $penjualan_detail = PenjualanDetail::find($penjualan_detail_id);
        $penjualan_detail->delete();

        return response('Berhasil menghapus 1 baris data di table penjualan_detail');
    }

    // $permintaan menangkap value key jumlah dan produk_id yang dikirimkan script
    public function cek_stok_produk(Request $request)
    {
        // tangkap value input jumlah
        // berisi $permintaan->jumlah
        $jumlah = $request->jumlah;
        // berisi $permintaan->produk_id
        $produk_id = $request->produk_id;

        // berisi ambil value detail_produk, column stok berdasarkan produk_id
        // berisi Produk dimana value column produk_id sama dengan value variable $produk_id, ambil data baris pertama, column stok
        $stok_produk = Produk::where('produk_id', $produk_id)->first()->stok;

        // jika value input jumlah lebih besar dari value $stok_produk misalnya value input jumlah berisi 1000 lalu value $stok_produk berisi 80 maka
        if ($jumlah > $stok_produk) {
            // kembalikkan tanggapan berupa json kirimkan data berupa array
            return response()->json([
                // key pesan berisi pesan berikut
                'message' => "Stok produk nya tersisa $stok_produk",
                // key stok_produk berisi value $stok_produk
                'stok_produk' => $stok_produk,
            ]);
        };
    }

    // $request kan menangkap data objek yg dikirim oleh javascript
    public function tutup_penjualan(Request $request)
    {
        // berisi ambil detail user yg login, column user_id
        $user_id = Auth::user()->user_id;
        // ambil detail karyawan yang column user_id nya berisi $detail_user_yg_login, column_user_id, ambil detal datanya dan value column jam_masuk
        $jam_masuk_karyawan = Karyawan::where('user_id', $user_id)->first()->jam_masuk;
        $jam_keluar_karyawan = Karyawan::where('user_id', $user_id)->first()->jam_keluar;

        // berisi tanggal hari ini
        // berisi karbon::sekarang->lokal('indonesia')->bentuk('Y-m-d')
        $tanggal_hari_ini = Carbon::now()->locale('id')->format('Y-m-d');

        // berisi tangal dan jam mausk karyawan
        // berisi value variable $tanggal_hari_ini digabung dengan spasi lalu digabung dengan value variable $jam_masuk_karyawan
        $tanggal_dan_jam_masuk_karyawan = $tanggal_hari_ini . ' ' . $jam_masuk_karyawan;
        $tanggal_dan_jam_keluar_karyawan = $tanggal_hari_ini . ' ' . $jam_keluar_karyawan;
        // dd($tanggal_dan_jam_keluar_karyawan);


        // berisi value dari $permintaan->waktu_jam_7
        // $waktu_jam_7 = $request->waktu_jam_7;
        // $waktu_tutup = $request->waktu_tutup;

        // berisi value dari input name="hasil_pilihan"
        $hasil_pilihan = $request->hasil_pilihan;

        // berisi table PenjualanDetail, pilih value table penjualan_detail, column jumlah
        $total_produk = PenjualanDetail::select('penjualan_detail.jumlah')
        // ambil beberapa table penjualan_detail, yg column updated_at nya berada di jangkauan $permintaan->waktu_jam_7 sampai $permintaan->waktu_tutup
        // diamana antara value penjualan_detail, column updated_at, waktu_jam_7 sampai waktu_tutup
        ->whereBetween('penjualan_detail.updated_at', [$tanggal_dan_jam_masuk_karyawan, $tanggal_dan_jam_keluar_karyawan])
        // lakukan pertambahan pada beberapa value column subtotal
        ->sum('jumlah');

        $total_harga = PenjualanDetail::select('penjualan_detail.subtotal')
        ->whereBetween('penjualan_detail.updated_at', [$tanggal_dan_jam_masuk_karyawan, $tanggal_dan_jam_keluar_karyawan])
        ->sum('subtotal');

        // berisi menyetel waktu Karbon yaitu sekarang lalu setel Lokal nya ke Asia/Jakarta
        $tanggal_hari_ini = Carbon::now('Asia/Jakarta');

        // cetak semua permintaan yang dikirim
        // dd($beberapa_penjualan_detail);

        // jika value variable hasil_pilihan nya berisi "PDF"
        if ($hasil_pilihan === "PDF") {
            // dapatkan data table PenjualanDetail berdasarkan range atau jangkauan yang dikirimkan
            // kembalikkan data PenjualanDetail, pilih kolom-kolom berikut seperti table produk column nama_produk dan harga_jual dan table penjualan_detail, column jumlah dan subtotal
            $beberapa_penjualan_detail = PenjualanDetail::select('produk.nama_produk', 'produk.harga_jual', 'penjualan_detail.jumlah', 'penjualan_detail.subtotal')
            // berelasi dengan table produk lewat column produk_id
            // table penjualan_detail digabung dengan table produk, value table produk, column produk_id sama dengan value table penjualan_detail, column produk_id
            ->join('produk', 'produk.produk_id', '=', 'penjualan_detail.produk_id')
            // ambil beberapa table penjualan_detail, yg column updated_at nya berada di jangkauan $permintaan->tanggal_dan_jam_masuk_karyawan sampai $permintaan->tanggal_dan_jam_keluar_karyawan
            // diamana antara value penjualan_detail, column updated_at, tanggal_dan_jam_masuk_karyawan sampai tanggal_dan_jam_keluar_karyawan
            ->whereBetween('penjualan_detail.updated_at', [$tanggal_dan_jam_masuk_karyawan, $tanggal_dan_jam_keluar_karyawan])
            // dapatkan data
            ->get();

            // menyesuaikan nama file yg akan di download
            $nama_file = "laporan_tutup_penjualan_{$tanggal_hari_ini->format('d-m-Y')}_{$tanggal_hari_ini->format('H-i')}.pdf";

            // berisi PDF muat tampilan file berikut dan kirimkan data berupa array
            $pdf = PDF::loadView('penjualan.pdf_tutup_penjualan', [
                // kunci name berisi detail user yg login, column name
                'name' => Auth::user()->name,
                // kunci beberapa_penjualan_detail berisi value variable $beberapa_penjualan_detail
                'beberapa_penjualan_detail' => $beberapa_penjualan_detail,
                // contoh hasilnya adalah 13 Februari 2023
                'tanggal_hari_ini' => $tanggal_hari_ini->format('d F Y'),
                'waktu_tutup' => $tanggal_dan_jam_keluar_karyawan,
                'total_produk' => $total_produk,
                'total_harga' => $total_harga
            ]);

            // atur kertas menggunakan A4, bentuk potrait atau lebih ke horizontal
            $pdf->setPaper('a4', 'potrait');
            // kembalikkan $pdf, stream itu berfungsi untuk mendownload file dengan nama yg sesuai dari value variable $nama_file
            return $pdf->stream($nama_file);
        }
        // lain jika value variable hasil_pilihan berisi "EXCEL:
        else if ($hasil_pilihan === "EXCEL") {
            // menyesuaikan nama file yg akan di download
            $nama_file = "laporan_tutup_penjualan_{$tanggal_hari_ini->format('d-m-Y')}_{$tanggal_hari_ini->format('H-i')}.xlsx";
            // // kembalikkan excel::unduh(new panggil TutupPenjualanExport.php lalu kirimkan value parameter $tanggal_dan_jam_masuk_karyawan dan $tanggal_dan_jam_keluar_karyawan, nama file nya adalah value variable $nama_file
            // return Excel::download(new TutupPenjualanExport($tanggal_dan_jam_masuk_karyawan, $tanggal_dan_jam_keluar_karyawan), $nama_file);
            // kembalikkan excel::unduh(new panggil TutupPenjualanExport.php lalu kirimkan value parameter $tanggal_dan_jam_masuk_karyawan dan $tanggal_dan_jam_keluar_karyawan, nama file nya adalah value variable $nama_file
            return Excel::download(new TutupPenjualanVersi2Export($tanggal_dan_jam_masuk_karyawan, $tanggal_dan_jam_keluar_karyawan), $nama_file);

        };

    }
}

