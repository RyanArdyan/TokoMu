{{-- memperluas parentnya yaitu layouts.app --}}
@extends('layouts.app')

{{-- dorong css lalu tangkap mengguakan $stack('css') --}}
@push('css')
    {{-- buat element style --}}
    <style>
        /* menyembunyikan baris table / tr terakhir */
        /* panggil #table_penjualan_detail, tbody, tr, anak terakhir */
        /* tr terakhir berfungsi untuk diambil value jumlah dan subtotal nya lalu disimpan dalam input total_harga dan total_barang */
        #table_penjualan_detail tbody tr:last-child {
            /* tampilan: tidak-ada */
            /* display: none; */
        }

        ;
    </style>
@endpush

{{-- kirim value section title ke @Yield('title') --}}
@section('title', 'Penjualan Detail')

{{-- kirim value section konten ke @yield('konten') --}}
@section('konten')
    <!-- Content Wrapper. Contains page content -->
    <div class="row mb-2">
        <div class="col-md-12 mt-2">
            <div class="card">
                <div class="card-body">

                    {{-- termasuk ada jika modal penjualan_detail.modal_produk di panggil --}}
                    @includeIf('penjualan_detail.modal_produk')
                    {{-- termasuk ada jika modal penjualan_detail.modal_member di panggil --}}
                    @includeIf('penjualan_detail.modal_member')

                    <div class="row mb-2">
                        <div class="col-sm-6">

                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                {{-- jika tombol Pilih  Produk di click maka fungsi tampilkan_produk() --}}
                                <button id="tampilkan_produk" class="btn btn-success btn-sm">
                                    <i class="fa fa-shopping-bag"></i>
                                    Pilih Produk
                                </button>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->

                    {{-- termasuk penjualan_detail.form_produk --}}
                    @include('penjualan_detail.form_produk')

                    {{-- termasuk tampilan penjualan_detail.table_penjualan_detail --}}
                    @include('penjualan_detail.table_penjualan_detail')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-default">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-bullhorn"></i>
                                        Total Pembayaran
                                    </h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="callout callout-danger">
                                        <h1 id="total_pembayaran"></h1>

                                        <p id="total_pembayaran_format_terbilang">
                                        </p>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>

                        {{-- col-md-6 akan membuat 2 column --}}
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <!-- /.card-header -->
                                {{-- termasuk penjualan_detail.form_penjualan --}}
                                @include('penjualan_detail.form_penjualan')
                            </div>
                        </div>

                        {{-- jika tombol kembali di click maka arahkan ke halaman penjualan dan jika value column total milik detail penjualan masih 0 maka hapus penjualan terkait atau hapus penjualan berdasarkan penjualan_id --}}
                        {{-- ke route penjualan.kembali lalu kirimkan penjualan_id --}}
                        {{-- <a href="{{ route('penjualan.kembali', $penjualan_id) }}" class="btn btn-sm btn-danger">
                            <i class="fas fa-arrow-left"></i>
                            Kembali
                        </a> --}}
                        {{-- jika tombol Simpan penjualan di click maka Update detail penjualan --}}
                        <button id="tombol_simpan_penjualan" type="button" class="btn btn-sm btn-primary"><i
                                class="fa fa-save"></i> Simpan penjualan</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection


@push('script')
<script>
    // berisi panggil #table_penjualan_detail lalu gunakan datatable
    let table_penjualan_detail = $('#table_penjualan_detail').DataTable({
        // table nya akan responsive di mobile, table dan laptop
        responsive: true,
        // ketika data nya masih di muat, tampilkan animasi processing
        processing: true,
        // serverSisi: benar
        serverSide: true,
        // autoLebar: salah
        autoWidth: false,
        // lakukan ajax berisi object
        ajax: {
            // muat data-data table penjualan_detail berdasarkan penjualan_id  yang dikirimkan
            url: "{{ route('penjualan_detail.data', $penjualan_id) }}",
        },
        // jika kemudian berhasil
        columns: [
            {
                // DT_RowIndex adalah kode laravel datatables, berfungsi melakukan pengulangan
                // DT_RowIndex terhubung dengan addIndexColumn di controller
                data: 'DT_RowIndex',
            },
            {
                data: 'kode_produk'
            },
            {
                data: 'nama_produk'
            },
            {
                data: 'harga_jual'
            },
            {
                data: 'jumlah'
            },
            {
                data: 'subtotal'
            },
            {
                data: 'aksi',
            },
        ],
        // Tentukan elemen kontrol tabel yang akan muncul di halaman dan dalam urutan apa.
        dom: 'Brt',
        // hilangkan search dan fitur menampilkan 10/50 data
        bSort: false,
        // hilangkan fitur paginasi
        paginate: false
    })
    // Saat Anda melakukan tindakan seperti menambahkan atau menghapus baris, mengubah karakteristik pengurutan, pemfilteran, atau paging tabel, Anda ingin DataTables memperbarui tampilan untuk mencerminkan perubahan ini. Fungsi ini disediakan untuk tujuan itu.
    .on('draw.dt', function() {
        // panggil fungsi load, kirimkan value #diskon
        muat_ulang_form($('#diskon').val());
        setTimeout(() => {
            $('#uang_diterima').trigger('input');
        }, 300);
    });

    // table produk gunakan datatable
    $('#table_produk').DataTable();

    // table member gunakan datatable
    $(`#table_member`).DataTable();

    // hanya izinkan user memasukkan angka di input yang telah di tentukan
    function number(event) {
        let charCode = (event.which) ? event.which : event.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        } else {
            return true;
        };
    };

    // package Input Mask - Robin Herbots
    // aku perlu ini agar Rp 1.000 akan menjadi 1000 ketika sudah di controller
    // 1000 akan menjadi 1.000
    $(".input_angka").inputmask();

    // tampilkan modal pilih produk
    // jika #tampilkan_produk di click maka jalankan fungsi berikut
    $("#tampilkan_produk").on("click", function() {
        // panggil #modal_produk lalu modal nya di tampilkan
        $("#modal_produk").modal("show");
    });

    // jika .tombol_pilih_produk di click maka jalankan fungsi berikut
    $(".tombol_pilih_produk").on("click", function() {
        let penjualan_id = $("#penjualan_id").val();
        // berisi pangil .tombol_pilih_prdouk lalu ambil value attribute data-produk-id
        let produk_id = $(this).data('produk-id');
        // berisi panggil .tombol_pilih_produk lalu ambil value attribute data-kode-produk
        let kode_produk = $(this).data('kode-produk');

        // panggil #kode_produk lalu vaue nya diisi value variable produk_id
        $('#produk_id').val(produk_id);
        // panggil #kode_produk lalu value nya diisi value variable kode_produk
        $('#kode_produk').val(kode_produk);
        // panggil #modal_produk lalu modalnya di sembunyikan
        $('#modal_produk').modal('hide');

        // simpan data ke table pmebelian detail
        // jquery lakukan ajax, berisi object
        $.ajax({
            // url panggil route penjualan_detail.store
            url: "{{ route('penjualan_detail.store') }}",
            // panggil route tipe kirim
            type: "POST",
            // tajuk-tajuk berisi object
            headers: {
                // key X-CSRF-TOKEN berisi panggil element meta name="csrf_token" lalu ambil value attribute content
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            // kirimkan data berupa object
            data: {
                // key penjualan_id berisi value variable penjualan_id
                penjualan_id: penjualan_id,
                // key produk_id berisi value variable produk_id
                produk_id: produk_id,
                // key kode_produk berisi value variable kode_produk
                kode_produk: kode_produk  
            },
        })
        // jika selesai dan berhasil maka jalankan fungsi berikut lalu ambil tanggapan nya
        .done(function(resp) {
            // panggil #kode_produk lalu input nya di fokuskan
            $('#kode_produk').focus();
            // reload ajax pada table lalu jalankan fungsi panggil fungsi muat_ulang_form
            // panggil variable table_penjualan_detail lalu ajax nya di muat ulang lalu jalankan fungsi berikut lalu panggil fungsi muat_ulang_form lalu kirimkan value #diskon sebagai argument
            table_penjualan_detail.ajax.reload(() => muat_ulang_form($('#diskon').val()));
        })

    });

    // jika document di masukkan angka yang class nya adalah .jumlah maka jalankan fungsi berikut
    $(document).on('input', '.jumlah', function() {
        // panggil .jumlah, value dari attribute data-id
        let penjualan_detail_id = $(this).data('id');
        // ambil value dari input jumlah
        let jumlah = parseInt($(this).val());

        // jika value jumlah lebih kecil dari 1 berarti dimulai dari 0
        if (jumlah < 1) {
            // value input jumlah diisi dengan 1
            $(this).val(1);
            // tampilkan peringatan berikut
            Swal.fire('Jumlah tidak boleh kurang dari 1');
            // kode selesai dan berhenti
            return;
        }
        // lain jika jumlah lebih besar dari 1000
        else if (jumlah > 1000) {
            // panggil .jumlah lalu value nya diisi dengan 1000
            $(this).val(1000);
            // tampilkan peringatan berikut
            Swal.fire('Jumlah tidak boleh lebih dari 1.000');
            // kode selesai dan berhenti
            return;
        };
        // setelah 2 detik jalankan fungsi berikut
        setTimeout(function() {
            // lakukan ajax tipe kirim, ke url /penjualan-detail/ lalu kirimkan penjualan_detail_id
            $.post(`/penjualan-detail/${penjualan_detail_id}`, {
                // key _token berisi value dari element meta name="csrf_token", attribute content
                '_token': $('meta[name="csrf-token"]').attr('content'),
                // ubah method post menjadi put
                '_method': 'put',
                // kirikman name jumlah berisi value input jumlah
                'jumlah': jumlah
            })
            // jika selesai dan berhasil maka jalankan fungsi berikut dan kirimkan tanggapan nya
            .done(response => {
                // panggil table_penjualan_detail lalu ajax nya di muat ulang lalu jalankan fungsi berikut lalu panggil fungsi muat_ulang-form lalu kirimkan value #diskon
                table_penjualan_detail.ajax.reload(() => muat_ulang_form($('#diskon').val()));
            })
        }, 2000)
    });

    // jika #button_tampilkan_member di click maka jalankan fungsi berikut
    $("#button_tampilkan_member").on("click", function() {
        console.log('Oke');
        $(`#modal_member`).modal(`show`);
    });

    // tampilkan modal member dan datanya
    function tampilkan_member() {
    };



    // Jika aku memilih member di modal member
    function pilih_member(member_id, kode_member) {
        $('#member_id').val(member_id);
        $('#kode_member').val(kode_member);
        // diskon dari PenjualanDetailController, diskon didapatkan dari table setting
        $('#diskon').val("{{ $diskon }}");
        // panggil funggsi muat_ulang_form kirimkan value #diskon
        muat_ulang_form($('#diskon').val());
        // #diterima diisi 0, lalu focuskan
        $('#uang_diterima').val(0).focus().select();
        // sembunyikan modal member
        $('#modal_member').modal('hide');
    };

    // ada nilai bawaan, jadi jika aku tidak mengirim argumen maka tidak akan error
    function muat_ulang_form(diskon = 0, uang_diterima = 0) {
        let total_harga = $(".total_harga").text();
        let total_barang = $(".total_barang").text();

        // .total_harga dan #total_barang di dapatkan dari PenjualanDetailController, method data
        // panggil #total_harga lalu value nya di ambil dari text dari .total_harga
        $('#total_harga').val(total_harga);
        // panggil #total_barang lalu value nya di ambil dari text .total_barang
        $('#total_barang').val(total_barang);

        // kirim data lewat url
        // lakukan ajax tipe dapatkan, panggil url berikut lalu kirimkan 3 argument
        $.get(`/penjualan-detail/muat-ulang-form/${diskon}/${total_harga}/${uang_diterima}`)
            // jika selesai dan berhasil maka jalankan arrow function dambil tangapan nya
            .done(response => {
                // panggil #total_rp lalu value nya diisi value response.data.total_rp
                $('#total_rp').val(response.data.total_rp);
                $('#bayar_rp').val(response.data.bayar_rp);
                $('#harus_bayar').val(response.data.harus_bayar);
                $('#total_pembayaran').text('Bayar: ' + response.data.bayar_rp);
                $('#total_pembayaran_format_terbilang').text(response.data.terbilang);
                $('#uang_kembalian_pelanggan').val(response.data.kembali_rp);

                // jika value dari #uang_diterima tidak sama dengan 0 maka
                if ($('#uang_diterima').val() != 0) {
                    // panggil #total_pemabayan lalu text nya diisi string bayar di tambah value dari response.data.bayar_rp
                    $('#total_pembayaran').text('Bayar: ' + response.data.bayar_rp);
                    // panggil #total_pembayaran_format_terbilang lalu text nya diisi response.data.terbilang
                    $('#total_pembayaran_format_terbilang').text(response.data.terbilang);
                };
            });
    };

    // hapus 1 baris data dari table penjualan_detail
    function hapus_data(url) {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Anda tidak akan dapat mengembalikkan ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(url, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'delete'
                    })
                    .done((response) => {
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        );
                        table_penjualan_detail.ajax.reload(() => muat_ulang_form($('#diskon').val()));
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menghapus data karena kesalahan code.');
                        return;
                    });

            }
        })
    };

    // Jika input uang diterima dimasukkan angka atau data maka jalnkan fungsi berikut
    $('#uang_diterima').on('input', function() {
        // jika input uang diterima, valuenya ada lalu dihapus sampai kosong maka
        if ($(this).val() === "") {
            // input uang diterima diisi dengan 0 lalu ada efek pilih
            $(this).val(0).select();
        };
        // panggil fungsi muat_ulang_form, kirimkan value dari #diskon dan value dari input uang diterima
        muat_ulang_form($('#diskon').val(), $(this).val());
        // jika focus maka jalankan fungsi
    }).focus(function() {
        // input uang diterima kita seleksi valuenya
        $(this).select();
    });

    // Perbarui detail penjualan
    // jika #tombol_simpan_penjualan di click maka jalankan fungsi berikut
    $('#tombol_simpan_penjualan').on('click', function() {
        // berisi text dari .total_barang yang di buat di PenjualanDetailController, method data
        let total_barang = $(".total_barang").text();
        // jika total_barang sama dengan "0" karena user memilih produk, tipe datanya string bukan integer
        if (total_barang === "0") {
            // tampilkan notifikasi berisi pesan berikut
            Swal.fire('Silahkan pilih produk terlebih dahulu.');
        }
        // lain jika total_barang tidak sama dengan "0" karna user sudah memilih produk dan tidak menghapus semua produk di halaman penjualan detail, misalnya total_barang berisi "10" maka
        else if (total_barang !== "0") {
            $("#form_penjualan").submit();            
        };
    });
</script>
@endpush
