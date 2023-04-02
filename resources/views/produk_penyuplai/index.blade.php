{{-- memperluas parentnya yaitu layouts.app --}}
@extends('layouts.app')

{{-- dorong css lalu tangkap menggunakan $stack('css') --}}
@push('css')
@endpush

{{-- kirim value section title ke @Yield('title') --}}
@section('title', 'Produk Penyuplai')

{{-- kirim value section konten ke @yield('konten') --}}
@section('konten')
    <div class="row mb-2">
        <div class="col-sm-12 mt-2">
            {{-- cetak detail_kategori_pertama --}}
            {{-- @if ()
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Anda harus membuat kategori di menu kategori terlebih dahulu</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif --}}


            {{-- termasuk ada jika modal dipanggil --}}
            {{-- panggil modal tambah --}}
            @includeIf('produk_penyuplai.modal_create')
            {{-- panggil modal edit --}}
            @includeIf('produk_penyuplai.modal_edit')

            {{-- termasuk produk_penyuplai.table --}}
            @include('produk_penyuplai.table')

            <div class="mt-2">
                {{-- jika aku click tombol Tambah produk Penyuplai maka panggil modal tambah --}}
                <button id="tombol_tambah" class="btn btn-purple btn-sm">
                    <i class="mdi mdi-plus"></i>
                    Tambah Produk Penyuplai
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
        // lakukan ajax untuk mengecek apakah ada kategori dan penyuplai jika tidak ada maka tampilkan notifikasi "Anda harus membuat kategori terlebih dahulu", lalu arahkan ke menu kategori
        $.ajax({
            // panggil route produk_penyuplai.cek_kategori_dan_penyuplai
            url: "{{ route('produk_penyuplai.cek_kategori_dan_penyuplai') }}",
            // panggil route tipe dapatkan
            type: "GET"
        })
            // jika selesai, maka jalankan fungsi berikut dan ambil tanggapan nya
            .done(function(resp) {
                // console.log(resp);
                // jika tanggapan.pesan sama dengan pesan berikut
                if (resp.message === 'Anda belum membuat satu pun kategori.') {
                    // tampilkan notifikasi menggunakan sweetalert
                    Swal.fire(
                        'Kategori belum ditambahkan!',
                        'Anda harus membuat minimal satu kategori terlebih dahulu!',
                        'error'
                    )
                    // jika user click oke maka kemudian jalankan fungsi berikut
                    .then(function() {
                        window.location = "{{ route('kategori.index') }}";
                    });
                };
                // // lain jika tanggapan.pesan sama dengan pesan berikut
                if (resp.pesan === 'Anda belum membuat satu pun penyuplai.') {
                    // tampilkan notifikasi menggunakan sweetalert
                    Swal.fire(
                        'Ada yang salah!',
                        'Anda harus membuat minimal satu penyuplai terlebih dahulu!',
                        'error'
                    )
                    // jika user click oke maka kemudian jalankan fungsi berikut
                    .then(function() {
                        window.location = "{{ route('penyuplai.index') }}";
                    });
                };
            });


        // Ini berarti jika form nya dikirim maka hapus input mask nya, contoh Rp 1.000 akan menjadi 1000
        $(".input_angka").inputmask();

        // read data produk penyuplai
        // berisi panggil element table, gunakan datatable
        let table = $("table").DataTable({
            // tampilkan processing, sebelum datanya di muat
            processing: true,
            // jika produk penyuplai sudah lebih dari 10.000 maka loading nya tidak akan lama karena server side nya true
            serverSide: true,
            // lakukan ajax, panggil route produk.read
            ajax: "{{ route('produk_penyuplai.read') }}",
            // jika selesai dan berhasil maka buat element tbody, tr, td dan isi valuenya
            columns: [
                // buat kotak centang
                {
                    data: "select",
                    // aku tidak bisa menghilangkan icon anak panah di colmn pertama table tapi aku akan mematikan fungsi pembalik dari a ke z atau sebaliknya
                    sortable: false
                },
                {
                    // lakukan pengulangan terhadap nomor
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    // sortable: false, brearti aku menghilangkan icon anak panah agar aku tidak membalik data
                    sortable: false
                },
                // 
                {
                    data: 'nama_produk',
                    name: 'nama_produk'
                },
                {
                    data: 'nama_kategori',
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
                    data: 'harga',
                    name: 'harga'
                },
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
                // berisi memanggil folder public/terjemahan_datatable
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
                    // url ke route produk_penyuplai.relasinya karena aku akan mengambil semua kategori dan penyuplai terlebih dahulu
                    url: "{{ route('produk_penyuplai.data_relasinya') }}",
                    // panggil route tipe dapatkan
                    type: "GET"
                })
                // ketika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapannya
                .done(function(resp) {
                    // show modal tambah
                    $("#modal_tambah").modal("show");
                    // lakukan pengulangan sebanyak jumlah kategori
                    // value berisi semua value dari column kategori_id dan nama_kategori milik table kategori
                    $.each(resp.semua_kategori, function(key, value) {
                        // tambahkan element option untuk menampilkan semua kategori
                        // panggil #kategori_id lalu tambahkan element option yang berattribute value untuk dikirimkan valuenya
                        $("#kategori_id").append(
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
        // jika form tambah dikirim jalankan fungsi berikut
        $("#form_tambah").on("submit", function(e) {
            // event cegah bawaan nya yaitu reload
            e.preventDefault();
            // lakukan ajax
            $.ajax({
                    // url ke route produk_penyuplai.store
                    url: "{{ route('produk_penyuplai.store') }}",
                    // panggil route type POST
                    type: "POST",
                    // kirimkan formulir data atau value semua input dari #form_tambah
                    data: new FormData(this),
                    // aku butuh 3 baris kode dibawah ini
                    processData: false,
                    contentType: false,
                    cache: false,
                    // sebelum formnya dikirim, hapus validsai error dulu
                    // sebelum kirim, jalankan fungsi berikut
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
                        // lakukan pengulangan terhadap semua value attribute name yang error dan semua pesan error nya
                        // key berisi semua value attribute name 
                        // value berisi semua pesan error
                        $.each(resp.errors, function(key, value) {
                            // contohnya panggil .nama_produk_input lalu tambah .is-invalid
                            $(`.${key}_input`).addClass("is-invalid");
                            // panggil .nama_produk_error lalu textnya diisi dengan pesan value index 0 atau pesan error
                            $(`.${key}_error`).text(value[0]);
                        });
                        // jika produk penyupalai berhasil disimpan
                        // lain jika tanggapan.status sama dengan 200
                    } else if (resp.status === 200) {
                        // reset formulir atau kosongkan semua value element input
                        // panggil #form_tambah, index 0, lalu atur ulang
                        $("#form_tambah")[0].reset();
                        // input nama produk di focuskan
                        // panggil #nama_produk lalu fokuskan
                        $("#nama_produk").focus();
                        // muat ulang data pada table produk penyuplai
                        // panggil variabel table, ajax nya kita reload
                        table.ajax.reload();
                        // notifikasi menggunakan toastr
                        // panggil package toastr, tipe sukses atau background warna hijau lalu cetak pesan nya
                        toastr.success(`${resp.pesan}.`);
                    };
                });
        });

        // Edit produk
        // jika document di click yang classnya adalah .tombol_edit maka jalankan fungsi berikut dan ambil event atau acara nya
        $(document).on("click", ".tombol_edit", function(e) {
            // event cegah bawaanya yaitu reload
            e.preventDefault();
            // ambil nilai attr data-id
            // panggil .tombol_edit lalu ambil value dari attribute data-id, angaplah berisi angka 1
            let produk_penyuplai_id = $(this).attr("data-id");
            // lakukan ajax
            $.ajax({
                    // panggil route produk_penyuplai.show, lalu kirim produk_id
                    url: `/produk-penyuplai/${produk_penyuplai_id}`,
                    // panggil route tipe dapatkan
                    type: "GET",
                })
                // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapannya
                .done(function(resp) {
                    // tampilkan modal edit produk_penyuplai
                    $("#modal_edit").modal("show");

                    // tambahkan element option
                    // lakukan pengulangan sebanyak jumlah kategori
                    // value berisi semua value dari column kategori_id dan nama_kategori
                    $.each(resp.semua_kategori, function(key, value) {
                        // jika setiap kategori.kategori_id === resp.detail_produk_penyuplai.kategori_id
                        if (value.kategori_id === resp.detail_produk_penyuplai.kategori_id) {
                            // pnaggil #edit_kategori_id lalu tambah element option
                            $("#edit_kategori_id").append(
                                `<option value="${value.kategori_id}" selected>${value.nama_kategori}</option>`
                            );
                        }
                        // lain jika value.kategori_id tidak sama dengan resp.detail_produk_penyuplai.kategori_id
                        else if (value.kategori_id !== resp.detail_produk_penyuplai.kategori_id) {
                            $("#edit_kategori_id").append(
                                `<option value="${value.kategori_id}">${value.nama_kategori}</option>`
                            );
                        };
                    });

                    // tambahkan element option
                    // lakukan pengulangan sebanyak jumlah penyuplai
                    // value berisi semua value dari column penyuplai_id dan nama_penyuplai
                    $.each(resp.semua_penyuplai, function(key, value) {
                        // jika setiap penyuplai.penyuplai_id === resp.detail_produk_penyuplai_penyuplai.penyuplai_id
                        if (value.penyuplai_id === resp.detail_produk_penyuplai.penyuplai_id) {
                            // pnaggil #edit_penyuplai_id lalu tambah element option
                            $("#edit_penyuplai_id").append(
                                `<option value="${value.penyuplai_id}" selected>${value.nama_penyuplai}</option>`
                            );
                        }
                        // lain jika value.penyuplai_id tidak sama dengan resp.detail_produk_penyuplai.penyuplai_id
                        else if (value.penyuplai_id !== resp.detail_produk_penyuplai.penyuplai_id) {
                            $("#edit_penyuplai_id").append(
                                `<option value="${value.penyuplai_id}">${value.nama_penyuplai}</option>`
                            );
                        };
                    });

                    // panggil #edit_produk_id lalu input nya diisi dengan value resp.detail_produk_penyuplai.produk_id
                    $("#edit_produk_penyuplai_id").val(resp.detail_produk_penyuplai.produk_penyuplai_id);
                    $("#edit_nama_produk").val(resp.detail_produk_penyuplai.nama_produk);
                    $("#edit_merk").val(resp.detail_produk_penyuplai.merk);
                    $("#edit_harga").val(resp.detail_produk_penyuplai.harga);
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
            let produk_penyuplai_id = $("#edit_produk_penyuplai_id").val();
            // lakukan ajax
            $.ajax({
                    // ke method update
                    // url ke /produk-penyuplai/ lalu kirim produk_id
                    url: `/produk-penyuplai/${produk_penyuplai_id}`,
                    // aku sudah mengubah tipe route menjadi PUT di formulir edit
                    type: "POST",
                    // kirim formulir data atau semua value input2x dam select dari #form_edit
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
                        // panggil .pesan_error lalu kosongkan text nya
                        $(".pesan_error").text("");
                    }
                })
                // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapan nya
                .done(function(resp) {
                    // jika validasi formulir menemukan error
                    if (resp.status === 0) {
                        // lakukan pengulangan kepada resp.errors
                        // paramter key berisi semua value attribute name yang error
                        // parameter value berisi semua pesan error nya
                        $.each(resp.errors, function(key, value) {
                            // misalnya, panggil .nama_produk_input lalu tambah class is-invalid
                            $(`.${key}_input`).addClass('is-invalid');
                            // misalnya, panggil .nama_produk_error lalu isi text nya dengan pesan error nya dengan cara memanggil parameter value
                            $(`.${key}_error`).text(value);
                        });
                        // jika validasi berhasil
                    } else if (resp.status === 200) {
                        // hapus option 
                        // panggil semua element option lalu hapus mereka
                        $("option").remove();
                        // tutup modal
                        // pangil @modal_edit lalu modal nya di tutup
                        $("#modal_edit").modal("hide");
                        // notifikasi menggunakan toastr AdminLTE
                        // panggil package toastr, tipe sukses atau background warna hijau lalu cetak resp.pesan
                        toastr.success(`${resp.pesan}`);
                        // muat ulang ajax pada table
                        // pangil variable table lalu ajax nya di muat ulang
                        table.ajax.reload();
                    };
                });
        });


        // Pilih semua
        $("[name=select_all]").on("click", function() {
            $(":checkbox").prop('checked', this.checked)
        })

        // Delete
        // #tombol_hapus di click maka jalankan fungsi berikut
        $("#tombol_hapus").on("click", function() {
            // jika input.pilih yang di centang, panjangnya sama dengan 0
            if ($("input.pilih:checked").length === 0) {
                // tampilkan notifikasi yang berisi pesan berikut
                Swal.fire('Anda belum memilih baris data');
            }
            // lain jika input .pilih yang dicentang panjangnya lebih besar dari 0
            else if ($("input.pilih:checked").length > 0) {
                // tampilkan konfirmasi
                Swal.fire({
                        title: 'Apakah anda yakin?',
                        text: "Anda tidak akan dapat mengembalikan ini!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!'
                    })
                    // kemudian hasil nya, jalankan fungsi berikut
                    .then((result) => {
                        // jika user click tombol "Ya, hapus"
                        // jika hasilnya di konfirmasi
                        if (result.isConfirmed) {
                            // lakukan ajax type post, ke url /produk/destroy, lalu kirim data .form_produk atau aku mengirim value input name="produk_penyuplai_ids[]", anggaplah aku mengirim ["1", "2"]
                            $.post("{{ route('produk_penyuplai.destroy') }}", $('#form_produk_penyuplai')
                                    .serialize())
                                // jika selesai, maka jalankan fungsi berikut dan ambil tanggapan nya
                                .done(function(resp) {
                                    // console.log(resp);
                                    // notifkasi menggunakan package sweetalert
                                    Swal.fire(
                                        'Dihapus!',
                                        'Berhasil menghapus produk penyuplai yang dipilih.',
                                        'success'
                                    );
                                    // reload ajax table
                                    // panggil variabel table lalu ajax nya di muat ulang
                                    table.ajax.reload();
                                });
                        };
                    });
            }
        });
    </script>
@endpush
