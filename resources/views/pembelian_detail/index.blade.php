{{-- memperluas parentnya yaitu layouts.app --}}
@extends('layouts.app')

{{-- dorong css lalu tangkap mengguakan $stack('css') --}}
@push('css')
    {{-- buat element style --}}
    <style>
        /* menyembunyikan baris table / tr terakhir */
        /* panggil #table_pembelian_detail, tbody, tr, anak terakhir */
        /* #table_pembelian_detail tbody tr:last-child {
            display: none;
        }; */
        /* tampilan: tidak ada */
    </style>
@endpush

{{-- kirim value section title ke @Yield('title') --}}
@section('title', 'Pembelian Detail')

{{-- kirim value section konten ke @yield('konten') --}}
@section('konten')
    <!-- Content Wrapper. Contains page content -->
    <div class="row mb-2">
        <div class="col-md-12 mt-2">
            <div class="card">
                <div class="card-body">

                    {{-- termasuk ada jika modal pembelian_detail.modal_produk_penyuplai di panggil --}}
                    @include('pembelian_detail.modal_produk_penyuplai')

                    {{-- termasuk view pembelian_detail.form_produk_penyuplai --}}
                    @include('pembelian_detail.form_produk_penyuplai')

                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <div class="invoice-col mb-3">
                                Penyuplai: <b>{{ $detail_penyuplai->nama_penyuplai }}</b><br>
                                Telepon: <b>{{ $detail_penyuplai->telepon_penyuplai }}</b><br>
                                Alamat: <b>{{ $detail_penyuplai->alamat_penyuplai }}</b>
                            </div>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <button id="tombol_tampilkan_modal_produk_penyuplai" class="btn btn-success btn-sm"><i
                                        class="fa fa-shopping-bag"></i>
                                    Pilih Produk Penyuplai</button>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->

                    {{-- termasuk tampilan pembelian_detail.table_pembelian_detail --}}
                    @include('pembelian_detail.table_pembelian_detail')

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
                                        {{-- <h1 id="total_pembayaran">{{ rupiah_bentuk($detail_total_harga_pembelian) }}</h1> --}}
                                        <h1 id="total_pembayaran">{{ rupiah_bentuk($detail_total_harga_pembelian) }}</h1>

                                        <p id="total_pembayaran_format_terbilang">
                                            {{ terbilang($detail_total_harga_pembelian) }}</p>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>

                        <div class="col-md-6">
                            <div class="card card-primary">
                                <!-- /.card-header -->
                                {{-- termasuk pembelian_detail.form_pembelian --}}
                                @include('pembelian_detail.form_pembelian')
                            </div>
                        </div>
                    </div>

                    <div class="form-check mt-1">
                        {{-- Hapus --}}
                        <button id="tombol_hapus" type="button" class="btn btn-danger btn-flat btn-sm"><i
                                class="fa fa-trash"></i>
                            Hapus</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection


@push('script')
<script>
    // panggil data table produk_penyuplai yang terkait dengan penyuplai yang dipilih
    // panggil #table_produk_penyuplai lalu gunakan datatable
    $("#table_produk_penyuplai").DataTable({
        // Jika data produk_penyuplai sedang dimuat maka tampilkan processing nya dulu
        processing: true,
        // Jika data sudah lebih dari 10.000 maka tidak lemot karena serverSide nya true
        // sisi server: benar
        serverSide: true,
        // lakukan ajax, dan panggil route pembelian_detail.produk
        ajax: "{{ route('pembelian_detail.produk_penyuplai', $detail_penyuplai->penyuplai_id) }}",
        // buat tbody, tr dan td lalu isi datanya
        columns: [
            {
                // pengulangan nomor
                // DT_RowIindex didapatkan dari laravel datatable
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                // sortable: false berarti akan menghilangkan icon anak panah atau menghilangkan fitur balik data dari Z-A
                sortable: false
            },
            {
                data: 'nama_produk',
                name: 'nama_produk'
            },
            {
                data: 'harga',
                name: 'harga'
            },
            // panggil tombol pilih produk_penyuplai
            {
                data: 'action',
                name: 'action',
                // sortable: false berarti akan menghilangkan icon anak panah atau menghilangkan fitur balik data dari Z-A
                sortable: false,
                // menghilangkan fitur cari di column pilih produk_penyuplai
                searchable: false
            }
        ],
        // gunakan bahasa indonesia di datatable
        language: {
            // url panggil folder public/terjemahan_datatable/indonesia.json
            url: "/terjemahan_datatable/indonesia.json"
        }
    });

    // misalnya, table pembelian, column pembelian_id berisi angka 1, maka panggil semua data table pembelian_detail yang value column pembelian_id nya berisi angka 1
    // panggil #table_pembelian_detail lalu gunakan datatable
    let table_pembelian_detail = $("#table_pembelian_detail").DataTable({
            // table nya akan responsive
            // responsive: benar
            responsive: true,
            // ketika data di table pembelian_detail sedang di muat maka tampilkan animasi processing
            // processing: benar
            processing: true,
            // autoLebar: matikan
            autoWidth: false,
            // Web tidak akan lemot ketika data table pembelian_eetail sudah lebih dari 10.000 karena serverSide nya true
            // server sisi: benar
            serverSide: true,
            // ajax memanggil route pembelian_detail.data
            ajax: "{{ route('pembelian_detail.data', $pembelian_id) }}",
            // jika kemudian berhasil maka jalankan fungsi berikut
            // akan membuat elemnent tbody, tr dan td
            columns: [
                // membuat pengulangan nomor
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    // menghilangkan icon anak panah atau mematikan fitur balik data dari Z ke A
                    sortable: false
                },
                {
                    data: 'nama_produk',
                    name: 'nama_produk.nama_produk'
                },
                {
                    data: 'harga',
                    name: 'harga'
                },
                {
                    data: 'jumlah',
                    name: 'jumlah'
                },
                {
                    data: 'subtotal',
                    name: 'subtotal'
                },
                {
                    data: 'action',
                    name: 'action',
                    sortable: false,
                    // menghilangkan icon anak panah atau mematikan fitur balik data dari Z ke A
                    // tombol hapus tidak akan bisa di cari
                    searchable: false
                }
            ],
            dom: "Brt",
            // bsort: false akan menghilangkan fitur search, pagination dan lain-lain
            // menyortir: matikan
            bSort: false,
            // gunakan bahasa indonesia di package datatable
            language: {
                // panggil folder public/terjemahan_datatable
                url: "/terjemahan_datatable/indonesia.json"
            }
        })
        // pada draw.dt, jalankan fungsi berikut
        .on("draw.dt", function() {
            // panggil fungsi reload_form
            reload_form();
        });


    // Update Jumlah Dan Subtotal
    // jika #jumlah diubah inputnya maka
    $(document).on("input", "#jumlah", function() {
        let id_pembelian_detail = $(this).data("id");
        let jumlah = $(this).val();
        // jika input jumlah < 1
        if ($(this).val() < 1) {
            Swal.fire("Jumlah Tidak Boleh Kurang Dari 1");
            $(this).val(1)
        } else if ($(this).val() > 1000) {
            Swal.fire("Jumlah Tidak Boleh Lebih Dari 1000");
            $(this).val(1000);
        } else {
            // setelah waktu selesai maka lakukan ajax
            setTimeout(() => {
                $.post(`/pembelian-detail/${id_pembelian_detail}`, {
                        _method: 'PUT',
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        jumlah: $(this).val()
                    })
                    .done((response) => {
                        table_pembelian_detail.ajax.reload();
                    });
            }, 2000);
        };

    });

    // jika #tombol_tampilkan_produk_penyuplai di click maka jalankan fungsi berikut
    $('#tombol_tampilkan_modal_produk_penyuplai').on('click', function() {
        // panggil #modal_produk_penyuplai lalu modal nya di tampilkan
        $("#modal_produk_penyuplai").modal("show");
    })

    // fitur pilih produk penyuplai
    // jika document di click yang class nya adalah .pilih_produk_penyuplai, jalankan fungsi berikut
    $(document).on('click', '.pilih_produk_penyuplai', function() {
        // berisi panggil value dari .pilih_produk_penyuplai, attribute data-produk-penyuplai-id
        let produk_penyuplai_id = $(this).data('produk-penyuplai-id');
        // berisi panggil value dari .pilih_produk_penyuplai, attribute data-harga
        let harga = $(this).data('harga');
        // cetak value produk_penyuplai_id di menu console
        // console.log(produk_penyuplai_id, harga);

        // berisi ambil nilai input #pembelian_id yang disimpan dalam form_produk_penyuplai.blade
        let pembelian_id = $("#pembelian_id").val();
        // panggil #produk_penyuplai_id yang disimpan di form_produk_penyuplai.blade diisi dengan variable produk_penyuplai_id
        $('#produk_penyuplai_id').val(produk_penyuplai_id);

        // sembunyikan modal produk_penyuplai
        // panggil #modal_produk_penyuplai lalu modal nya di tutup
        $(`#modal_produk_penyuplai`).modal('hide');
        
        // jquery lakukan ajax
        $.ajax({
            // url memanggil route pembelian_detail.store
            url: "{{ route('pembelian_detail.store') }}",
            // panggil route tipe POST
            type: "POST",
            // laravel mewajibkan keamanan dari serangan csrf
            // tajuk-tajuk berisi object
            headers: {
                // berisi panggil element meta, attribute name="csrf_token", value attribute content
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            // kirimkan data berupa object
            // object berisi key dan value
            // karena aku mengirim data berupa object maka aku tidak perlu processData, contentType dan cache
            data: {
                // key pembelian_id berisi value variable pembelian_id
                "pembelian_id": pembelian_id,
                "produk_penyuplai_id": produk_penyuplai_id
            }
        })
            // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapan nya
            .done(function(resp) {
                // jika tanggapan.status sama dengan 200 maka
                if (resp.status === 200) {
                    // Tampilkan pembelian_detail yang baru di views pembelian_detail.index
                    table_pembelian_detail.ajax.reload();
                    console.log(resp.pesan);
                };
            });
    });


    

    // jika document di click, yang class adalah .tombol_hapus maka jalankan fungsi berikut
    $(document).on("click", ".tombol_hapus", function() {
        // berisi panggil .tombol_hapus lalu ambil value atribute data-pebelian-detail-id
        let pembelian_detail_id = $(this).data("pembelian-detail-id");
        // tampilkan notifikasi penghapusan menggunakan sweetalert
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Anda tidak akan dapat mengembalikan ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        })
        // kemudian, jalankan fungsi berikut dam ambil hasil pilihan user yaitu ya atau tidak
        .then((result) => {
            // jika user pilih tombol ya
            // jika hasil adalah konfirmasi
            if (result.isConfirmed) {
                // jquery lakukan ajax
                $.ajax({
                    // url memanggil /pembelian/destroy lalu kirimkan value variable pembelian_detail_id
                    url: `/pembelian-detail/destroy/${pembelian_detail_id}`,
                    // route tipe kirim
                    type: "POST",
                    // laravel mewajibkan keamanan dari serangan csrf
                    // tajuk-tajuk berisi object
                    headers: {
                        // berisi panggil element meta, attribute name="csrf_token", value attribute content
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                })
                    // jika selesai dan berhasil maka jalankan fungsi berikut lalu ambil tanggapan nya
                    .done(function(resp) {
                        // jika value tangapan.status sama dengan 200 maka
                        if (resp.status === 200) {
                            // tampilkan notifikasi menggunakan sweetalert
                            Swal.fire(
                                'Dihapus!',
                                'Detail Pembelian Berhasil Dihapus.',
                                'success'
                            );
                            // karena data yang dipilih sudah hapus maka ambil lagi semua data table pembelian_detail
                            // panggil variable table_pembelian_detail, lalu ajax nya di muat ulang
                            table_pembelian_detail.ajax.reload();
                        };
                    });
            };
        });
    });

    // jika aku memasukkan diskon
    // diskon
    $(document).on("input", "#diskon", function() {
     // jika value #diskon sama dengan kosong
        if ($(this).val() === "") {
            $(this).val(0).select();
     // jika value #diskon berisi angka maka
        } else {
        // panggil fungsi loadFrom kirim value #diskon
            reload_form($(this).val());
        };
    });

    // fungsi reload_form
    function reload_form() {
        // berisi text milik .total_harga yang di buat di PembelianDetailController, method data
        let total_harga = $('.total_harga').text();
        // panggil #total_harga di form_pembelian.blade lalu diisi dengan value variable total_harga
        $("#total_harga").val(total_harga);
        // panggil #total_barang di form_pembelian.blade lalu diisi dengan text dari .total_item yang di buat di PembelianDetailController, method data
        $("#total_barang").val($('.total_barang').text());
        // panggil #show_total_barang lalu diisi dengan text milik .total_barang
        $("#show_total_barang").val($('.total_barang').text());

        // panggil ajax tipe dapatkan, panggil url /pembelian-detail/reload-form/ lalu kirimkan value variable total_harga
        $.get(`{{ url('/pembelian-detail/reload-form') }}/${total_harga}`)
            // jika berhasil maka jalankan fungsi berikut lalu ambil tanggapan
            .done(response => {
                // #total_rp di form_pembelian.blade lalu diisi dengan response.total_rp
                $("#total_rp").val(response.total_rp);
                $("#bayar_rp").val(response.bayar_rp);
                $("#bayar").val(response.bayar);
                // panggil #total_pembayaran di pembelian_detail.index lalu text nya diisi dengan response.bayar_rp
                $("#total_pembayaran").text(response.bayar_rp);
                $("#total_pembayaran_format_terbilang").text(response.terbilang);
            })
            // jika gagal maka jalankan fungsi berikut lalu ambil errorsnya
            .fail(errors => {
                // tampilkan alert yang berisi pesan berikut
                alert('Tidak Dapat Menampilkan Data karena kode error.');
                // selesai
                return;
            });
    };

    // Jika jumlah di input atau dimasukkan maka jalankan fungsi berikut
    $(document).on("input", ".quantity", function(e) {
        // ambil nilai attribute data-id
        let id_pembelian_detail = $(this).data("id");
        // konversi string ke integer
        let jumlah = parseInt($(this).val());
        // jika jumlah lebih kecil dari satu atau tidak ada jumlah
        if (jumlah < 1 || !jumlah) {
            $(this).val(1);
            Swal.fire("Jumlah Tidak Boleh Lebih Kecil Dari 1");
            return;
        };
        if (jumlah > 10000) {
            $(this).val(10000);
            Swal.fire("Jumlah Tidak Boleh Lebih Besar Dari 10.000");
            return;
        };
        setTimeout(function() {
            // ajax type kirim
            // ke method update karena methodnya adalah put
            $.post(`/pembelian-detail/${id_pembelian_detail}`, {
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: "PUT",
                // kirimkan jumlah
                jumlah: jumlah,
            })
            .done(response => {
                    table_pembelian_detail.ajax.reload();
            })
                // .fail diperlukan ketika debug
                .fail(errors => {
                    Swal.fire("Tidak Dapat Menyimpan Data Karena Code Error");
                    return;
                });
        }, 2000);
    });

    $("button#simpan_transaksi").on("click", function() {
        $("#form_pembelian").submit();
    });
</script>
@endpush
