{{-- memperluas parentnya yaitu layouts.app --}}
@extends('layouts.app')

{{-- dorong css lalu tangkap mengguakan $stack('css') --}}
@push('css')
    {{-- buat element style --}}
    <style>
        /* menyembunyikan baris table / tr terakhir */
        /* panggil #table_pembelian_detail, tbody, tr, anak terakhir */
        /* tr terakhir berfungsi untuk diambil value jumlah dan subtotal nya lalu disimpan dalam input total_harga dan total_barang */
        /* #table_pembelian_detail tbody tr:last-child { */
            /* tampilan: tidak_ada */
            /* display: none; */
        /* } */
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

                    {{-- termasuk ada jika modal pembelian_detail.modal_produk di panggil --}}
                    @includeIf('pembelian_detail.modal_produk')

                    <div class="row mb-2">
                        <ol class="breadcrumb float-sm-right">
                            {{-- jika #tombol_tampilkan_modal_produk di click maka tampilkan modal_produk.blade --}}
                            <button id="tombol_tampilkan_modal_produk" class="btn btn-success btn-sm"><i
                                    class="fa fa-shopping-bag"></i>
                                Pilih Produk</button>
                        </ol>
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
                                            id="total_pembayaran">0</h1>

                                        {{-- cetak, panggil fungsi helper terbilang agar 1000 menjadi seribu --}}
                                        <p id="total_pembayaran_format_terbilang">{{ terbilang(0) }}</p>
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

                        {{-- ke route pembelian.index --}}
                        <a href="{{ route('pembelian.index') }}" class="btn btn-sm btn-danger">
                            <i class="fas fa-arrow-left"></i>
                            Kembali
                        </a>
                        {{-- jika tombol Simpan Pembelian di click maka simpan banyak pembelian detail, pembelian dan tambah stok produk --}}
                        <button id="tombol_simpan_pembelian" type="button" class="btn btn-sm btn-primary"><i
                                class="fa fa-save"></i> Simpan Pembelian</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('script')
    <script>
        // fitur mengisi input tanggal & waktu secara otomatis berdasarkan waktu saat ini
        // jika document siap maka jalankan fungsi berikut
        $(document).ready(function() {
            // Dapatkan waktu sekarang dalam format yang sesuai dengan datetime-local (YYYY-MM-DDTHH:mm)
            var now = new Date();
            var year = now.getFullYear();
            var month = ('0' + (now.getMonth() + 1)).slice(-2);
            var day = ('0' + now.getDate()).slice(-2);
            var hours = ('0' + now.getHours()).slice(-2);
            var minutes = ('0' + now.getMinutes()).slice(-2);

            var waktuSekarang = year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;

            // Set nilai input
            // panggil #tanggal_dan_waktu lalu value nya diisi value variable waktuSekarang
            $("#tanggal_dan_waktu").val(waktuSekarang);
        });

        // fungsi angka_terbilang akan mengubah 1000 menjadi seribu
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
                temp = "Seratus " + angka_terbilang(nilai - 100);
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


        // panggil data table produk
        // panggil #table_produk lalu gunakan datatable
        $("#table_produk").DataTable({
            // Jika data produk sedang dimuat maka tampilkan processing nya dulu
            processing: true,
            // Jika data sudah lebih dari 10.000 maka tidak akan ngelag karena serverSide nya true
            // sisi server: benar
            serverSide: true,
            // lakukan ajax, dan panggil route pembelian_detail.produk
            ajax: "{{ route('pembelian_detail.produk') }}",
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
                    data: 'harga_beli',
                    name: 'harga_beli'
                },
                // panggil tombol pilih produk
                {
                    data: 'action',
                    name: 'action',
                    // sortable: false berarti akan menghilangkan icon anak panah atau menghilangkan fitur balik data dari Z-A
                    sortable: false,
                    // menghilangkan fitur cari di column pilih produk
                    searchable: false
                }
            ],
            // gunakan bahasa indonesia di datatable
            language: {
                // url panggil folder public/terjemahan_datatable/indonesia.json
                url: "/terjemahan_datatable/indonesia.json"
            }
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

        // jika #tombol_tampilkan_produk di click maka jalankan fungsi berikut
        $('#tombol_tampilkan_modal_produk').on('click', function() {
            // panggil #modal_produk lalu modal nya di tampilkan
            $("#modal_produk").modal("show");
        });


        

        // // fitur pilih produk
        // // jika document di click yang class nya adalah .pilih_produk, jalankan fungsi berikut
        // $(document).on('click', '.pilih_produk', function() {
        //     // berisi panggil value dari .pilih_produk_penyuplai, attribute data-produk-id
        //     let produk_id = $(this).data('produk-id');
        //     // berisi panggil value dari .pilih_produk, attribute data-harga-beli
        //     let harga_beli = $(this).data('harga-beli');

        //     // berisi ambil nilai input #pembelian_id yang disimpan dalam form_pembelian.blade
        //     let pembelian_id = $("#pembelian_id").val();
        //     // panggil #produk_id yang disimpan di form_produk.blade diisi dengan variable produk_id
        //     $('#produk_id').val(produk_id);

        //     // jquery lakukan ajax
        //     $.ajax({
        //             // url memanggil route pembelian_detail.store
        //             url: "{{ route('pembelian_detail.store') }}",
        //             // panggil route tipe POST
        //             type: "POST",
        //             // laravel mewajibkan keamanan dari serangan csrf
        //             // tajuk-tajuk berisi object
        //             headers: {
        //                 // berisi panggil element meta, attribute name="csrf_token", value attribute content
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             },
        //             // kirimkan data berupa object
        //             // object berisi key dan value
        //             // karena aku mengirim data berupa object maka aku tidak perlu processData, contentType dan cache
        //             data: {
        //                 // key pembelian_id berisi value variable pembelian_id
        //                 "pembelian_id": pembelian_id,
        //                 "produk_id": produk_id
        //             }
        //         })
        //         // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapan nya
        //         .done(function(resp) {
        //             // jika tanggapan.status sama dengan 200 maka
        //             if (resp.status === 200) {
        //                 // Tampilkan pembelian_detail yang baru di views pembelian_detail.index
        //                 // table_pembelian_detail, ajax nya di reload
        //                 table_pembelian_detail.ajax.reload();
        //             };
        //         });
        // });


        // jadi nanti value nya akan di tambah jika aku menambah baris baru pembelian detail
        // berisi angka 0
        let baris = 0;
        // untuk menyimpan total barang, jadi pada awalnya 0 lalu aku click tombol pilih di modal pilih produk maka variable total_barang jadi 1, aku click lagi jadi 2
        let total_barang = 0;
        // untuk menyimpan total harga, jadi pada awalnya 0 lalu aku click tombol pilih di modal pilih produk maka variable total_harga ditambah value detail_produk, column harga_jual, aku click lagi jadi 2
        let total_harga = 0;

        // jika .piih_produk di click maka jalankan fungsi berikut
        $(document).on("click", ".pilih_produk", function() {
            // berisi panggil .pilih_produk lalu ambil value attribute data-produk-id, anggaplah 1
            let produk_id = $(this).data('produk-id');

            // jquery lakukan ajax
            $.ajax({
                // url memanggil route penjualan_detail.ambil_detail_produk
                url: "{{ route('penjualan_detail.ambil_detail_produk') }}",
                // panggil route tipe kirim
                type: 'POST',
                // untuk keamanan dari serangan csrf
                // tajuk-tajuk berisi object
                headers: {
                    // berisi panggil meta[name="csrf-token"], ambil value attribute content
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                // kirim data berupa object
                data: {
                    // key produk_id berisi value dari variable produk_id
                    produk_id: produk_id
                }
            })
            // jika selesai dan berhasil maka jalankan fungsi berikut
            // parameter response akan menangkap value yang dikirim PenjualanDetailController, method ambil_detail_produk()
            .done(function(response) {
                // panggil variable total_barang lalu value nya ditambah 1, lalu nanti di click lagi maka value nya jadi 2
                total_barang += 1;
                // panggil variable total_harga lalu value nya di tambah dengan value response.harga_beli
                total_harga += response.harga_beli;

                // panggil #total_barang lalu value atau nilai nya diisi dengan value variabel total_barang
                $("#total_barang").val(total_barang);
                // panggil #total_harga lalu value atau nilai nya diisi dengan value variabel total_harga
                $("#total_harga").val(total_harga);

                // panggil fungsi update_total_harga_versi_rupiah lalu kirimkan value variable total_harga
                update_total_harga_versi_rupiah(total_harga);


                // berisi mengubah 1000 menjadi Rp 1.000
                let harga_beli = response.harga_beli.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });

                // jadi nanti value nya akan di tambah jika aku click tombol pilih produk di modal pilih produk
                // panggil variable baris lalu value nya di tambah 1
                baris += 1;
                // jadi nanti akan ada class="baris1", baris2 dan seterusnya untuk fitur menambah baris
                // .baris_baru aku gunakan agar setelah aku click tombol simpan maka semua data disimpan ke dalam database lalu aku hapus .baris_baru dan turunannya
                var html = `<tr class="baris${baris} baris_baru">`;
                    // berisi panggil value variable html lalu isinya ditambah
                    // aku tidak perlu attribute name jadi aku akan mengambil value-value lewat class
                    html += `<th>${baris}</th>`;
                    // data-produk-id yang menyimpan value detail_produk, column produk_id, aku gunakan di fitur simpan penjualan detail setelah aku click tombol Simpan pembelian
                    // cetak value detail_produk, column kode_produk
                    html += `<td class="kode_produk" data-produk-id="${response.produk_id}">${response.kode_produk}</td>`;
                    // cetak value detail_produk, column nama_produk
                    html += `<td class="nama_produk">${response.nama_produk}</td>`;
                    // cetak value variable harga_beli
                    html += `<td class="harga_beli">${harga_beli}</td>`;
                    // attribtue data-baris aku gunakan untuk fitur perubahan subtotal setelah aku mengubah input jumlah, misalnya harga_belinya 30.000 lalu jumlah nya jadi 2 maka subtotal nya jadi 60.000, anggaplah berisi 1, 2 dan seterusnya
                    // buat attribute data-harga-beli agar bisa menyimpan value detail_produk, column harga_beli
                    // value=1 artinya bawaan value nya adalah 1
                    html += `<td><input type="number" class="jumlah" data-baris=${baris} data-harga-beli=${response.harga_beli} data-produk-id="${response.produk_id}" value=1></td>`;
                    // .subtotal aku gunakan di fitur total harga dan menyimpan pembelian_detail
                    // .subtotal_${baris} aku gunakan untuk fitur perubahan subtotal ketika jumlah barang nya di ubah, anggaplah berisi subtotal_1, subtotal_2, dan seterusnya
                    html += `<td class="subtotal subtotal_${baris}">${harga_beli}</td>`;
                    // .hapus aku gunakan untuk fitur hapus pembelian detail
                    // attribute data-rows anggaplah berisi baris1, baris2, dst.
                    html += "<td><button class='hapus btn btn-danger' data-row='baris"+ baris +"'>-</button></td>";
                    html += "</tr>";
                
                // panggil #tbody_pembelian_detail lalu tambahkan value variable html sebagai anak terakhir
                $("#tbody_pembelian_detail").append(html);
                
                // tampilkan notifikasi menggunakan package toastr
                toastr.success('Berhasil memilih produk.');
            });
        });

        // fungsi untuk memperbarui input total_harga versi rupiah dan input harus_bayar versi rupiah
        // parameter total_harga berisi value argument total_harga yang aku kirim
        function update_total_harga_versi_rupiah(total_harga) {
            // berisi mengubah 1000000 menjadi Rp 1.000.000
            // value parameter total_harga diubah menjadi bentuk rupiah
            let total_harga_versi_rupiah = total_harga.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });
            // panggil #total_pembayaran lalu value atau nilai nya diisi dengan nilai variable total_harga_versi_rupiah
            $("#total_pembayaran").val(total_harga_versi_rupiah);
            // panggil #total_pembayaran_format_terbilang lalu value nya dikosongkan pake string kosong
            $("#total_pembayaran_format_terbilang").text("");
        };




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

        // // jika jumlah di masukkan value maka jalankan fungsi beikut
        // // Jika document di masukkan value yang class nya adalah .jumlah maka jalankan fungsi berikut
        // $(document).on("input", ".jumlah", function() {
        //     // ambil nilai attribute data-pembelian-detail-id
        //     let pembelian_detail_id = $(this).data("pembelian-detail-id");
        //     // konversi value milik .jumlah yang tipe nya string ke integer
        //     // berisi ubah string ke integer panggil .jumlah lalu amil value nya maksudnya ambil value dari attribute value=""
        //     let jumlah = parseInt($(this).val());
        //     // jika jumlah lebih kecil dari 1 berarti 0 atau tidak ada jumlah
        //     if (jumlah < 1 || !jumlah) {
        //         // value dari .jumlah disetel ke 1
        //         // panggil .jumlah lalu value nya diisi 1
        //         $(this).val(1);
        //         // tampilkan notifikasi menggunakan sweetalert
        //         Swal.fire("Jumlah Tidak Boleh Lebih Kecil Dari 1");
        //     }
        //     // jumlah produk yang dibeli tidak boleh lebih besar dari 1000 berarti dimulai dari 1001
        //     // lain jika jumlah lebih besar dari 1000 berarti dimulai dari 1001
        //     else if (jumlah > 1000) {
        //         // panggil .jumlah lalu value nya di setel ke 1000
        //         // panggil .jumlah lalu value nya diisi 1000
        //         $(this).val(1000);
        //         // panggil notifikasi menggunakan sweetalert
        //         Swal.fire("Jumlah Tidak Boleh Lebih Besar Dari 1.000");
        //     }
        //     // && berarti semua pengecekan harus bernilai true
        //     // misalnya user memasukkan jumlah yang bernilai 900 maka cek apakah 900 lebih besar dari 0? jawaban nya adalah true lalu apakah 900 lebih kecil dari 1001? jawabannya true, maka kode akan di eksekusi
        //     // jumlah minimal 1 dan maksimal 1000
        //     // lain jika jumlah lebih besar dari 0 berart 1 dan jumlah lebih kecil dari 1001 berarti 1000
        //     else if (jumlah > 0 && jumlah < 1001) {
        //         // setelah 3 detik maka eksekusi kode
        //         setTimeout(function() {
        //             // ajax type kirim
        //             // ke method update karena methodnya adalah put
        //             $.post(`/pembelian-detail/${pembelian_detail_id}`, {
        //                     _token: $('meta[name="csrf-token"]').attr('content'),
        //                     _method: "PUT",
        //                     // kirimkan jumlah agar bisa ditangkap oleh variable $request di method update
        //                     jumlah: jumlah,
        //                 })
        //                 .done(response => {
        //                     table_pembelian_detail.ajax.reload();
        //                 })
        //                 // .fail diperlukan ketika debug
        //                 .fail(errors => {
        //                     // tampilkan notifikasi error menggunakan sweetalert
        //                     Swal.fire("Tidak Dapat Menyimpan Data Karena Code Error");
        //                     return;
        //                 });
        //         }, 3000);
        //     };

        // });





        // jika document di masukkan angka atau di ubah yang class nya adalah .jumlah maka jalankan fungsi berikut
        $(document).on('input', '.jumlah', function() {
            // console.log($(this).val());

            // // panggil .jumlah, value dari attribute data-id
            // let penjualan_detail_id = $(this).data('id');

            // berisi panggil input jumlah
            let input_jumlah = $(this);

            // ambil value dari input jumlah, ubah string menjadi angka menggunakan parseInt
            // panggil .jumlah lalu ambll value nya
            let jumlah = parseInt($(this).val());
            // panggil .jumlah, lalu ambil value dari attribute data-harga-beli yaitu berisi value detail_produk, column harga_beli
            let harga_beli = $(this).data('harga-beli');
            // panggil .jumlah lalu ambil value attribute data-baris, baris 1 akan menghasilkan angka 1, baris 2 menghasilkan angka 2, dan seterusnya
            let baris = $(this).data('baris');
            // berisi panggil .jumlah lalu ambil value dari attribute data-produk-id
            let produk_id = $(this).data("produk-id");

            // jika value jumlah lebih kecil atau sama dengan 0
            if (jumlah <= 0) {
                // panggil .jumlah lalu value atau nilai nya di set atau di tetapkan ke 1
                $(this).val(1);
                // tampilkan peringatan berikut
                Swal.fire('Jumlah tidak boleh kurang dari 1');
                // kode nya selesai atau jangan jalankan kode dibawahnya
                return;
            }
            // lain jika tidak ada value dari .jumlah maksudnya ada angka 1 di input jumlah lalu aku seleksi angka nya lalu aku hapus
            else if (!jumlah) {
                // panggil .jumlah, lalu value atau nilai nya di set atau di tetapkan ke 1
                $(this).val(1);
                // kode selesai dan berhenti
                return;
            };

            // // lakukan ajax untuk mengecek jumlah detail_produk, column stok jadi misalnya stok nya 80, lalu value input jumlah aku atur ke 1000 maka dia akan kembali menjadi 80
            // $.ajax({
            //     // panggil url: route penjualan_detail.cek_stok_produk
            //     url: "{{ route('penjualan_detail.cek_stok_produk') }}",
            //     // panggil route tipe POST
            //     type: "POST",
            //     // kirimkan data berupa object
            //     data: {
            //         // key jumlah berisi value variable jumlah
            //         jumlah: jumlah,
            //         // kirimkan value detail_penjuala_detail, column produk_id
            //         // key produk_id berisi value variable produk_id
            //         produk_id: produk_id
            //     },
            //     // laravel mewajibkan keamanan dari serangan csrf
            //     // tajuk-tajuk berisi objeck
            //     headers: {
            //         // key X-CSRF-TOKEN berisi panggil meta name csrf-token, value attribute content
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     }
            // })
            // // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapan nya
            // .done(function(response) {
            //     // jika value input jumlah lebih besar dari value detail_produk, column stok_produk atau value tanggapan.stok_produk, anggaplah value input jumlah nya adalah 1000 lalu value detail_produk column stok nya 80 maka
            //     if (jumlah > response.stok_produk) {
            //         // tampilkan notifikasi pakai sweetalert
            //         // Swal.api() panggil value tanggapan.pesan, tanda backtick bisa mencetak value variable di dalam string
            //         Swal.fire(`${response.message}`);
            //         // kembalikkan value nya ke jumlah maksimal detail_produk, column stok_produk
            //         // panggil .jumlah lalu value nya diisi dengan value tanggapan.stok_produk
            //         input_jumlah.val(response.stok_produk);
            //         // cetak value dari input_jumlah
            //         let stok_produk = input_jumlah.val();


            //         // berisi value variable stok_produk anggaplah 2 dikali value harga_jual anggaplah 30.000 berarti 60.000
            //         let subtotal = stok_produk * harga_jual;
            //         // anggaplah panggil .subtotal_1 lalu ubah text nya mengikuti value variable subtotal lalu ubah anggaplah 60000 akan menjadi Rp 60.000
            //         $(`.subtotal_${baris}`).text(subtotal.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }));


            //         // panggil fungsi update_totaL_barang_dan_harga();
            //         update_total_barang_dan_harga();
            //     };
            // });

            

            // berisi value variable jumlah anggaplah 2 dikali value harga_beli anggaplah 30.000 berarti 60.000
            let subtotal = jumlah * harga_beli;
            // anggaplah panggil .subtotal_1 lalu ubah text nya mengikuti value variable subtotal lalu ubah anggaplah 60000 akan menjadi Rp 60.000
            $(`.subtotal_${baris}`).text(subtotal.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }));


            // panggil fungsi update_totaL_barang_dan_harga();
            update_total_barang_dan_harga();
        });

        // ini untuk mendapatkan total barang dan total harga dengan cara looping semua value dari input jumlah dan td subtotal
        function update_total_barang_dan_harga() {
            // atur total_barang kembali ke 0 agar program nya benar, ini sebenarnya aku menimpa let total_barang = 0
            total_barang = 0;
            // atur total_harga kembali ke 0 agar program nya benar
            total_harga = 0;

            // untuk fitur menghitung total_barang
            // lakukan pengulangan terhadap semua .jumlah
            // setiap .jumlah, jalankan fungsi berikut, aku melakukan pengulangan karena pada baris 1 dan seterusnya aku buat di script makanya pakai each
            $(".jumlah").each(function() {
                // mulai pengulangan
                // value dari .jumlah akan berubah-ubah karena dia input
                // berisi panggil value dari tiap .jumlah lalu ubah string menjadi menjadi angka menggunakan parseInt, anggaplah baris 1 ada 2 jumlah, baris 2 ada 3 jumlah
                let value_dari_class_jumlah = parseInt($(this).val());

                // panggil variable total_barang lalu value nya di tambah sama dengan value variable value_dari_class_jumlah, anggaplah pada awal nya value total_barang berisi 0 lalu pada baris 1, jumlah nya 2 lalu pada baris 2 jumlah nya 3 maka total_barang berisi 5
                total_barang += value_dari_class_jumlah;
            });

            // lakukan pengulangan terhadap semua .subtotal
            // setiap .subtotal, jalankan fungsi berikut, aku melakukan pengulangan karena pada baris 1 dan seterusnya aku buat di script makanya pakai each
            $(".subtotal").each(function() {
                // mulai pengulangan
                // panggil .subtotal lalu ambil textnya
                let subtotal_versi_rupiah = $(this).text();
                // mengubah misalnya Rp 60.000 menjadi 60000
                let subtotal_versi_angka = parseInt(subtotal_versi_rupiah.replace(/[^0-9]/g, ''));

                // panggil total_harga lalu value nya di tambah sama dengan value variable subtotal_versi_angka, anggaplah pada awal nya value total_harga berisi 0 lalu pada baris 1, subtotal nya 30000 lalu pada baris 2 subtotal nya 80000 maka total_harga berisi 110000
                total_harga += subtotal_versi_angka;
            });

            // panggil #total_barang lalu value atau nilai nya diisi dengan value variabel total_barang
            $("#total_barang").val(total_barang);

            // panggil #total_harga lalu value atau nilai nya diisi dengan value variabel total_harga
            $("#total_harga").val(total_harga);

            // panggil fungsi update_total_harga_versi_rupiah lalu kirimkan value atau nilai variable total_harga agar input total_harga dan harus_bayar versi rupiah terisi
            update_total_harga_versi_rupiah(total_harga);
        };






        // jika #tombol_simpan_pembelian di click maka jalankan fungsi berikut
        $("#tombol_simpan_pembelian").on("click", function() {
            console.log("Oke");
            // jika value input #keterangan_pembelian sama dengan tidak ada
            if (!$("#keterangan_pembelian").val()) {
                // tampilkan notifikasi menggunakan sweetalert yang berisi pesan berikut
                Swal.fire("Input Keterangan Harus Diisi");
                // matikan kode dengan cara kembali agar kode dibawahnya tidak berjalan atau agar ajax tidak berjalan
                return;
            };

            // jika value input #tanggal_dan_waktu sama dengan tidak ada maka
            if (!$("#tanggal_dan_waktu").val()) {
                // tampilkan notifikasi menggunakan sweetalert yang berisi pesan berikut
                Swal.fire("Input tanggal dan waktu Harus Diisi");
                // matikan kode dibawah beserta ajax nya dengan cara return
                return;
            };

            // berisi array agar aku bisa menyimpan semua produk_id yang ada di table pembelian_detail.table_pembelian_detail, anggaplah berisi [1, 2]
            let semua_produk_id = [];
            let semua_kode_produk = [];
            let semua_nama_produk = [];
            let semua_harga_beli = [];
            let semua_jumlah = [];
            let semua_subtotal = [];


            // lakukan pengulangan terhadap semua .kode_produk
            // setiap .kode_produk, jalankan fungsi berikut, aku melakukan pengulangan karena pada baris pertama dan seterusnya, .kode_produk di buat di script
            $(".kode_produk").each(function() {
                // mulai pengulangan
                // berisi panggil tiap .kode_produk, lalu ambil semua value dari attribute data-produk-id
                let produk_id = $(this).data("produk-id");

                // panggil array semua_produk_id, dorong setiap value dari variable produk_id
                semua_produk_id.push(produk_id);

                // berisi panggil semua .kode_produk lalu ambil text nya
                let kode_produk = $(this).text();
                // panggil array semua_produk_id lalu dorong semua value variable kode_produk ke dalam array itu
                semua_kode_produk.push(kode_produk);
            });

            // console.log(semua_produk_id);

            // lakukan pengulangan terhadap semua .nama_produk
            // setiap .nama_produk, jalankan fungsi berikut, aku melakukan pengulangan karena pada baris pertama dan seterusnya, .nama_produk di buat di script
            $(".nama_produk").each(function() {
                // berisi panggil semua .nama_produk lalu ambil text nya
                let nama_produk = $(this).text();
                // panggil array semua_nama_produk lalu dorong semua value variable nama_produk ke dalam array itu
                semua_nama_produk.push(nama_produk);
            });

            // lakukan pengulangan terhadap semua .harga_beli
            // setiap .harga_beli, jalankan fungsi berikut, aku melakukan pengulangan karena pada baris pertama dan seterusnya, .harga_beli di buat di script
            $(".harga_beli").each(function() {
                // berisi panggil semua .harga_beli lalu ambil text nya, ubah string misalnya Rp 100.000 menjadi 100000
                let harga_beli = parseInt($(this).text().replace(/[^0-9]+/g,''));
                // panggil array semua_harga_beli lalu dorong semua value variable harga_beli ke dalam array itu
                semua_harga_beli.push(harga_beli);
            });

            // lakukan pengulangan terhadap semua .jumlah
            // setiap .jumlah, jalankan fungsi berikut, aku melakukan pengulangan karena pada baris pertama dan seterusnya, .jumlah di buat di script
            $(".jumlah").each(function() {
                // berisi panggil semua .jumlah lalu ambil value nya, lalu ubah string ke integer, misalnya "10" akan menjadi 10
                let jumlah = parseInt($(this).val().replace(/[^0-9]+/g,''));
                // panggil array semua_jumlah lalu dorong semua value variable jumlah ke dalam array itu
                semua_jumlah.push(jumlah);
            });

            // lakukan pengulangan terhadap semua .subtotal
            // setiap .subtotal, jalankan fungsi berikut, aku melakukan pengulangan karena pada baris pertama dan seterusnya, .subtotal di buat di script
            $(".subtotal").each(function() {
                // berisi panggil semua .subtotal lalu ambil text nya, ubah string misalnya "Rp 1.000.000" menjadi 1000000
                let subtotal = parseInt($(this).text().replace(/[^0-9]+/g,''));
                // panggil array semua_subtotal lalu dorong semua value variable subtotal ke dalam array itu
                semua_subtotal.push(subtotal);
            });

            // panggil #keterangan_pembelian lalu ambil value nya
            let keterangan_pembelian = $("#keterangan_pembelian").val();
            // panggil #tanggal_dan_waktu lalu ambil value nya
            let tanggal_dan_waktu = $("#tanggal_dan_waktu").val();
            // panggil #total_barang lalu ambil value nya
            let total_barang = $("#total_barang").val();
            // panggll #total_harga lalu ambil value nya
            let total_harga = $("#total_harga").val();
            
            // lakukan ajax untuk mengirim semua value input
            $.ajax({
                // url panggil route pembelian_detail.store
                url: "{{ route('pembelian_detail.store') }}",
                // tipe memanggil route tipe post / kirim
                type: 'POST',
                // kirimkan aata berupa object yang berisi key dan value
                data: {
                    // untuk keamanan dari serangan csrf
                    // key _token berisi cetak fungsi csrf_token()
                    "_token": "{{ csrf_token() }}",
                    // key semua_produk_id berisi value array semua_produk_id
                    semua_produk_id: semua_produk_id,
                    semua_kode_produk: semua_kode_produk,
                    semua_nama_produk: semua_nama_produk,
                    semua_harga_beli: semua_harga_beli,
                    semua_jumlah: semua_jumlah,
                    semua_subtotal: semua_subtotal,
                    total_barang: total_barang,
                    total_harga: total_harga,
                    keterangan_pembelian: keterangan_pembelian,
                    tanggal_dan_waktu: tanggal_dan_waktu
                }
            })
            // jika selesai dan berhasil maka jalankan fungsi berikut lalu ambil tanggapan nya
            .done(function(resp) {
                // jika value tanggapan.status sama dengan 200 maka
                if (resp.status === 200) {
                    // tampilkan notifikasi menggunakan package sweetalert
                    Swal.fire({
                        icon: 'success',
                        title: 'Bagus',
                        text: 'Berhasil menyimpan data pembelian.',
                    })
                    // kemudian hasilnya maka jalankan fungsi berikut dan ambil hasil nya
                    .then((result) => {
                        // berisi menangkap value resp.pembelian_id
                        let pembelian_id = resp.pembelian_id;

                        // jika aku click oke pada pop up sweetalert maka
                        // jika hasilnya dikonfirmasi maka
                        if (result.isConfirmed) {
                            // buka tab baru, panggil route pembelian_detail.index, _kosong 
                            window.open("{{ route('pembelian_detail.index') }}", "_blank");
                            // pindah rute
                            // berisi panggil url /pembelian/nota-kecil/ lalu kirimkan value variable pembelian_id
                            window.location.href = `/pembelian/nota-kecil/${pembelian_id}`;
                        };
                    });
                };
            });

        });
    </script>
@endpush
