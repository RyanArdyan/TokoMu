{{-- memperluas parentnya yaitu layouts.app --}}
@extends('layouts.app')

{{-- dorong css lalu tangkap mengguakan $stack('css') --}}
@push('css')
    {{-- buat element style --}}
    <style>
        /* menyembunyikan baris table / tr terakhir */
        /* panggil #table_pembelian_detail, tbody, tr, anak terakhir */
        /* tr terakhir berfungsi untuk diambil value jumlah dan subtotal nya lalu disimpan dalam input total_harga dan total_barang */
        #table_pembelian_detail tbody tr:last-child {
            display: none;
        }

        ;
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
                                        {{-- attribute data-inputmask adalah attribute milik package input mask, jadi 1000 akan menjadi Rp 1.000 --}}
                                        <h1 data-inputmask="'alias': 'decimal', 'prefix': 'Rp ', 'groupSeparator':  '.',  'removeMaskOnSubmit': true, 'autoUnMask': true, 'rightAlign': false, 'radixPoint': ','"
                                            id="total_pembayaran">{{ $detail_total_harga_pembelian }}</h1>

                                        <p id="total_pembayaran_format_terbilang">
                                            {{ terbilang($detail_total_harga_pembelian) }}</p>
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
                                {{-- termasuk pembelian_detail.form_pembelian --}}
                                @include('pembelian_detail.form_pembelian')
                            </div>
                        </div>

                        {{-- jika tombol kembali di click maka arahkan ke halaman pembelian dan jika value column total milik detail pembelian masih 0 maka hapus pembelian terkait atau hapus pembelian berdasarkan pembelian_id --}}
                        {{-- ke route pembelian.kembali lalu kirimkan pembelian_id --}}
                        <a href="{{ route('pembelian.kembali', $pembelian_id) }}" class="btn btn-sm btn-danger">
                            <i class="fas fa-arrow-left"></i>
                            Kembali
                        </a>
                        <button id="simpan_transaksi" type="submit" class="btn btn-sm btn-primary"><i
                                class="fa fa-save"></i> Simpan Transaksi</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection


@push('script')
    <script>
        // fungsi angka_terbilang akan mengubah 1000 menjadi 1000
        // Jika aku ingin memanggil fungsi angka_terbilang maka aku harus mengirim argumen nilai
        function angka_terbilang(nilai) {
            // Metode Math.abs() mengembalikan nilai absolut dari sebuah angka.
            nilai = Math.abs(nilai);
            // beisi angka 0
            let simpan_nilai_bagi = 0;
            // berisi array menyimpan terbilang dari 0 sampai 11
            let huruf = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh",
                "Sebelas"
            ];
            // berisi string
            let temp = "";

            // jika value parameter nilai < 12
            if (nilai < 12) {
                // temp berisi " " digabung array huruf[valur parameter nilai]
                temp = huruf[nilai];
            }
            // lain jika value parameter nilai lebih kecil dari 20
            else if (nilai < 20) {
                // temp berisi memanggil fungsi angka_terbilang lalu nilai parameter nilai dikurang 10 ditambah belas rupiah
                temp = angka_terbilang(nilai - 10) + " Belas";
            } 
            // lain jika value parameter nilai lebih kecil dari 100
            else if (nilai < 100) {
                // Metode Math.floor() membulatkan angka ke bawah
                // berisi Math.lantai(nilai dibagi 10)
                simpan_nilai_bagi = Math.floor(nilai / 10);
                // berisi panggil fungsi angka_terbilang(simpan_nilai_bagi) ditambah " Puluh Rupiah" + panggil fungsi angka_terbilang(nilai sisa bagi 10);
                temp = angka_terbilang(simpan_nilai_bagi) + " Puluh " + angka_terbilang(nilai % 10);
            }
            // lain jika value parameter nilai lebih kecil dari 200
            else if (nilai < 200) {
                // berisi "Senilai Seratus" digabung panggil fungsi angka_terbilang(nilai - 100);
                temp = "Senilai Seratus" + angka_terbilang(nilai - 100);
            }
            // lain jika value parameter nilai lebih kecil dari 1000
            else if (nilai < 1000) {
                // berisi Math.lantai(value parameter nilai dibagi 100);
                simpan_nilai_bagi = Math.floor(nilai / 100);
                // berisi panggil fungsi angka_terbilang(value variable simpan_nilai_bagi) digabung " Ratus Rupiah " digabung panggil fungsi angka_terbilang(nilai parameter nilai di sisa bagi 100);
                temp = angka_terbilang(simpan_nilai_bagi) + " Ratus " + angka_terbilang(nilai % 100);
            }
            // lain jika value parameter nilai lebih kecil dari 2000 maka
            else if (nilai < 2000) {
                // Berisi " Seribu " digabung panggil fungsi angka_terbilang(value dari parameter nilai dikurang 1000);
                temp = " Seribu" + angka_terbilang(nilai - 1000);
            }
            // lain jika value parameter nilai lebih kecil dari 1 juta maka
            else if (nilai < 1000000) {
                // berisi Math.lantai(value parameter nilai dibagi 1000);
                simpan_nilai_bagi = Math.floor(nilai / 1000);
                // berisi panggil fungsi angka_terbilang(value dari variable simpan_nilai_bagi) digabung " Ribu " digabung panggil fungsi angka_terbilang(value parameter nilai di sisa bagi 1000);
                temp = angka_terbilang(simpan_nilai_bagi) + " Ribu " + angka_terbilang(nilai % 1000);
            } 
            // lain jika value parameter nilai lebih kecil dari 1 milyar maka 
            else if (nilai < 1000000000) {
                // berisi Math.lantai(value parameter nilai dibagi 1 juta
                simpan_nilai_bagi = Math.floor(nilai / 1000000);
                // berisi panggil fungsi angka_terbilang(value dari variable simpan_nilai_bagi) digabung " Juta " digabung panggil fungsi angka_terbilang(value dari parameter nilai disisa bagi 1 juta)
                temp = angka_terbilang(simpan_nilai_bagi) + " Juta " + angka_terbilang(nilai % 1000000);
            } else if (nilai < 1000000000000) {
                simpan_nilai_bagi = Math.floor(nilai / 1000000000);
                temp = angka_terbilang(simpan_nilai_bagi) + " Miliar " + angka_terbilang(nilai % 1000000000);
            }
            // lain jika value dari parameter nilai lebih kecil dari 100 trilyun maka
            else if (nilai < 1000000000000000) {
                // Berisi Math.lantai(value dari parameter nilai dibagi 1 trilyun
                simpan_nilai_bagi = Math.floor(nilai / 1000000000000);
                // berisi panggil fungsi angka_terbilang(value parameter nilai dibagi 1 trilyun digabung " Trilyun" digabung fungsi angka_terbilang(value variable nilai disisa bagi 1 trilyun
                temp = angka_terbilang(nilai / 1000000000000) + " Triliun " + angka_terbilang(nilai % 1000000000000);
            };

            // kembalikkan variable temp
            return temp;
        };


        // panggil data table produk_penyuplai yang terkait dengan penyuplai yang dipilih
        // panggil #table_produk_penyuplai lalu gunakan datatable
        $("#table_produk_penyuplai").DataTable({
            // Jika data produk_penyuplai sedang dimuat maka tampilkan processing nya dulu
            processing: true,
            // Jika data sudah lebih dari 10.000 maka tidak akan ngelag karena serverSide nya true
            // sisi server: benar
            serverSide: true,
            // lakukan ajax, dan panggil route pembelian_detail.produk lalu kirimkan penyuplai_id
            ajax: "{{ route('pembelian_detail.produk_penyuplai', $detail_penyuplai->penyuplai_id) }}",
            // buat tbody, tr dan td lalu isi datanya
            columns: [{
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
                // ajax memanggil route pembelian_detail.data, kirimkan value $pembelian_id, anggaplah berisi 1
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
                        // menghilangkan icon anak panah atau mematikan fitur balik data dari Z ke A
                        sortable: false,
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
            // agar input produk_penyuplai_id yang hidden sampai input total_harga di update value nya dan component dikiri juga diupdate
            .on("draw.dt", function() {
                // panggil fungsi reload_form
                reload_form();
            });


        // Update Jumlah Dan Subtotal
        // jika #jumlah diubah inputnya maka
        $(document).on("input", "#jumlah", function() {
            let pembelian_detail_id = $(this).data("id");
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
                    $.post(`/pembelian-detail/${pembelian_detail_id}`, {
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

            // berisi ambil nilai input #pembelian_id yang disimpan dalam form_pembelian.blade
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
                        // table_pembelian_detail, ajax nya di reload
                        table_pembelian_detail.ajax.reload();
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

        // fungsi reload_form agar input produk_penyuplai_id yang hidden sampai input total_harga di update value nya dan component dikiri juga diupdate
        function reload_form() {
            // panggil #total_barang di form_pembelian.blade lalu diisi dengan text dari .total_item yang di buat di PembelianDetailController, method data
            $("#total_barang").val($('.total_barang').text());
            // berisi text milik .total_harga yang di buat di PembelianDetailController, method data
            let total_harga = $('.total_harga').text();
            // panggil #total_harga di form_pembelian.blade lalu value nya diisi dengan value variable total_harga
            $("#total_harga").val(total_harga);
            // panggil #total_pembayaran lalu kasi value variable total_harga, jangan gunakan .text() karena itu akan menimpa package input mask
            $("#total_pembayaran").val(total_harga);
            // berisi panggil fungsi angka_terbilang lalu kirimkan total_harga
            let angka_terbilang_dari_total_harga = angka_terbilang(total_harga);
            // panggil #total_pembayaran_format_terbilang lalu text nya diisi dengan value dari variable angka_terbilang_dari_total_harga
            $("#total_pembayaran_format_terbilang").text(angka_terbilang_dari_total_harga);
        };

        // jika jumlah di masukkan value maka jalankan fungsi beikut
        // Jika document di masukkan value yang class nya adalah .jumlah maka jalankan fungsi berikut
        $(document).on("input", ".jumlah", function() {
            // ambil nilai attribute data-pembelian-detail-id
            let pembelian_detail_id = $(this).data("pembelian-detail-id");
            // konversi value milik .jumlah yang tipe nya string ke integer
            // berisi ubah string ke integer panggil .jumlah lalu amil value nya maksudnya ambil value dari attribute value=""
            let jumlah = parseInt($(this).val());
            // jika jumlah lebih kecil dari 1 berarti 0 atau tidak ada jumlah
            if (jumlah < 1 || !jumlah) {
                // value dari .jumlah disetel ke 1
                // panggil .jumlah lalu value nya diisi 1
                $(this).val(1);
                // tampilkan notifikasi menggunakan sweetalert
                Swal.fire("Jumlah Tidak Boleh Lebih Kecil Dari 1");
            }
            // jumlah produk yang dibeli tidak boleh lebih besar dari 1000 berarti dimulai dari 1001
            // lain jika jumlah lebih besar dari 1000 berarti dimulai dari 1001
            else if (jumlah > 1000) {
                // panggil .jumlah lalu value nya di setel ke 1000
                // panggil .jumlah lalu value nya diisi 1000
                $(this).val(1000);
                // panggil notifikasi menggunakan sweetalert
                Swal.fire("Jumlah Tidak Boleh Lebih Besar Dari 1.000");
            }
            // && berarti semua pengecekan harus bernilai true
            // misalnya user memasukkan jumlah yang bernilai 900 maka cek apakah 900 lebih besar dari 0? jawaban nya adalah true lalu apakah 900 lebih kecil dari 1001? jawabannya true, maka kode akan di eksekusi
            // jumlah minimal 1 dan maksimal 1000
            // lain jika jumlah lebih besar dari 0 berart 1 dan jumlah lebih kecil dari 1001 berarti 1000
            else if (jumlah > 0 && jumlah < 1001) {
                // setelah 3 detik maka eksekusi kode
                setTimeout(function() {
                    // ajax type kirim
                    // ke method update karena methodnya adalah put
                    $.post(`/pembelian-detail/${pembelian_detail_id}`, {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            _method: "PUT",
                            // kirimkan jumlah agar bisa ditangkap oleh variable $request di method update
                            jumlah: jumlah,
                        })
                        .done(response => {
                            table_pembelian_detail.ajax.reload();
                        })
                        // .fail diperlukan ketika debug
                        .fail(errors => {
                            // tampilkan notifikasi error menggunakan sweetalert
                            Swal.fire("Tidak Dapat Menyimpan Data Karena Code Error");
                            return;
                        });
                }, 3000);
            };

        });

        // jika tombol #simpan_transaksi di click maka jalankan fungsi berikut
        $("button#simpan_transaksi").on("click", function() {
            // berisi text dari .total_barang
            let total_barang = $(".total_barang").text();
            // jika total_barang sama dengan "0", tipe datanya string bukan integer
            if (total_barang === "0") {
                // tampilkan notifikasi berisi pesan berikut
                Swal.fire('Silahkan pilih produk terlebih dahulu.');
            }
            // lain jika total_barang tidak sama dengan "0" misalnya "3" maka
            else if (total_barang !== "0") {
                // panggil #form_pembelian lalu di kirim
                $("#form_pembelian").submit();
            };

        });
    </script>
@endpush
