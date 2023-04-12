{{-- memperluas parentnya yaitu layouts.app --}}
@extends('layouts.app')

{{-- dorong css lalu tangkap mengguakan $stack('css') --}}
@push('css')
    {{-- buat element style --}}
    <style>
        /* Jika tidak menulis kode di bawah ini maka table penyuplai yang berada di dalam modal, tidak akan full */
        /* panggil #table_penyuplai */
        #table_penyuplai {
            /* lebar: 100% tidak penting */
            width: 100% !important;
        }
    </style>
@endpush

{{-- kirim value section title ke @Yield('title') --}}
@section('title', 'Pembelian')

{{-- kirim value section konten ke @yield('konten') --}}
@section('konten')
    <!-- Content Wrapper. Contains page content -->
    <div class="row mb-2">
        <div class="col-md-12 mt-2">
            {{-- termasuk ada jika modal dipanggil --}}
            {{-- panggil modal penyuplai --}}
            @includeIf('pembelian.modal_penyuplai')

            <div class="mb-3">
                {{-- jika tombol di click maka panggil fungsi pilih_penyuplai --}}
                <button id="tombol_pembelian_baru" class="btn btn-purple btn-sm mr-1">
                    <i class="mdi mdi-plus"></i> Pembelian Baru
                </button>
                @if (session('id_pembelian'))
                    <a href="{{ url('/pembelian-detail') }}" class="btn btn-info mr-1"><i class="fa fa-money-bill"></i>
                        Transaksi Aktif</a>
                @endif
            </div>

            {{-- termasuk element table dan form --}}
            @include('pembelian.table')

            {{-- jika tombol lihat detail pembelian di click maka panggil views/pembelian/modal_detail --}}
            {{-- termasuk jika pembelian.detail --}}
            @includeIf('pembelian.modal_detail')
            {{-- termasuk modal pembelian.modal_retur --}}
            @include('pembelian.modal_retur')
        </div>
    </div>
@endsection


@push('script')
    <script>
        // untuk mengecilkan sidebar

        // lakukan ajax untuk mengecek apakah ada penyuplai dan produk jika tidak ada maka tampilkan notifikasi "Anda harus menambahkan minimal 1 penyuplai terlebih dahulu", lalu arahkan ke menu penyuplai
        $.ajax({
                // panggil route pembelian.cek_pemyuplai_dan_produk
                url: "{{ route('pembelian.cek_penyuplai_dan_produk') }}",
                // panggil route tipe dapatkan
                type: "GET"
            })
            // jika selesai, maka jalankan fungsi berikut dan ambil tanggapan nya
            .done(function(resp) {
                // console.log(resp);
                // jika tanggapan.pesan sama dengan pesan berikut
                if (resp.message === 'Anda harus menambahkan minimal 1 penyuplai terlebih dahulu.') {
                    // tampilkan notifikasi menggunakan sweetalert
                    Swal.fire(
                            'Penyuplai Belum Ditambahkan!',
                            'Anda harus menambahkan minimal 1 penyuplai terlebih dahulu!',
                            'error'
                        )
                        // jika user click oke maka kemudian jalankan fungsi berikut
                        .then(function() {
                            // arahkan ke route penyuplai.index
                            // windows.lokasi memanggil route penyuplai.index
                            window.location = "{{ route('penyuplai.index') }}";
                        });
                };
                // // lain jika tanggapan.pesan sama dengan pesan berikut
                if (resp.pesan === 'Anda harus menambahkan minimal 1 produk terlebih dahulu.') {
                    // tampilkan notifikasi menggunakan sweetalert
                    Swal.fire(
                            'Ada yang salah!',
                            'Anda harus menambahkan minimal 1 produk terlebih dahulu!',
                            'error'
                        )
                        // jika user click oke maka kemudian jalankan fungsi berikut
                        .then(function() {
                            // ke route produk.index
                            // window.lokasi memanggil route poduk_penyuplai.index
                            window.location = "{{ route('produk.index') }}";
                        });
                };
            });

        // berisi panggil #table_pembelian lalu gunakan datatable
        let table = $("#table_pembelian").DataTable({
            // ketika data masih dimuat maka tampilkan animasi processing
            // proses: benar
            processing: true,
            // autoLebar: mati
            autoWidth: false,
            // gunakan serverSide agar ketika data sudah diatas 10.000 maka tidak akan lemot dalam menampilkan data
            // serverSisi: benar
            serverSide: true,
            // lakukan ajax
            // ajax berisi object
            ajax: {
                // url panggil route pembelian.data
                url: "{{ route('pembelian.data') }}"
            },
            // gunakan bahasa indonesia di package datatable
            language: {
                // url memamnggil folder public/terjemahkanDatatable/indonesia.json
                url: "/terjemahan_datatable/indonesia.json"
            },
            // buat tbody, tr dan td lalu masukkan data ke dalamnya
            columns: [
                // lakukan pengulangan nomor, searchable agar tidak bisa dicari, sortable agar menghilangkan icon anak panah agar datanya tidak bisa dibalik
                {
                    data: 'DT_RowIndex',
                    searchable: false,
                    sortable: false
                },
                {
                    data: 'tanggal'
                },
                {
                    data: 'penyuplai'
                },
                {
                    data: 'total_barang'
                },
                {
                    data: 'total_harga'
                },
                {
                    data: 'status'
                },
                {
                    data: 'action',
                    searchable: false,
                    sortable: false
                }
            ],
        });

        // table detail
        // berisi #table_detail gunakan datatable
        let table_detail = $("#table_detail").DataTable({
            processing: true,
            bsort: false,
            dom: 'Brt',
            columns: [
                // nomor
                {
                    data: 'DT_RowIndex',
                    searchable: false,
                    sortable: false
                },
                {
                    data: 'nama_produk'
                },
                {
                    data: 'kode_produk'
                },
                {
                    data: 'harga'
                },
                {
                    data: 'jumlah'
                },
                {
                    data: 'subtotal'
                },
            ]
        });

        // read data penyuplai
        // panggil #table_penyuplai lalu gunakan datatable
        $("#table_penyuplai").DataTable({
            // Jika penyuplai sedang dimaut maka tampilkan processing nya dulu
            processing: true,
            // server side akan menangani data yang lebih besar dari 10.000
            serverSide: true,
            // lakukan ajax, dan panggil route pnyuplai.index
            ajax: "{{ route('pembelian.penyuplai') }}",
            // buat tbody, tr dan td lalu isi datanya
            columns: [{
                    // pengulangan nomor
                    // DT_RowIindex didapatkan ari laravel datatable
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    sortable: false
                },
                {
                    data: 'nama_penyuplai',
                    name: 'nama_penyuplai'
                },
                {
                    data: 'telepon_penyuplai',
                    name: 'telepon_penyuplai'
                },
                {
                    data: 'alamat_penyuplai',
                    name: 'alamat_penyuplai'
                },
                {
                    data: 'action',
                    name: 'action',
                    sortable: false,
                    searchable: false
                }
            ],
            // gunakan bahasa indonesia di package datatable
            language: {
                // panggil folder public/terjemahan_datatable
                url: "/terjemahan_datatable/indonesia.json"
            }
        });

        function pilih_penyuplai() {
            $("#modal_penyuplai").modal("show");
        };

        // Jika #tombol_pembelian_baru di click maka jalankan fungsi berikut
        $("#tombol_pembelian_baru").on("click", function() {
            // panggil #modal_penyuplai lalu modal nya di munculkan
            $("#modal_penyuplai").modal("show");
        })


        // jika modal tambah dikirim
        $("#form_tambah").on("submit", function(e) {
            e.preventDefault();
            $.ajax({
                    url: "{{ route('pembelian.store') }}",
                    type: "POST",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $(".input").removeClass("is-invalid");
                        $(".pesan_error").text("");
                    }
                })
                .done(function(resp) {
                    if (resp.status === 0) {
                        // lakukan pengulangan
                        // key berisi semua nilai name.
                        // value berisi array yang menyimpan semua pesan error
                        $.each(resp.errors, function(key, value) {
                            $(`.${key}_input`).addClass("is-invalid");
                            $(`.${key}_error`).text(value[0]);
                        });
                    } else if (resp.status === 200) {
                        // // reset formulir
                        $("#form_tambah")[0].reset();
                        // nama pembelian di focuskan
                        $("#deskripsi").focus();
                        // muat ulang table ajax
                        table.ajax.reload();
                        // notifikasi
                        toastr.success(`${resp.pesan}.`);
                    };
                });
        });

        // detail pembelian
        // fungsi tampilkan_semua_pembelian_detail_terkait, parameter berisi url
        function tampilkan_semua_pembelian_detail_terkait(url) {
            // #modal_detail modalnya di tampilkan
            $("#modal_detail").modal("show");
            // table_detail panggil ajax url berisi url
            table_detail.ajax.url(url);
            // table_detail, ajax nya, di muat ulang
            table_detail.ajax.reload();
        };

        // hapus satu baris 
        // parameter url berisi route
        function hapus_data(url) {
            // tampilkan konfirmasi penghapusan
            Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Anda tidak akan dapat mengembalikan ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                })
                // kemudian hasilnya
                .then((result) => {
                    // jika hasilnya dikonfirmasi
                    if (result.isConfirmed) {
                        // lakukan ajax tipe kirim, kirim url
                        $.post(url, {
                                // laravel mewajibkan keamanan dari serangan csrf
                                '_token': $('[name=csrf-token]').attr('content'),
                                // panggil route tipe hapus
                                '_method': 'delete'
                            })
                            // jika selesai dan berhasil maka
                            .done((response) => {
                                table.ajax.reload();
                            })
                    };
                });
        };

        hasil_pembelian_id = '';

        // fungsi retur_pembelian agar aku bisa retur_pembelian atau mengembalikkan pembelian
        // function retur_pembelian berisi parameter pembelian_id yang menangkap pembelian_id dari controller
        function retur_pembelian(pembelian_id) {
            // jquery lakukan ajax
            $.ajax({
                // url panggil /pembelian/data-retur/ kirimkan pembelian_id
                url: `/pembelian/data-retur/${pembelian_id}`,
                // panggil route tipe dapatkan
                type: "GET",
            })
                // jika selesai dan berhasil maka jalankan fungsi berikut lalu ambil tanggapan
                // parameter response berisi semua pembelian detail terkait
                // misalnya column pembelian_id berisi 1 maka ambil semua pembelian detail yang column pembelian_id berisi 1
                .done(function(response) {
                    // aku butuh variable hasil untuk menyimpan banyak tr, jadi pada awal nya variabel hasil berisi string kosong, setelah di looping maka variable hasil akan digabung dengan element tr
                    let hasil = ``;

                    // lakukan pengulangan terhadap response yang berisi semua pembelian detail terkait
                    // tanggapan.untukSetiap(fungsi(barang, index))
                    // parameter item berisi data table pembelian detail maksudnya semua detail pembelian_detail terkait
                    // parameter index berisi index nya misalnya index 0, index 1
                    response.forEach(function(item, index) {
                        // lakukan pengulangan terhadap tr atau table rows atau table baris
                        // panggil variable hasil lalu tambahkan element tr berulang kali ke dalam variable hasil
                        hasil += `
                            <tr>
                                <td>${index + 1}</td>    
                                <td>${item.nama_produk}</td>    
                                <td>
                                    <input name="jumlah_retur" type="number" class="form-control" value="${item.jumlah}" max="${item.jumlah}" data-produk-penyuplai-id="${item.produk_penyuplai_id}">
                                </td> 
                                <td>
                                    <input name="keterangan" type="text" class="form-control" autocomplete="off">
                                </td>
                            </tr>
                        `;
                    });
                    
                    // panggil #tbody_retur_pembelian lalu tambahkan value variable data_retur_pembelian_detail sebagai anak terakhir
                    $("#tbody_retur_pembelian").append(hasil);

                    // panggil #modal_retur lalu modal nya di tampilkan
                    $("#modal_retur").modal('show');

                    hasil_pembelian_id = pembelian_id;
                });
        };

        // jika #tombol_tutup_retur_pembelian di click maka jalankan fungsi berikut
        $(".tombol_tutup_retur_pembelian").on("click", function() {
            // panggil #tbody_return_pembelian lalu kosongkan semua anaknya
            $("#tbody_retur_pembelian").empty();
        });

        // jika #form_retur_pembelian dikirim maka jalankan fungsi berikut dan ambil event atau acara nya
        $("#form_retur_pembelian").on("submit", function(e) {
            // event cegah bawaan nya
            e.preventDefault();

            // console.log(hasil_pembel ian_id);
            // lakukan ajax
            $.ajax({
                // url memanggil /pembelian/retur/ lalu kirimkan pembelian_id
                url: `/pembelian/retur/${hasil_pembelian_id}`,
                // panggil route tipe kirim
                type: "POST",
                // kirimkan data formulir dari #form_retur_pembelian
                // data: baru FormulirData("#form_retur_pembelian")
                data: new FormData(this),
                processData: false,
                contentType: false,
                cache: false
            })
                // jika selesai dan berhasil maka ambil tanggapannya
                .done(function(response) {
                    $("#modal_retur").modal('hide');
                    // variabel table.ajax.muatuLANG
                    table.ajax.reload();
                })
        });
    </script>
@endpush
