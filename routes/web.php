<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutentikasiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EditProfileController;
use App\http\Controllers\PengaturanController;
use App\Http\Controllers\ManajemenKasirController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PenyuplaiController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PembelianDetailController;
use App\Http\Controllers\ReturPembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PenjualanDetailController;
use App\Http\Controllers\LaporanController;

// hanya tamu atau user yang belum login atau auth yang bisa mengakses url berikut
// middleware untuk guest, guest di dapatkan dari App/Http/Kernel.php
Route::middleware(['guest'])->group(function() {
    // url awal
    // jika user di url awal maka jalankan fungsi berikut
    Route::get('/', function () {
        // kembali alihkan route login
        return redirect()->route('login.index');
    });

    // login
    // route tipe dapatkan, ke url /login, ke AutentikasiController, ke method index, name nya adalah login.index
    Route::get('/login', [AutentikasiController::class, 'index'])
                ->name('login.index');
    // route tipe kirim, ke url /login, ke AutentikasiController, ke method store, name nya adalah login.store
    Route::post('/login', [AutentikasiController::class, 'store'])->name('login.store');
});



// middleware untuk yang sudah login, auth di dapatkan dari App/Http/Kernel.php
// route tipe perangkatTengah, untuk yg sudah login, grup, jalankan fungsi
Route::middleware(['auth'])->group(function() {
    // dashboard
    // route tipe dapatkan, jika user diarahkan ke url /dashboard maka arahkan ke DashboadController, method index, name nya adalah dashboard.index
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // logout
    // route tipe dapatkan, ke url logout, ke controller dan method berikut, dan punya name 
	Route::get('/logout', [AutentikasiController::class, 'logout'])->name('logout');

    // edit profile
    Route::get('/edit-profile', [EditProfileController::class, 'edit'])->name('edit_profile');
    Route::post('/edit-profile/perbarui', [EditProfileController::class, 'update'])->name('update_profile');
    // Route tipe post, arahakn user ke url berikut, lalu ke controller dan method berikut, dan ada namenya
    Route::post('/edit-profile/update-password', [EditProfileController::class, 'update_password'])->name('edit_profile.update_password');

    // penjualan
    // route tipe dapatkan, jika user diarahkan ke url /penjualan/create maka maka PenjualanController, method create, name nya adalah penjualan.create
    Route::get('/penjualan/create', [PenjualanController::class, 'create'])->name('penjualan.create');
    // route tipe dapatkan, jika user di arahkan ke url /penjualan maka arahkan PenjualanController, ke method index, name nya adalah penjualan.index
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    // route tipe dapatkan, jika user di arahkan ke url /penjualan/data maka arahkan ke PenjualanController, method data, name nya adalah penjualan.data
    Route::get('/penjualan/data', [PenjualanController::class, 'data'])->name('penjualan.data');
    // route tipe kirim, jika user di arahkan ke url /penjualan maka arahkan PenjualanController, method update, name nya adalah penjualan.update
    Route::post('/penjualan', [PenjualanController::class, 'update'])->name('penjualan.update');
    // route tipe dapatkan, jika user diarahkan ke url /penjualan/selesai, maka arahkan ke PenjualanController, method selesai, name nya adalah penjualan.selesai
    Route::get('/penjualan/selesai', [PenjualanController::class, 'selesai'])->name('penjualan.selesai');
    // route tipe dapatkanm jika user diarahkan ke url /penjualan/nota-kecil maka arahkan ke PenjualanController, method nota_kecil name nya adalah penjualan.nota_kecil
    Route::get('/penjualan/nota-kecil', [PenjualanController::class, 'nota_kecil'])->name('penjualan.nota_kecil');
    // route tipe dapatkan, jika user di arahkan ke url /penjualan/nota-besar maka arahkan ke PenjualanController, method nota_besar, name nya adalah penjualan.nota_besar
    Route::get('/penjualan/nota-besar', [PenjualanController::class, 'nota_besar'])->name('penjualan.nota_besar');
    Route::get('/penjualan/{penjualan_id}', [PenjualanController::class, 'show'])->name('penjualan.show');
    // route tipe hapus, jika user di url /penjualan maka kirimkan penjualan_id lalu ke PenjualanController, method destroy, name nya adalah penjualan.destroy
    Route::delete('/penjualan/{penjualan_id}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');
    // retur penjualan
    // route tipe dapatkan, jika user di arahkan ke url berikut maka kirimkan penjualan_id anggaplah berisi angka 1 lalu arahkan ke penjualanController, method data_retur, namenya adalah penjualan.data_retur
    Route::get('/penjualan/data-retur/{penjualan_id}', [PenjualanController::class, 'data_retur'])->name('penjualan.data_retur');
    // route tipe kirim, ke url /penjualan/retur-penjualan lalu kirimkan ke PenjualanController, method retur_penjualan, name nya adalah penjualan.retur_penjualan
    Route::post('/penjualan/retur-penjualan', [PenjualanController::class, 'retur_penjualan'])->name('penjualan.retur_penjualan');
    // route tipe kirim, jika user diarahkan ke url /penjualan/export-excel maka arahkan ke PenjualanController, method export_excel, name nya adalah penjualan.export_excel
    Route::post('/penjualan/export-excel', [PenjualanController::class, 'export_excel'])->name('penjualan.export_excel');
    // route tipe kirim, jika user diarahkan ke url berikut maka kirimkan penjualan_id lalu ke PenjualanController, ke method berikut, name nya adalah berikut
    Route::get('/penjualan/penjualan-detail/export-excel/{penjualan_id}', [PenjualanController::class, 'export_excel_penjualan_detail'])->name('export_excel.penjualan_detail');
    // route tipe kirim, jika user diarahkan ke url berikut maka arahkan ke PenjualanController, method cek_stok_produk, name nya adalah penjualan.cek_stok_produk
    Route::post('/penjualan/cek-stok-produk', [PenjualanController::class, 'cek_stok_produk'])->name('penjualan.cek_stok_produk');



    // detail penjualan
    // route tipe kirim, jika user diarahkan ke url /penjualan-detail, ke PenjualanDetailController, ke method store, name nya adalah penjualan_detail.store
    Route::post('/penjualan-detail', [PenjualanDetailController::class, 'store'])->name('penjualan_detail.store');
    // route tipe dapatkan, jika user di arahkan ke /penjualan-detail maka arahkan PenjualanDetailController, method index, name nya adalah penjualan_detail.index
    Route::get('/penjualan-detail', [PenjualanDetailController::class, 'index'])->name('penjualan_detail.index');
    // route tipe dapatkan, jika user di arahkan ke url berikut maka kirimkan penjualan_id, ke PenjualanController, method data, name nya adalah penjualan_detail.data
    Route::get('/penjualan-detail/data/{penjualan_id}', [PenjualanDetailController::class, 'data'])->name('penjualan_detail.data');
    // route tipe dapatkan, jika user di arahkan ke /penjualan-detail/load-form/ maka kirimkan 3 argument, lalu ke PenjualanDdetailController, method load_form, name nya adalah penjualan_detail.load_form
    Route::get('/penjualan-detail/muat-ulang-form/{diskon}/{total_harga}/{uang_diterima}', [PenjualanDetailController::class, 'muat_ulang_form'])->name('penjualan_detail.muat_ulang_form');
    // route tipe letakkan, jika user diarahkan ke url /penjualan-detail/ tangkap dan kirimkan penjualan_detail_id, panggil PenjualanDetailController, method update, name nya adalah penjualan_detail.update
    Route::put('/penjualan-detail/{penjualan_detail_id}', [PenjualanDetailController::class, 'update'])->name('penjualan_detail.update');
    Route::delete('/penjualan-detail/{penjualan_id_detail}', [PenjualanDetailController::class, 'destroy'])->name('penjualan_detail.destroy');
    // route tipe kirim, jika user diarahkan ke url berikut maka panggil PenjualanDetailController, method ambil_detail-produk, name nya adalah penjualan_detail.ambil_detail_produk
    Route::post('/penjualan-detail/ambil-detail-produk', [PenjualanDetailController::class, 'ambil_detail_produk'])->name('penjualan_detail.ambil_detail_produk');
    // route tipe kirim, jika user diarahkan ke url berikut maka arahkan ke PenjualanDetailController, method cek_stok_produk, name nya adalah penjualan_detail.cek_stok_produk
    Route::post('/penjualan-detail/cek-stok-produk', [PenjualanDetailController::class, 'cek_stok_produk'])->name('penjualan_detail.cek_stok_produk');
});


// middleware untuk admin yang sudah login
// auth di dapatkan dari Kernel.php
// is_admin di dapatkan dari App/Providers/AuthServiceProvider.php
// hanya admin yang sudah login yang bisa mengakses url berikut
Route::middleware(['can:is_admin', 'auth'])->group(function() {
    // pengaturan
    // Route tipe dapatkan, ke url pengauran, ke PengaturanController, method index, namenya adalah pengaturan.index 
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    // Route tipe kirim, ke url pengaturan, ke PengaturanController, method update, namenya pangaturan.update
    Route::post('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');

    // Manajemen kasir
    // route tipe dapatkan ke url manajemen-kasir, ke ManajemenKasirController, method index, namenya adalah manajemen_kasir.index
    Route::get('/manajemen-kasir', [ManajemenKasirController::class, 'index'])->name('manajemen_kasir.index');
    // Route tipe post ke url manajemen-kasir, ke ManajemenKasriController, method strore, namneya adalah manajemen_kasir.store
    Route::post('/manajemen-kasir', [ManajemenKasirController::class, 'store'])->name('manajemen_kasir.store');
    // ini untuk menghapus kasir
    // Route tipe post, ke url manajemen-kasir/hapus-terpilih, ke ManajemenKasirController, method destroy, namanya adalah manajemen_kasir.destroy
    Route::post('/manajemen-kasir/hapus-terpilih', [ManajemenKasirController::class, 'destroy'])->name('manajemen_kasir.destroy');

    // Kategori
    // route tipe dapatkan, ke url kategori, ke KategoriController, method index, namenhya adalah kategori.index
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    // show detaik kategori di modal edit
    Route::get('/kategori/{kategori_id}', [KategoriController::class, 'show'])->name('kategori.show');
    // perbarui kategori
    // Route tipe letakkan, ke url kategori/{kategori_id}, ke KategoriController, method update
    Route::put('/kategori/{kategori_id}', [KategoriCOntroller::class, 'update'])->name('kategori.update');
    // hapus kategori yang dipilih lewat pengiriman data di script
    Route::delete('/kategori/destroy', [KategoriController::class, 'destroy'])->name('kategori.destroy');

    // produk
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
    // route tipe kirim, ke url /produk, ke ProdukController, ke method store, namenya adalah produk.store
    Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
    // route tipe dapatkan, panggil url /produk/ lalu tangkap dan kirimkan id_produk, ke ProdukController, method Show, namenya adalah produk.show
    Route::get('/produk/{produk_id}', [ProdukController::class, 'show'])->name('produk.show');
    // route tipe letakkan, ke url /produk/ lalu kirimkan produk_id, ke ProdukController, ke method update, namenya adalah produk.update
    Route::put('/produk/{produk_id}', [ProdukController::class, 'update'])->name('produk.update');
    Route::get('/produk-dan-relasinya', [ProdukController::class, 'data_relasinya'])->name('produk.data_relasinya');
    Route::post('/produk/destroy', [ProdukController::class, 'destroy'])->name('produk.destroy');
    // route tipe kirim, ke url /produk/cetak-barcode, ke ProdukController, ke method cetak_barcode, namenya adalah produk.cetak_barcode
    Route::post('/produk/cetak-barcode', [ProdukController::class, 'cetak_barcode'])->name('produk.cetak_barcode');

    // penyuplai
    // route tipe dapatkan, ke url penyuplai, ke PenyuplaiController, ke method index
    // namenya penyuplai.index
    Route::get('/penyuplai', [PenyuplaiController::class, 'index'])->name('penyuplai.index');
    // route tipe dapatkan, ke url /penyuplai/read, ke PenyuplaiController, ke method read, name nya adalah penyuplai.read
    Route::get('/penyuplai/read', [PenyuplaiController::class, 'read'])->name('penyuplai.read');
    // route tipe kirim, ke url penyuplai, ke PenyuplaiController, method store, namenya penyuplai.store
    Route::post('/penyuplai', [PenyuplaiController::class, 'store'])->name('penyuplai.store');
    // route tipe dapatkan, url /penyuplai/dapat dan kirim penyuplai_id ke PenyuplaiController, method show
    Route::get('/penyuplai/{penyuplai_id}', [PenyuplaiController::class, 'show'])->name('penyuplai.show');
    // route tipe letakkan, ke url /penyuplai lalu ambil dan kirim penyuplai_id, ke PenyuplaiController, method update, namenya adalah penyuplai.update
    Route::put('/penyuplai/{penyuplai_id}', [PenyuplaiController::class, 'update'])->name('penyuplai.update');
    // route tipe kirim, ke url /penyuplai/hapus-terpilih, ke PenyuplaiController, ke method destroy, namenya adalah destroy
    Route::post('/penyuplai/destroy', [PenyuplaiController::class, 'destroy'])->name('penyuplai.destroy');

    // member
    // route tipe dapatkan, ke url member, ke MemberController, ke method index, namenya adalah member.index
    Route::get('/member', [MemberController::class, 'index'])->name('member.index');
    // route tipe dapatkan, ke url member/read, ke MemberController, ke method read, namenya adalah member.read
    Route::get('/member/read', [MemberController::class, 'read'])->name('member.read');
    // route tipe kirim, ke url member, ke MemberController, ke method store, namenya adalah member.store
    Route::post('/member', [MemberController::class, 'store'])->name('member.store');
    // Route tipe dapatkan, ke url /member/ lalu kirimkan member_id lalu ke MemberController, ke method show, namenya adalah member.show
    Route::get('/member/{member_id}', [MemberController::class, 'show'])->name('member.show');
    // route tipe letakkan, ke url /member/ kirimkan member_id, ke MemberController, ke method update, namenya adalah member.update
    Route::put('/member/{member_id}', [MemberController::class, 'update'])->name('member.update');
    // route tipe kirim, ke url /member/destroy, ke MemberController, ke method destroy
    Route::post('/member/destroy', [MemberController::class, 'destroy'])->name('member.destroy');
    // route tipe kirim, ke url /member/cetak-kartu, ke MemberController, ke method cetak_kartu, namenya adalah member.cetak_kartu
    Route::post('/member/cetak-kartu', [MemberController::class, 'cetak_kartu'])->name('member.cetak_kartu');

    // Pengeluaran
    // route tipe dapatkan, ke url /pengeluaran, ke PengeluaranController ke method index, name nya adalah pengeluaran.index
    Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
    // route tipe dapatkan, ke url /pengeluaran/read, ke PengeluaranController, ke method read, name nya adalah pengeluaran.read
    Route::get('/pengeluaran/read', [PengeluaranController::class, 'read'])->name('pengeluaran.read');
    // route tipe kirim, ke url pengeluaran, ke PengeluaranController, ke method store, namenya adalah pengelauran.store
    Route::post('/pengeluaran', [PengeluaranController::class, 'store'])->name('pengeluaran.store');
    // route tipe dapatkan, ke url /pengeluaran/ kirimkan pengeluaran_id anggaplah berisi angka 1, ke PengeluaranController, ke method show, namenya adalah pengeluaran.show
    Route::get('/pengeluaran/{pengeluaran_id}', [PengeluaranController::class, 'show'])->name('pengeluaran.show');
    // route tipe letakkan, ke url /pengeluarn/ kirimkan pengeluaran_id anggaplah 1, ke PengeluaranController, ke method update, name nya adalah pengeluaran.update
    Route::put('/pengeluaran/{pengeluaran_id}', [PengeluaranController::class, 'update'])->name('pengeluaran.update');
    // Route tipe kirim, ke url /pengeluaran/destroy, ke PengeluaranController, ke method destroy, namenya adalah pengeluaran.destroy
    Route::post('/pengeluaran/destroy', [PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');
    // route tipe kirim, jika user di arahkan ke url /pengeluaran-import-excel, ke PengeluaranController, method import_excel, name nya adalah pengeluaran.import_excel
    Route::post('/pengeluaran-import-excel', [PengeluaranController::class, 'import_excel'])->name('pengeluaran.import_excel');
    // route tipe dapatkan, jika user diarahkan ke url /pengeluran-export-excel maka arahkan ke PengeluaranController, method export_excel, name nya adalah pengeluran.export_excel
    Route::get('/pengeluaran-export-excel', [PengeluaranController::class, 'export_excel'])->name('pengeluaran.export_excel');

    // pembelianz
    // route tipe dapatkan, ke url /pembelian, ke PembelianController, ke method index, name nya adalah pembelian.index
    Route::get('/pembelian', [PembelianController::class, 'index'])->name('pembelian.index');
    // route tipe dapatkan, ke url /pembelian/cek-penyuplai-dan-produk ke PembelianController, ke method cek_penyuplai_dan_produk, name nya adalah cek_penyuplai_dan_produk
    Route::get('/pembelian/cek-penyuplai-dan-produk', [PembelianController::class, 'cek_penyuplai_dan_produk'])->name('pembelian.cek_penyuplai_dan_produk');
    // route tipe dapatkan, ke url /pembelian/penyuplai, ke PembelianController, ke method penyuplai, name nya adalah pembelian.penyuplai
    Route::get('/pembelian/penyuplai', [PembelianController::class, 'penyuplai'])->name('pembelian.penyuplai');
    // route tipe store, ke url /pembelian, ke PembelianController, ke method store, name nya adalah pembelian.store
    Route::post('/pembelian', [PembelianController::class, 'store'])->name('pembelian.store');
    // route tipe dapatkan, ke url /pembelian/data ke PembelianController, ke method data, name nya adalah pemeblian.data
    Route::get('/pembelian/data', [PembelianController::class, 'data'])->name('pembelian.data');
    // route tipe dapatkan, ke url /pembelian/create/ lalu kirimkan penyuplai_id, ke PembelianContoller, ke method create, name nya adalah pembelian.create
    Route::get('/pembelian/create/{penyuplai_id}', [PembelianController::class, 'create'])->name('pembelian.create');
    // route tipe dapatkan ke url /pembelian/ kirimkan pembelian_id, ke PembelianContoller, method tampilkan_semua_pembelian_detail_terkait, name nya adalah pembelian.tampilkan_semua_pembelian_detail_terkait
    Route::get('/pembelian/{pembelian_id}', [PembelianController::class, 'tampilkan_semua_pembelian_detail_terkait'])->name('pembelian.tampilkan_semua_pembelian_detail_terkait');
    // route tipe hapus, ke url /pembelian/ kirimkan pembelian_id, ke PembelianController, ke method destroy, name nya adalah pembelian.destroy
    Route::delete('/pembelian/{pembelian_id}', [PembelianController::class, 'hapus'])->name('pembelian.hapus');
    // route tipe get, ke url /pembelian/ kirimkan pembelian_id, ke PembelianController, ke method kembali, name nya adalah pembelian.kembali
    Route::get('/pembelian/kembali/{pembelian_id}', [PembelianController::class, 'kembali'])->name('pembelian.kembali');
    // retur pembelian
    // route tipe dapatkan, jika user di arahkan ke url berikut maka kirimkan pembelian_id anggaplah 1 lalu arahkan ke PembelianController, method data_retur, namenya adalah pembelian.data_retur
    Route::get('/pembelian/data-retur/{pembelian_id}', [PembelianController::class, 'data_retur'])->name('pembelian.data_retur');
    // route tipe kirim, ke url /pembelian/retur-pembelian lalu kirimkan ke PembelianController, method retur_pembelian, name nya adalah pembelian.retur_pembelian
    Route::post('/pembelian/retur-pembelian', [PembelianController::class, 'retur_pembelian'])->name('pembelian.retur_pembelian');

    // pembelian detail
    // route tipe dapatkan, ke url /pembelian-deteail/data/ kirimkan pembelian_id, ke PembelianDetailController, ke method data, name nya adalah pembelian_detail.data
	Route::get('/pembelian-detail/data/{pembelian_id}', [PembelianDetailController::class, 'data'])->name('pembelian_detail.data');
    // route tipe dapatkan, ke url /pembelian-detail, ke PembelianDetailController, ke method index, name nya adalah pembelian_detail.index
    Route::get('/pembelian-detail', [PembelianDetailController::class, 'index'])->name('pembelian_detail.index');
    // route tipe dapatkan, ke url /pembelian-detail/produk lalu kirimkan penyuplai_id, ke PembelianDetailController, ke method produk, name nya adalah pembelian_detail.produk
    Route::get('/pembelian-detail/produk/{penyuplai_id}', [PembelianDetailController::class, 'produk'])->name('pembelian_detail.produk');
    // route tipe kirim, ke url /pembelian-detail, ke PembelianDetailController, ke method store, name nya adalah pembelian_detail.store
    Route::post('/pembelian-detail', [PembelianDetailController::class, 'store'])->name('pembelian_detail.store');
    Route::get('/pembelian-detail/reload-form/{total_harga}', [PembelianDetailController::class, 'reload_form'])->name('pembelian_detail.reload_form');
    // route tipe kirim, ke url /pembelian-detail/destroy/ lalu kirimkan pembelian_detail_id ke PembelianDetailController, ke method destroy, name nya adalah pembelian_detail.destroy
    Route::put('/pembelian-detail/{pembelian_detail_id}', [PembelianDetailController::class, 'update'])->name('pembelian_detail.update');
    Route::post('/pembelian-detail/destroy/{pembelian_detail_id}', [PembelianDetailController::class, 'destroy'])->name('pembelian_detail.destroy');

    

    // Laporan
    // route tipe dapatkam, jika user diarahkan ke url /laporan, ke LaporanController, method index, name nya adalah laporan.index
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    // route type get, url laporan tangkap dan kirimkan tanggal_awal dan tanggal_akhir
    Route::get('/laporan/{tanggal_awal}/{tanggal_hari_ini}', [LaporanController::class, 'data'])->name('laporan.data');
    // route tipe dapatkan, jika user di arahkan ke url /laporan/ubah-periode maka arahkan ke LaporanController, method ubah_periode, name nya adalah laporan.ubah_periode
    Route::get('/laporan/ubah-periode', [LaporanController::class, 'ubah_periode'])->name('laporan.ubah_periode');
    // route tipe dapatkan, jika user diarahkan ke url berikut maka tangkap dan kirimkan value tanggal_awal dan tanggal_hari_ini, lalu ke LaporanController, method cetak_pdf, name nya laporan.cetak_pdf
    Route::get('/laporan/cetak-pdf/{tanggal_awal}/{tanggal_hari_ini}', [LaporanController::class, 'cetak_pdf'])->name('laporan.cetak_pdf');
});
