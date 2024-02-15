{{-- memperluas parentnya yaitu layouts.app --}}
@extends('layouts.app')

{{-- dorong css lalu tangkap menggunakan $stack('css') --}}
@push('css')
    {{-- buat element style --}}
    <style>
    </style>
@endpush

{{-- kirim value section title ke @yield('title') --}}
@section('title', 'Penjualan')

{{-- kirim value section konten ke @yield('konten') --}}
@section('konten')
    <!-- Content Wrapper. Contains page content -->
    <div class="row mb-2">
        <div class="col-md-12 mt-2">
            <div class="mb-3">
                {{-- Jika tombol Penjualan Baru di click maka arahkan ke route penjualan_detail.index --}}
                <a href="{{ route('penjualan_detail.index') }}" class="btn btn-primary btn-sm">
                    <i class="mdi mdi-plus"></i> Penjualan Baru
                </a>
                <button id="tombol_ubah_periode" class="btn btn-sm btn-success">
                    <i class="mdi mdi-file-excel"></i> Export Excel</button>
                <button id="tombol_tutup_penjualan" class="btn btn-sm btn-success">
                    <i class="dripicons-store"></i> Tutup Penjualan</button>
            </div>

            {{-- termasuk element table dan form --}}
            @include('penjualan.table')
            {{-- jika tombol lihat detail penjualan di click maka panggil views/penjualan/modal_detail --}}
            {{-- termasuk ada jika penjualan.detail di panggil --}}
            @includeIf('penjualan.modal_detail')
            {{-- termasuk ada jika modal penjualan.modal_retur dipanggil --}}
            @includeIf('penjualan.modal_retur')
            {{-- termasuk ada jika modal_ubah_periode di panggil --}}
            @includeIf('penjualan.modal_ubah_periode')
            {{-- termasuk ada jika formulir_tutup_penjualan di panggil --}}
            @includeIf('penjualan.formulir_tutup_penjualan')
        </div>
    </div>
@endsection


@push('script')
<script>

// // inisialisasi vaariable
// let tanggal_awal, tanggal_akhir;

// // Fungsi pemfilteran khusus yang akan mencari data di kolom empat antara dua nilai
// // DataTable.ext.search.push(function (settings, data, dataIndex) {...}): Ini adalah cara untuk menambahkan fungsi pencarian kustom ke DataTables. Fungsi ini akan dipanggil untuk setiap baris dalam tabel, dan jika fungsi mengembalikan true, baris tersebut akan ditampilkan; jika false, baris tersebut akan disembunyikan.
// DataTable.ext.search.push(function (settings, data, dataIndex) {
//     // berisi ambil value dari variable tanggal_awal
//     let awal = tanggal_awal.val();
//     let akhir = tanggal_akhir.val();
//     // Ini mengubah data dalam kolom kelima (indeks dimulai dari 0, jadi [4] adalah kolom kelima) dari baris saat ini menjadi objek Date.
//     let date = new Date(data[4] );
//     if (
//         // Blok if berikutnya memeriksa apakah tanggal dalam baris saat ini berada dalam rentang tanggal yang ditentukan oleh awal dan akhir. Jika ya
//         (awal === null && akhir === null) ||
//         (awal === null && date <= akhir) ||
//         (awal <= date && akhir === null) ||
//         (awal <= date && date <= akhir)
//     ) {
//         // maka fungsi mengembalikan true dan baris tersebut ditampilkan.
//         return true;
//     }
//     // Jika tidak, fungsi mengembalikan false dan baris tersebut disembunyikan.
//     return false;
// });

// // buat masukkan tanggal awal, panggil #tanggal_awal
// tanggal_awal = new DateTime('#tanggal_awal', {
//     //  adalah objek opsi yang menentukan format tanggal yang diinginkan.
//     format: 'MMMM Do YYYY'
// });

// // buat masukkan tanggal akhir
// tanggal_akhir = new DateTime('#tanggal_akhir', {
//     format: 'MMMM Do YYYY'
// });

// panggil input name="daterange", lalu ubah input nya menjadi pemilih rentang tanggal menggunakan plugin daterangefilter
$('input[name="daterange"]').daterangepicker({
    // Ini mengatur tanggal awal pemilih rentang tanggal menjadi satu bulan sebelum tanggal saat ini. Fungsi moment().subtract(1, 'M') menggunakan library Moment.js untuk menghitung tanggal tersebut.
    startDate: moment().subtract(1, 'M'),
    // ini mengatur tanggal menjadi tanggal hari ini
    endDate: moment()
});

// table penjualan gunakan datatable
let table_penjualan = $('#table_penjualan').DataTable({
    // table nya akan responsve di mobile, table dan laptop
    responsive: true,
    // ketika datanya masih dimuat maka tampilkan animasi processing
    processing: true,
    // serverSisi: benar
    serverSide: true,
    // autoLebar: salah
    autoWidth: false,
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    // lakukan ajax, berisi object
    ajax: {
        // url memanggil route penjualan.data
        url: "{{ route('penjualan.data') }}",
        // kirimkan data, jalankan fungsi, Fungsi ini menerima satu argumen, d, yang merupakan objek data yang akan dikirim ke server.
        data:function (d) {
            // objek d, buat properti dari_tanggal berisi panggil input name="daterange", lalu dapatkan instance dari plugin daterangepicker
            // .format('YYYY-MM-DD'): Ini adalah metode dari library Moment.js yang digunakan untuk mengubah format tanggal menjadi ‘YYYY-MM-DD’.
            d.from_date = $('input[name="daterange"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
            d.to_date = $('input[name="daterange"]').data('daterangepicker').endDate.format('YYYY-MM-DD');
        },
    },
    // jika kemudian berhasil
    columns: [
        // DT_RowIndex didapatkan dari laravel datatables
        // searchable berarti nomornya tidak dapat dicari
        // sortable berarti anak panah akan hilang
        {data: 'DT_RowIndex', searchable: false, sortable: false},
        {data: 'tanggal'},
        {data: 'kode_member'},
        {data: 'total_barang'},
        {data: 'total_harga'},
        {data: 'diskon'},
        {data: 'harus_bayar'},
        {data: 'kasir'},
        {data: 'aksi', searchable: false, sortable: false},
    ]
});

// class filter ketika di click maka jalankan fungsi berikut
$(".filter").click(function() {
    // table_penjualan di gambar ulang atau di refresh
    table_penjualan.draw();
});

// // Filter ulang table
// // document.querySelectorAll('#tanggal_awal, #tanggal_akhir'): Fungsi ini mencari semua elemen dalam dokumen yang memiliki id tanggal_awal atau tanggal_akhir. Hasilnya adalah NodeList, yang mirip dengan array.
// // .foreach berarti melakukan pengulangan terhadap kedua input tersebut dan kemudian parameter el akan mengambil setiap input
// document.querySelectorAll('#tanggal_awal, #tanggal_akhir').forEach((el) => {
//     // jika setiap input diubah maka tambahkan acara pendengar yaitu ubah lalu jalankan fungsi berikut lalu panggil value variable table_penjualan, lalu draw itu berarti refresh
//     el.addEventListener('change', () => table_penjualan.draw());
// });

// berisi table penjualan_detail gunakan datatable
let table_penjualan_detail = $('#table_penjualan_detail').DataTable({
    // attribute url tidak ada, ada setelah aku click tombol detail lalu panggil fungsi tampilkan_detail_penjualan
    // ketika data nya masih di muat maka tampilkan animasi processing
    processing: true,
    // dua baris kode dibawah akan menghilangkan fitur search, paginasi dan pilihan menanmpilkan beberapa data
    bSort: false,
    dom: 'Brt',
    // membuat element tbody, tr, td lalu mengambil data dari PenjualanController, method show
    columns: [
        // DT_RowIndex didapatkan dari laravel datatables, addIndexColumn
        // lakukan pengulangan nomor
        {data: 'DT_RowIndex'},
        // pengulangan kode_produk
        {data: 'kode_produk'},
        {data: 'nama_produk'},
        {data: 'harga_jual'},
        {data: 'jumlah'},
        {data: 'subtotal'},
    ]
});

// table retur
// berisi #table_retur gunakan datatable
let table_retur = $("#table_retur_penjualan_detail").DataTable({
    // kode ini belum dijalankan karena attribute url nya belum ada, jadi kode ini baru akan dijalankan setelah aku mengirimkan attribute url menggunakan fungsi data_retur
    processing: true,
    // menghilangkan fitur pencarian, pagination dan memesan jumlah data
    bsort: false,
    dom: 'Brt',
    columns: [
        // looping nomor
        {
            data: 'DT_RowIndex',
            // nonaktifkan fitur bolak-balik data atau icon anak panah
            sortable: false
        },
        {
            data: 'nama_produk'
        },
        {
            data: 'jumlah'
        },
        {
            data: 'keterangan'
        },
        {
            data: 'action'
        }
    ]
});

// tampilkan data detail-detail penjualan
function tampilkan_detail_penjualan(url) {
    // panggil variable table_penjual_detail lalu buat attribute url lalu kirimkan url
    table_penjualan_detail.ajax.url(url);
    table_penjualan_detail.ajax.reload();
    // tampilkan modal
    // panggil #modal_detail lalu modal nya di tampilkan
    $('#modal_detail').modal('show');
};

// fungsi hapus detail penjualan dan semua penjualan_detail terkait nya
function hapus(url) {
    // tampilkan dialog menggunakan sweetlaert 2
    Swal.fire({
        // key judul berisi value berikut
        title: 'Apakah anda yakin?',
        // text: ""
        text: "Anda tidak akan dapat mengembalikkan ini!",
        // icon: "peringatan"
        icon: 'warning',
        // tampilBatalTombol: benar
        showCancelButton: true,
        // konfirmasiTombolWarna: ''
        confirmButtonColor: '#3085d6',
        // batalTombolWarna: ''
        cancelButtonColor: '#d33',
        // konfirmasiTombolText
        confirmButtonText: 'Ya, hapus!'
    })
    // .kemudian((hasll) jalankan fungsi berikut {})
    .then((result) => {
    // jika hasilnya di konfirmasi
    if (result.isConfirmed) {
        // lakukan ajax tipe kirim, lalu panggil route yang disimpan di parameter url, object
        $.post(url, {
            // key _token bersi melakukan keamanan dari serangan CSRF
            '_token': $('[name=csrf-token]').attr('content'),
            // panggil route tipe atau method delete
            '_method': 'delete'
        })
        // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapan lewat parameter fungsi
        .done((response) => {
            // jika value tanggapan.status sama dengan 200 maka
            if (response.status === 200) {
                // variable table_penjualan, ajax nya di muat ulang
                table_penjualan.ajax.reload();
            };
        });
    }
    });
};

// Menampilkan data retur terkait jika tombol retur penjualan di click
// fungsi data_retur, parameter berisi route_laravel
function data_retur(route_laravel, penjualan_id) {
    // panggil #modal_retur modalnya di tampilkan
    $("#modal_retur").modal("show");
    // panggil variable table_retur buat panggilan ajax route_laravel, berisi value parameter route_laravel
    table_retur.ajax.url(route_laravel);
    // table_retur, ajax nya, di muat ulang
    table_retur.ajax.reload();
};

// retur penjualan, ada 3 parameter
function retur_penjualan(penjualan_detail_id, produk_id, penjualan_id) {
    // berisi ambil value dari anggaplah .jumlah_retur_1 dari PenjualanController, method data retur bagian jumlah
    let jumlah_retur = $(`.jumlah_retur_${produk_id}`).val();
    let keterangan = $(`.keterangan_${produk_id}`).val();
    // lakukan ajax
    $.ajax({
        // url panggil route berikut
        url: "{{ route('penjualan.retur_penjualan') }}",
        // panggil route tipe post
        type: "POST",
        // laravel mewajibkan keamanan dari serangan csrf
        headers: {
            // berisi ambil value tag meta name="csrf_token", attribute content
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        // hapus validasi error sebelum value input-input di kirim
        // sebelum kirim, jalankan fungsi berikut
        beforeSend: function() {
            // anggaplah panggil .input_1 lalu hapus class is-invalid
            $(`.input_${produk_id}`).removeClass("is-invalid");
            // anggaplah panggil .pesan_error_1 lalu textnya di kosongkan
            $(`.pesan_error_${produk_id}`).text("");
        },
        // kirimkan data yang akan di tangkap parameter $request, method retur_penjualan
        data: {
            // key penjualan_id berisi value parameter penjualan_id
            penjualan_id: penjualan_id,
            penjualan_detail_id: penjualan_detail_id,
            produk_id: produk_id,
            jumlah_retur: jumlah_retur,
            keterangan: keterangan
        }
    })
    // jika selesai dan berhasil maka ambil tanggapan nya
    .done(function(resp) {
        // jika validasi input nya error
        // jika value dari resp.status sama dengan 0
        if (resp.status === 0) {
            // lakukan pengulangan
            // key berisi semua nilai name.
            // value berisi array yang menyimpan semua pesan error
            $.each(resp.errors, function(key, value) {
                // anggaplah panggil .jumlah_retur_1 lalu tambah .is-invalid
                $(`.${key}_${produk_id}`).addClass("is-invalid");
                $(`.${key}_error_${produk_id}`).text(value[0]);
            });
        };
        // jika retur pembelian nya berhasil alias kode nya tidak ada error
        if (resp.status === 200) {
            // table_retur, ajax, muat ulang
            table_retur.ajax.reload();
            // berikan notifikasi berdasarkan resp.message
            toastr.success(resp.message);
        };
    });
};

//  if #change_period button is clicked then run following function
// jika #tombol_ubah_periode di click maka jalankan fungsi berikut
$("#tombol_ubah_periode").on("click", function() {
    // call #modal_ubah_period then the modal is displayed
    // panggil #modal_ubah_periode lalu modal nya di tampilkan
    $("#modal_ubah_periode").modal("show");
});


// jika #tombol_tutup_penjualan di click maka jalankan fungsi berikut
$("#tombol_tutup_penjualan").on("click", function() {
    // console.log("Kau sudah dibenci");

    // moment.locale("id");

    // Bagian ini menggunakan library moment.js untuk membuat instance objek moment yang merepresentasikan tanggal dan waktu. Fungsi set digunakan untuk mengubah spesifik komponen tanggal dan waktu dari objek moment tersebut.
    const jam_7 = moment().set({
        // menyetel ke jam 7
        hour: 7,
        // menyetel ke menit 0
        minute: 0,
        // menyetel ke detik 0
        second: 0,
    });

    // Bagian ini menggunakan fungsi format pada jam_7 moment untuk memformat tanggal dan waktu menjadi string.
    const waktu_jam_7 = jam_7.format("YYYY-MM-DD HH:mm:ss");

    // inisialisasi objek moment
    const objek_moment = moment();
    // mengatur timezone lokal nye ke indonesia
    objek_moment.locale("id");
    // untuk mendapatkan tahun-bulan-tanggal jam:menit:detik
    const waktu_tutup = objek_moment.format("YYYY-MM-DD HH:mm:ss");

    // tampilkan notiifikasi
    Swal.fire({
        // judul:
        title: "Bikin laporan menggunakan?",
        // tampilkanTombolTolak
        showDenyButton: true,
        // tampilkanTombolBatal
        showCancelButton: true,
        // textTombolKonfirmasi
        confirmButtonText: "PDF",
        // textTombolTolak
        denyButtonText: `EXCEL`
    })
    // kemudian jalankan fungsi berikut kemudian ambil hasilnya yaitu pilihan tombol yang ditekan user seperti "PDF" atau "EXCEL"
    .then((result) => {
        // jika hasilnya dikonfirmasi atau "PDF" maka
        if (result.isConfirmed) {
            // panggil fungsi berikut lalu kirimkan argument
            tangani_setelah_dapat_hasil_pilihan("PDF");
        }
        // jika hasilnya ditolak atau "EXCEL" maka
        else if (result.isDenied) {
            tangani_setelah_dapat_hasil_pilihan("EXCEL");

        };
    });

    // mendefinisikan fungsi tangani_setelah_dapat_hasil_pilihan, ada 1 parameter yaitu hasil_pilihan untuk menangkap argument
    function tangani_setelah_dapat_hasil_pilihan(hasil_pilihan) {
        // panggil #waktu_jam_7 lalu diisi dengan value variable waktu_jam_7
        $("#waktu_jam_7").val(waktu_jam_7);
        $("#waktu_tutup").val(waktu_tutup);
        // panggil #hasil_pilihan lalu value nya diisi value parameter hasil_pilihan
        $("#hasil_pilihan").val(hasil_pilihan);
        // panggil #form_tutup_penjualan lalu kita kirim dan ada reloadnya
        $("#form_tutup_penjualan").submit();
    };


});
</script>
@endpush
