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
use App\Http\Controllers\ProdukPenyuplaiController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PembelianDetailController;
use App\Http\Controllers\ReturPembelianController;

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



// middleware untuk yag sudah login, auth di dapatkan dari App/Http/Kernel.php
Route::middleware(['auth'])->group(function() {
    // dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // logout
    // route tipe dapatkan, ke url logout, ke controller dan method berikut, dan punya name 
	Route::get('/logout', [AutentikasiController::class, 'logout'])->name('logout');

    // edit profile
    Route::get('/edit-profile', [EditProfileController::class, 'edit'])->name('edit_profile');
    Route::post('/edit-profile/perbarui', [EditProfileController::class, 'update'])->name('update_profile');
    // Route tipe post, arahakn user ke url berikut, lalu ke controller dan method berikut, dan ada namenya
    Route::post('/edit-profile/update-password', [EditProfileController::class, 'update_password'])->name('edit_profile.update_password');
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
    Route::get('/produk-dan-relasinya', [ProdukController::class, 'produk_dan_relasinya'])->name('produk_dan_relasinya');
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

    // produk penyuplai
    // route tipe dapatkan ke url /produk-penyuplai, ke ProdukPenyuplaiController, ke method index, name nya adalah produk_penyuplai.index
    Route::get('/produk-penyuplai', [ProdukPenyuplaiController::class, 'index'])->name('produk_penyuplai.index');
    // route tipe dapatkan, ke url /produk-penyupalai/read, ke ProdukPenyuplaiController, ke method read, name nya adalah produk_penyuplai.read
    Route::get('/produk-penyuplai/read', [ProdukPenyuplaiController::class, 'read'])->name('produk_penyuplai.read');
    // Untuk menampilkan semua kategori dan penyuplai di modal tambah Produk Penyuplai
    // route tipe dapatkan ke url /produk-penyuplai/data-relasinya, ke ProdukPenyupaliController, ke method data_relasinya, name nya adalah produk_penyuplai.data_relasinya
    Route::get('/produk-penyuplai/data-relasinya', [ProdukPenyuplaiController::class, 'data_relasinya'])->name('produk_penyuplai.data_relasinya');
    // route tipe kirim, ke url produk-penyuplai, ke ProdukPenyuplaiController, ke method store, name nya adalah produk_penyuplai_store
    Route::post('/produk-penyuplai', [ProdukPenyuplaiController::class, 'store'])->name('produk_penyuplai.store');
    // jangan simpan route ini di bawah route produk_penyuplai.show karena nanti route sialan itu akan menimopa route di bawah ini
    // route tipe dapaatkan, ke url /produk-penyuplai/cek-kategori-dan-penyuplai, ke ProdukPenyuplaiController, ke method cek_kategori_dan_penyuplai, name nya adalah produk_penyuplai.cek_kategori_dan_penyuplai
    Route::get('/produk-penyuplai/cek-kategori-dan-penyuplai', [ProdukPenyuplaiController::class, 'cek_kategori_dan_penyuplai'])->name('produk_penyuplai.cek_kategori_dan_penyuplai');
    // route tipe dapatkan, ke url /produk-penyuplai/ kirimkan produk_id, ke ProdukPenyuplaiController, ke method show, name nya adalah produk_penyuplai.show
    Route::get('/produk-penyuplai/{produk_penyuplai_id}', [ProdukPenyuplaiController::class, 'show'])->name('produk_penyuplai.show');
    // route tipe letakkan, ke url /produk-penyuplai/ kirimkan produk_penyuplai_id, ke ProdukPenyuplaiController, ke method update, name nya adalah produk_penyuplai.update
    Route::put('/produk-penyuplai/{produk_penyuplai_id}', [ProdukPenyuplaiController::class, 'update'])->name('produk_penyuplai.update');
    // route tipe kirim, ke url /produk-penyuplai/destroy, ke ProdukPenyuplaiController, ke method destroy, name nya adalah produk_penyuplai.destroy
    Route::post('/produk-penyuplai/destroy', [ProdukPenyuplaiController::class, 'destroy'])->name('produk_penyuplai.destroy');
    

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

    // pembelianz
    // route tipe dapatkan, ke url /pembelian, ke PembelianController, ke method index, name nya adalah pembelian.index
    Route::get('/pembelian', [PembelianController::class, 'index'])->name('pembelian.index');
    // route tipe dapatkan, ke url /pembelian/cek-penyuplai-dan-produk-penyuplai ke PembelianController, ke method cek_penyuplai_dan_produk_penyuplai, name nya adalah cek_penyuplai_dan_produk_penyuplai
    Route::get('/pembelian/cek-penyuplai-dan-produk-penyuplai', [PembelianController::class, 'cek_penyuplai_dan_produk_penyuplai'])->name('pembelian.cek_penyuplai_dan_produk_penyuplai');
    // route tipe dapatkan, ke url /pembelian/penyuplai, ke PembelianController, ke method penyuplai, name nya adalah pembelian.penyuplai
    Route::get('/pembelian/penyuplai', [PembelianController::class, 'penyuplai'])->name('pembelian.penyuplai');
    // route tipe store, ke url /pembelian, ke PembelianController, ke method store, name nya adalah pembelian.store
    Route::post('/pembelian', [PembelianController::class, 'store'])->name('pembelian.store');
    // route tipe dapatkan, ke url /pembelian/data ke PembelianController, ke method data, name nya adalah pemeblian.data
    Route::get('/pembelian/data', [PembelianController::class, 'data'])->name('pembelian.data');
    // route tipe dapatkan, ke url /pembelian/create/ lalu kirimkan penyuplai_id, ke PembelianContoller, ke method create, name nya adalah pembelian.create
    Route::get('/pembelian/create/{penyuplai_id}', [PembelianController::class, 'create'])->name('pembelian.create');
    // route tipe dapatkan ke url /pembelian/ kirimkan pembelian_id, ke PembelianContoller, method show, name nya adalah pembelian.show
    Route::get('/pembelian/{pembelian_id}', [PembelianController::class, 'show'])->name('pembelian.show');
    // route tipe hapus, ke url /pembelian/ kirimkan pembelian_id, ke PembelianController, ke method destroy, name nya adalah pembelian.destroy
    Route::delete('/pembelian/{pembelian_id}', [PembelianController::class, 'hapus'])->name('pembelian.hapus');
    // pembelian detail
    // route tipe dapatkan, ke url /pembelian-deteail/data/ kirimkan pembelian_id, ke PembelianDetailController, ke method data, name nya adalah pembelian_detail.data
	Route::get('/pembelian-detail/data/{pembelian_id}', [PembelianDetailController::class, 'data'])->name('pembelian_detail.data');
    // route tipe dapatkan, ke url /pembelian-detail, ke PembelianDetailController, ke method index, name nya adalah pembelian_detail.index
    Route::get('/pembelian-detail', [PembelianDetailController::class, 'index'])->name('pembelian_detail.index');
    // route tipe dapatkan, ke url /pembelian-detail/produk-penyuplai lalu kirimkan penyuplai_id, ke PembelianDetailController, ke method produk_penyuplai, name nya adalah pembelian_detail.produk_penyuplai
    Route::get('/pembelian-detail/produk-penyuplai/{penyuplai_id}', [PembelianDetailController::class, 'produk_penyuplai'])->name('pembelian_detail.produk_penyuplai');
    // route tipe kirim, ke url /pembelian-detail, ke PembelianDetailController, ke method store, name nya adalah pembelian_detail.store
    Route::post('/pembelian-detail', [PembelianDetailController::class, 'store'])->name('pembelian_detail.store');
    Route::get('/pembelian-detail/reload-form/{total_harga}', [PembelianDetailController::class, 'reload_form'])->name('pembelian_detail.reload_form');
    // route tipe kirim, ke url /pembelian-detail/destroy/ lalu kirimkan pembelian_detail_id ke PembelianDetailController, ke method destroy, name nya adalah pembelian_detail.destroy
    Route::put('/pembelian-detail/{pembelian_detail_id}', [PembelianDetailController::class, 'update'])->name('pembelian_detail.update');
    Route::post('/pembelian-detail/destroy/{pembelian_detail_id}', [PembelianDetailController::class, 'destroy'])->name('pembelian_detail.destroy');
    // route tipe get, ke url /pembelian/ kirimkan pembelian_id, ke PembelianController, ke method kembali, name nya adalah pembelian.kembali
    Route::get('/pembelian/kembali/{pembelian_id}', [PembelianController::class, 'kembali'])->name('pembelian.kembali');
    // retur pembelian
    // route tipe kirim, ke url /pembelian/retur, ke PembelianController, method retur_pembelian, name nya adalah pembelian.retur_pembelian
    Route::post('/pembelian/retur', [PembelianController::class, 'retur_pembelian'])->name('pembelian.retur_pembelian');
});
