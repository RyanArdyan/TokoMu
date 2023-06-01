{{-- memperluas parentnya yaitu layouts.app --}}
@extends('layouts.app')

{{-- dorong css lalu tangkap menggunakan $stack('css') --}}
@push('css')
@endpush

{{-- kirim value section title ke @Yield('title') --}}
@section('title', 'Produk')

{{-- kirim value section konten ke @yield('konten') --}}
@section('konten')
    <div class="row mb-2">
        <div class="col-sm-12 mt-2">
            {{-- termasuk ada jika modal dipanggil --}}
            {{-- panggil modal tambah --}}
            @includeIf('produk.modal_create')
            {{-- panggil modal edit --}}
            @includeIf('produk.modal_edit')

            {{-- jika aku click tombol Tambah produk maka panggil modal tambah --}}
            <button id="tombol_tambah" class="btn btn-purple btn-sm mb-4">
                <i class="mdi mdi-plus"></i>
                Tambah Produk
            </button>

            {{-- agar tablenya responsive --}}
            <div class="table-responsive">
                {{-- aku menyimpan table ke dalam form agar aku bisa mengambil data table lalu menghapus beberapa produk dan mencetak beberapa barcode --}}
                <form id="form_produk">
                    {{-- untuk keamanan --}}
                    @csrf
                    <table class="table table-striped table-sm">
                        <thead class="bg-primary">
                            <tr>
                                <th scope="col" width="5%">
                                    <input type="checkbox" name="select_all" id="select_all">
                                </th>
                                <th scope="col" width="5%">No</th>
                                <th scope="col">Nama Produk</th>
                                <th scope="col">Kode</th>
                                <th scope="col">Kategori</th>
                                <th scope="col">Penyuplai</th>
                                <th scope="col">Merk</th>
                                <th scope="col">Harga Beli</th>
                                <th scope="col">Diskon</th>
                                <th scope="col">Harga Jual</th>
                                <th scope="col">Stok</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </form>
            </div>

            <div class="mt-2">
                {{-- jika aku click tombol Tambah produk maka panggil modal produk --}}
                {{-- <button id="tombol_tambah" class="btn btn-purple btn-sm">
                    <i class="mdi mdi-plus"></i>
                    Tambah Produk
                </button> --}}

                {{-- Cetak Barcode --}}
                <button id="cetak_barcode" type="button" class="btn btn-success btn-flat btn-sm">
                    <i class="mdi mdi-barcode"></i>
                    Cetak Barcode
                </button>

                {{-- Fitur hapus beberapa produk berdasarkan kotak centang yang di checklist --}}
                <button id="tombol_hapus" type="button" class="btn btn-danger btn-flat btn-sm">
                    <i class="fa fa-trash"></i>
                    Hapus
                </button>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        // Ini berarti jika form nya dikirim maka hapus input mask nya, contoh Rp 1.000 akan menjadi 1000
        $(".input_angka").inputmask();

        // read data produk
        // berisi panggil element table, gunakan datatable
        let table = $("table").DataTable({
            // tampilkan processing, sebelum datanya di muat
            processing: true,
            // jika produk sudah lebih dari 10.000 maka loading nya tidak akan lama karena server side nya true
            serverSide: true,
            // lakukan ajax, panggil route produk.index
            ajax: "{{ route('produk.index') }}",
            // jika selesai dan berhasil maka buat element tbody, tr, td dan isi valuenya
            columns: [
                // buat kotak centang atau input type="checkbox"
                {
                    data: "select",
                    // aku tidak bisa menghilangkan icon anak panah di colmn pertama table tapi aku akan mematikan fungsi pembalik dari a ke z atau sebaliknya
                    sortable: false
                },
                {
                    // lakukan pengulangan terhadap nomor
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    // sortable: false, berarti aku menghilangkan icon anak panah agar aku tidak membalik data
                    sortable: false
                },
                // 
                {
                    data: 'nama_produk',
                    name: 'nama_produk'
                },
                {
                    data: 'kode_produk',
                    name: 'kode_produk'
                },
                {
                    data: 'nama_kategori',
                    // ini berarti relasi, produk berelasi dengan kategori
                    name: 'nama_kategori.nama_kategori'
                },
                {
                    data: 'nama_penyuplai',
                    name: 'nama_penyuplai.nama_penyuplai'
                },
                {
                    data: 'merk',
                    name: 'merk'
                },
                {
                    data: 'harga_beli',
                    name: 'harga_beli'
                },
                {
                    data: 'diskon',
                    name: 'diskon'
                },
                {
                    data: 'harga_jual',
                    name: 'harga_jual'
                },
                {
                    data: 'stok',
                    name: 'stok'
                },
                // tombol edit
                {
                    data: 'action',
                    name: 'action',
                    sortable: false,
                    // aku mematikan pencarian column yang  berisi tombol edit, jadi ketika aku mencari edit maka data kosong
                    searchable: false
                }
            ],
            // datatable nya akan menggunakan bahasa indonesia
            language: {
                url: "/terjemahan_datatable/indonesia.json"
            }
        });

        // hanya izinkan user memasukkan angka di input yang telah ditentukan
        function number(event) {
            let charCode = (event.which) ? event.which : event.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            } else {
                return true;
            };
        };

        // tampilkan modal tambah
        // jika #tombol_tambah di click maka jalankan fungsi berikut
        $("#tombol_tambah").on("click", function() {
            // lakukan ajax
            $.ajax({
                    // url ke route produk.data_relasinya karena aku akan mengambil semua kategori dan penyuplai terlebih dahulu
                    url: "{{ route('produk.data_relasinya') }}",
                    // panggil route tipe dapatkan
                    type: "GET"
                })
                // ketika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapannya
                .done(function(resp) {
                    // panggil #modal_tambah lalu modalnya tampilkan
                    $("#modal_tambah").modal("show");
                    // lakukan pengulangan sebanyak jumlah kategori
                    // value berisi semua value dari column kategori_id dan nama_kategori
                    $.each(resp.semua_kategori, function(key, value) {
                        // tambahkan element option untuk menampilkan semua kategori
                        // panggil #kategori_id lalu tambahkan element option yang berattribute value untuk dikirimkan valuenya
                        $(`#kategori_id`).append(
                            `<option value="${value.kategori_id}">${value.nama_kategori}</option>`);
                    });
                    // lakukan pengulangan sebanyak jumlah penyuplai
                    // value berisi semua value dari column penyuplai_id dan nama_penyuplai
                    $.each(resp.semua_penyuplai, function(key, value) {
                        // tambahkan element option untuk menampilkan semua penyuplai
                        // panggil #penyuplai_id lalu tambahkan element option yang berattribute value untuk dikirimkan valuenya
                        $("#penyuplai_id").append(
                            `<option value="${value.penyuplai_id}">${value.nama_penyuplai}</option>`
                        );
                    });
                });
        });

        // jika modal tambah dikirim
        // jika form tambah dikirim jalankan fungsi berikut dan ambil event atau acara nya
        $("#form_tambah").on("submit", function(e) {
            // cegah bawaan nya yaitu reload
            e.preventDefault();
            // lakukan ajax
            $.ajax({
                // url ke route produk.store
                url: "{{ route('produk.store') }}",
                // panggil route type POST
                type: "POST",
                // kirimkan data berupa baru FormulirData dari #form_tambah
                data: new FormData(this),
                // aku butuh 3 baris kode dibawah ini
                processData: false,
                contentType: false,
                cache: false,
                // sebelum formnya dikirim, hapus validsai error dulu
                beforeSend: function() {
                    // panggil .input hapus class is-invalid
                    $(".input").removeClass("is-invalid");
                    // panggil .pesan_error lalu kosongkan textnya
                    $(".pesan_error").text("");
                }
            })
            // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapnnya
            .done(function(resp) {
                // jika validasi menemukan error
                // jika tanggapan.status sama dengan 0
                if (resp.status === 0) {
                    // lakukan pengulangan terhadap value attribute name dan pesan errornya
                    // key berisi semua value attribute name 
                    // value berisi semua pesan error
                    $.each(resp.errors, function(key, value) {
                        // contohnya panggil .nama_produk_input lalu tambah .is-invalid
                        $(`.${key}_input`).addClass("is-invalid");
                        // anggaplah panggil .nama_produk_error lalu textnya diisi dengan pesan value index 0 atau pesan error
                        $(`.${key}_error`).text(value[0]);
                    });
                }
                // jika produk berhasil disimpan
                // lain jika tanggapan.status sama dengan 200
                else if (resp.status === 200) {
                    // reset formulir atau kosongkan semua value element input
                    // panggil #form_tambah, index 0, lalu atur ulang
                    $("#form_tambah")[0].reset();
                    // input nama produk di focuskan
                    // panggil #nama_produk lalu fokuskan
                    $("#nama_produk").focus();
                    // muat ulang table ajax
                    // panggil variabel table, ajaxnya kita reload
                    table.ajax.reload();
                    // notifikasi menggunakan toastr
                    toastr.success(`${resp.pesan}.`);
                };
            });
        });

        // Edit produk
        // jika document di click yang classnya adalah .tombol_edit maka jalankan fungsi berikut
        // pakai document karena .tombol_Edit dibuat di script
        $(document).on("click", ".tombol_edit", function(e) {
            // cegah bawaan nya yaitu reload
            e.preventDefault();
            // ambil nilai attr data-id
            // panggil .tombol_edit lalu ambil value dari attribute data-id, angaplah berisi angka 1
            let produk_id = $(this).attr("data-id");
            // lakukan ajax
            $.ajax({
                    // panggil route produk.show, lalu kirim produk_id
                    url: `/produk/${produk_id}`,
                    // panggil route tipe dapatkan
                    type: "GET",
                })
                // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapannya
                .done(function(resp) {
                    // tampilkan modal
                    // panggil #modal_edit lalu modalnya di tampilkan
                    $("#modal_edit").modal("show");

                    // tambahkan element option
                    // lakukan pengulangan sebanyak jumlah kategori
                    // value berisi semua value dari column kategori_id dan nama_kategori
                    $.each(resp.semua_kategori, function(key, value) {
                        // jika setiap kategori.kategori_id === resp.detail_produk.kategori_id
                        if (value.kategori_id === resp.detail_produk.kategori_id) {
                            // pnaggil #edit_kategori_id lalu tambah element option
                            $("#edit_kategori_id").append(
                                `<option value="${value.kategori_id}" selected>${value.nama_kategori}</option>`
                                );
                        }
                        // lain jika value.kategori_id tidak sama dengan resp.detail_produk.kategori_id
                        else if (value.kategori_id !== resp.detail_produk.kategori_id) {
                            $("#edit_kategori_id").append(
                                `<option value="${value.kategori_id}">${value.nama_kategori}</option>`
                                );
                        };
                    });

                    // tambahkan element option
                    // lakukan pengulangan sebanyak jumlah penyuplai
                    // value berisi semua value dari column penyuplai_id dan nama_penyuplai
                    $.each(resp.semua_penyuplai, function(key, value) {
                        // jika setiap penyuplai.penyuplai_id === resp.detail_produk.penyuplai_id
                        if (value.penyuplai_id === resp.detail_produk.penyuplai_id) {
                            // pnaggil #edit_penyuplai_id lalu tambah element option
                            $("#edit_penyuplai_id").append(
                                `<option value="${value.penyuplai_id}" selected>${value.nama_penyuplai}</option>`
                            );
                        }
                        // lain jika value.penyuplai_id tidak sama dengan resp.detail_produk.penyuplai_id
                        else if (value.penyuplai_id !== resp.detail_produk.penyuplai_id) {
                            $("#edit_penyuplai_id").append(
                                `<option value="${value.penyuplai_id}">${value.nama_penyuplai}</option>`
                            );
                        };
                    });

                    // panggil #edit_produk_id lalu input nya diisi dengan value resp.detail_produk.produk_id
                    $("#edit_produk_id").val(resp.detail_produk.produk_id);
                    $("#edit_nama_produk").val(resp.detail_produk.nama_produk);
                    $("#edit_merk").val(resp.detail_produk.merk);
                    $("#edit_harga_beli").val(resp.detail_produk.harga_beli);
                    $("#edit_diskon").val(resp.detail_produk.diskon);
                    $("#edit_harga_jual").val(resp.detail_produk.harga_jual);
                    $("#edit_stok").val(resp.detail_produk.stok);
                });

        });

        // ketika modal ditutup maka reset formulir, hapus semua option hapus error validasi
        // jika .tutup di click maka jalankan fungsi berikut
        $(".tutup").on("click", function() {
            // panggil element form, index 0, atur ulang
            $("form")[0].reset();
            // panggil element option, lalu hapus
            $("option").remove();
            // panggil .input lalu hapus class -is-invalid
            $(".input").removeClass("is-invalid");
            // panggil .pesan_error lalu kosongkan textnya
            $(".pesan_error").text("");
        });

        // Update produk
        // jika #form_edit dikirim maka jalankan fungsi berikut dan ambil eventnya
        $("#form_edit").on("submit", function(e) {
            // cegah bawaannya yaitu reload
            e.preventDefault();

            // ambil value dari #edit_produk_id
            let produk_id = $("#edit_produk_id").val();
            // lakukan ajax
            $.ajax({
                    // ke method update
                    // url ke /produk/ lalu kirim produk_id
                    url: `/produk/${produk_id}`,
                    // aku sudah mengubah tipe route menjadi PUT di formulir edit
                    type: "POST",
                    // kirim formulir data dari #form_edit
                    data: new FormData(this),
                    // aku butuh ketiga baris kode dibawah
                    processData: false,
                    contentType: false,
                    cache: false,
                    // hapus validasi error, sebelum formulir di kirim
                    // sebelum kirim, jalankan fungsi berikut
                    beforeSend: function() {
                        // panggil .input lalu hapus class is-invalid
                        $(".input").removeClass("is-invalid");
                        // panggil .pesan_error lalu kosognak textnya
                        $(".pesan_error").text("");
                    }
                })
                .done(function(resp) {
                    // jika validasi error
                    if (resp.status === 0) {
                        $.each(resp.errors, function(key, value) {
                            $(`.${key}_input`).addClass('is-invalid');
                            $(`.${key}_error`).text(value);
                        });
                        // jika validasi berhasil
                    } else if (resp.status === 200) {
                        // hapus option 
                        $("option").remove();
                        // tutup modal
                        $("#modal_edit").modal("hide");
                        // notifikasi menggunakan toastr AdminLTE
                        toastr.success(`${resp.pesan}`);
                        // muat ulang ajax pada table
                        table.ajax.reload();
                    };
                });
        });


        // Pilih semua
        $("[name=select_all]").on("click", function() {
            $(":checkbox").prop('checked', this.checked)
        })

        // Delete
        // #tombol_hapus di click maka jalankan fungsi berikut lalu ambil responsenya
        $("#tombol_hapus").on("click", function(e) {
            // jika input .pilih yang dicentang panjangnya lebih besar dari 0
            if ($("input.pilih:checked").length > 0) {
                // tampilkan konfirmasi
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Anda tidak akan dapat mengembalikan ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    // jika user click tombol "Yam hapus"
                    // jika hasilnya di konfirmasi
                    if (result.isConfirmed) {
                        // lakukan ajax type post, ke url /produk/hapus, lalu kirim data .form_produk
                        $.post('/produk/destroy', $('#form_produk').serialize())
                            // jika selesai, maka jalankan fungsi berikut dan ambil tanggapannya
                            .done(function(resp) {
                                // console.log(resp);
                                // notifkasi
                                Swal.fire(
                                    'Dihapus!',
                                    'Berhasil menghapus produk yang dipilih.',
                                    'success'
                                );
                                // reload ajax table
                                table.ajax.reload();
                            });
                    };
                });
            }
            // lain jika input.pilih yang di centang, panjangnya sama dengan 0
            else if ($("input.pilih:checked").length === 0) {
                // tampilkan notifikasi yang berisi pesan berikut
                Swal.fire('Anda belum memilih baris data');
            };
        });

        //  cetak barcode
        // jika #cetak_barcode di click maka jalankan fungsi berikut
        $("#cetak_barcode").on("click", function() {
            // jika user belum memilih produk untuk mencetak barode produk
            // jika input class pilih yang dicentang, panjangnya sama dengan 0
            if ($("input.pilih:checked").length == 0) {
                // tampilkan notifikasi menggunakan sweetalert
                Swal.fire('Silahkan pilih produk');
            }
            // jika input .pilih yang dicentang, panjang nya lebih besar atau sama dengan 3
            else {
                // panggil #form_produk, lalu attribute target diisi dengan blank
                $("#form_produk").attr({
                    // buat attribute dan isi attributenya
                    // _blank agar membuka tab baru
                    "target": "_blank",
                    // berisi panggil route produk.cetak_barcode
                    "action": "{{ route('produk.cetak_barcode') }}",
                    // panggi route type method menggunakan attribute html yaitu method yang berisi POST
                    "method": "POST",
                })
                // lalu kirim mengirim value input name="produks_ids", anggaplah berisi ["1", "2", "3"]
                // sebenarnya aku hanya mengirim value input name="produk_ids[]"
                .submit();
            };
        });
    </script>
@endpush
