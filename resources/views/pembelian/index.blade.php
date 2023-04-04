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
        </div>
    </div>
@endsection


@push('script')
    <script>
        // untuk mengecilkan sidebar

        // lakukan ajax untuk mengecek apakah ada penyuplai dan produk_penyuplai jika tidak ada maka tampilkan notifikasi "Anda harus menambahkan minimal 1 penyuplai terlebih dahulu", lalu arahkan ke menu penyuplai
        $.ajax({
                // panggil route pembelian.cek_pemyuplai_dan_produk_penyuplai
                url: "{{ route('pembelian.cek_penyuplai_dan_produk_penyuplai') }}",
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
                if (resp.pesan === 'Anda harus menambahkan minimal 1 produk penyuplai terlebih dahulu.') {
                    // tampilkan notifikasi menggunakan sweetalert
                    Swal.fire(
                            'Ada yang salah!',
                            'Anda harus menambahkan minimal 1 produk penyuplai terlebih dahulu!',
                            'error'
                        )
                        // jika user click oke maka kemudian jalankan fungsi berikut
                        .then(function() {
                            // ke route produk_penyuplai.index
                            // window.lokasi memanggil route poduk_penyuplai.index
                            window.location = "{{ route('produk_penyuplai.index') }}";
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
        // fungsi show_detail, parameter berisi url
        function show_detail(url) {
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

        // fungsi retur_pembelian agar aku bisa retur_pembelian atau mengembalikkan pembelian
        // function retur_pembelian berisi parameter pembelian_id yang menangkap pembelian_id dari controller
        function retur_pembelian(pembelian_id) {
            // panggil sweetalert untuk menampilkan formulir untuk menyimpan input keterangan retur pembelian
            Swal.fire({
                // key judul berisi value berikut
                title: 'Retur Pembelian',
                // kunci html berisi tag input, aku butuh name="keterangan" agar masuk ke dalam table retur_pembelian, column keterangan
                html: `<input name="keterangan" type="text" id="keterangan" class="swal2-input" placeholder="Keterangan Retur">`,
                // textTombolKonfirmasi: 'Selesai'
                confirmButtonText: 'Selesai',
                // fokusKonfirmasi: false
                focusConfirm: false,
                // validasi input keterangan
                // prakonfirmasi berisi fungsi jalankan fungsi berikut
                preConfirm: () => {
                    // variable keterangan berisi Swal.dapatkanMuncul, ambil value dari #keterangan misalnya "Pembelian di retur karena barang nya rusak"
                    let keterangan = Swal.getPopup().querySelector('#keterangan').value;
                    // jika tidak ada keterangan
                    if (!keterangan) {
                        // Swal.tampilkanValidasiPesan yang berisi pesan berikut
                        Swal.showValidationMessage(`Tolong masukkan keterangan retur pembelian`);
                    };
                    // kembalikkan object
                    return {
                        // key keterangan berisi value variable keterangan
                        keterangan: keterangan,
                    };
                }
            })
            // kemudian hasil, jalankan fungsi berikut dan ambil hasil nya 
            .then((result) => {
                // lakukan ajax
                $.ajax({
                    // url panggil route pembelian.retur_pembelian lalu kirimkan pembelian_id
                    url: `{{ route('pembelian.retur_pembelian') }}`,
                    // route tipe POST
                    type: "POST",
                    // laravel mewajibkan keamanan dari serangan csrf
                    // tajuk-tajuk berisi object
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    // kirimkan data berupa object
                    data: {
                        // key pembelian_id berisi value dari parameter pembelian_id
                        "pembelian_id": pembelian_id,
                        // key keterangan berisi value dari input#keterangan
                        "keterangan": $("#keterangan").val()
                    }
                })
                // jika selesai dan berhasil maka ambil tanggapan nya
                .done(function(resp) {
                    // cetak semua tanggapan
                    // console.log(resp);

                    // muat ulang data table pembelian agar data table nya di update
                    // table ajax nya di muat ulang
                    table.ajax.reload();
                });
            });

        };
    </script>
@endpush
