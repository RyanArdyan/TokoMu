    {{-- memperluas parentnya yaitu layouts.app --}}
    @extends('layouts.app')

    {{-- dorong css lalu tangkap menggunakan $stack('css') --}}
    @push('css')
    @endpush

    {{-- kirim value section title ke @Yield('title') --}}
    @section('title', 'siswa')

    {{-- kirim value section konten ke @yield('konten') --}}
    @section('konten')
        <!-- Content Wrapper. Contains page content -->
        <div class="row mb-2">
            <div class="col-md-12 mt-2">

                {{-- termasuk element table dan form --}}
                @include('siswa.table')

                @include('siswa.modal_create')
                @include('siswa.modal_edit')

                <div class="mt-2">
                    <button id="tombol_tambah" class="btn btn-purple btn-sm">
                        <i class="mdi mdi-plus"></i>
                        Tambah Siswa
                    </button>

                    {{-- Fitur hapus beberapa siswa berdasarkan kotak centang yang di checklist --}}
                    <button id="tombol_hapus" type="button" class="btn btn-danger btn-flat btn-sm">
                        <i class="mdi mdi-delete"></i>
                        Hapus
                    </button>
                </div>

            </div>
        </div>
    @endsection

    @push('script')
    <script>
        // read daftar siswa
        // berisi panggil table siswa, gunakan datatable
        let table = $("table").DataTable({
            // ketika data masih di muat, tampilkan animasi processing
            // processing: benar
            processing: true,
            // serverSide digunakan agar ketika data sudah lebih dari 10.000 maka web masih lancar
            // sisi server: benar
            serverSide: true,
            // lakukan ajax, ke route siswa.read yang tipe nya adalah dapatkan
            ajax: "{{ route('siswa.read') }}",
            // jika berhasil maka buat element <tbody>, <tr> dan <td> lalu isi td nya dengan data siswa
            // kolom-kolom berisi array, di dalamnya ada object
            columns: [
                // kotak centang
                {
                    data: "select",
                    // menonaktifkan fungsi icon anak panah atau fitur balikkan data
                    sortable: false
                },
                // lakukan pengulangan
                // DT_RowIndex di dapatkan dari laravel datatable
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    sortable: false
                },
                {
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'jurusan',
                    name: 'jurusan'
                },
                {
                    data: 'gender',
                    name: 'gender'
                },
                {
                    // berisi tombol edit
                    data: 'action',
                    name: 'action',
                    sortable: false,
                    // searchable agar keyword edit tidak dapat dicari
                    searchable: false
                }
            ],
            language: {
                url: "/terjemahan_datatable/indonesia.json"
            }
        });

        // pilih semua
        // jika #pilih_semua di click maka jalankan fungsi berikut
        $("#select_all").on("click", function() {
            // jika #pilih_semua di centang maka
            if ($("#select_all").prop("checked")) {
                // panggil .pilih lalu centang nya benar
                $(".pilih").prop("checked", true);
            }
            // jika #pilih_semua tidak di centang maka
            else {
                // panggil .pilih lalu centang nya dihapus atau salah
                $(".pilih").prop("checked", false);
            };
        });

        // Delete
        // jika #tombol_hapus di click maka jalankan fungsi berikut dan ambil event nya
        $("#tombol_hapus").on("click", function(e) {
            // jika input .pilih yang di centang panjang nya sama dengan 0 maka
            if ($("input.pilih:checked").length === 0) {
                // tampilkan notifikasi menggunakan sweetalert yang menyatakan pesan berikut
                Swal.fire('Anda belum memilih baris data');
            }
            // jika input .pilih yang di centang panjang nya lebih atau sama dengan 1 maka
            else if ($("input.pilih:checked").length >= 1) {
                // tampilkan konfirmasi penghapusan menggunakan sweetalert
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Anda tidak akan dapat mengembalikan ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                })
                // kmeudian hasilnya, jalankan fungsi berikut, parameter result
                .then((result) => {
                    // jika hasilnya di konfirmasi
                    if (result.isConfirmed) {
                        // .serialize akan mengirimkan semua data pada table karena table disimpan di dalam form
                        // sebenarnya aku mengirim beberapa value input name="siswa_ids" yang di centang
                        // jquery lakukan ajax tipe kirim, ke url /pengeluarn/destroy, panggil #form_siswa, kirimkan value input
                        $.post('/siswa/destroy', $('#form_siswa').serialize())
                            //
                            .done(function(resp) {
                                // notifkasi
                                Swal.fire(
                                    'Dihapus!',
                                    'Berhasil menghapus siswa yang dipilih.',
                                    'success'
                                );
                                // reload ajax table
                                table.ajax.reload();
                            });
                    };
                });
            };
        });

        $("#tombol_tambah").on("click", function() {
            $("#modal_tambah").modal("show");
        });

        $("#form_tambah").on("submit", function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('siswa.store') }}",
                type: "POST",
                data: new FormData(this),
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function() {
                    $(".input").removeClass("is-invalid");
                    $(".pesan_error").text("");
                }
            })
            .done(function(resp) {
                if (resp.status === 0) {
                    $.each(resp.errors, function(key, value) {
                        $(`.${key}_input`).addClass("is-invalid");
                        $(`.${key}_error`).text(value[0]);
                    });
                }
                else if (resp.status === 200) {
                    $("#form_tambah")[0].reset();
                    $("#nama").focus();
                    table.ajax.reload();
                    toastr.success(`${resp.pesan}.`);
                };
            });
        });

        $(document).on("click", ".tombol_edit", function(e) {
            e.preventDefault();
            let siswa_id = $(this).attr('data-id');
            $.ajax({
                    url: "/siswa/" + siswa_id,
                    type: "GET"
                })
                .done(function(resp) {
                    $("#modal_edit").modal("show");
                    $("#e_siswa_id").val(resp.siswa_id);
                    $("#e_nama").val(resp.detail_siswa.nama);
                    $("#e_usia").val(resp.detail_siswa.usia);
                    $("#e_alamat").val(resp.detail_siswa.alamat);
                    $("#e_gender").val(resp.detail_siswa.gender);
                });

        });


        $("#form_edit").on("submit", function(e) {
            e.preventDefault();
            console.log("Oke");
            let siswa_id = $("#e_siswa_id").val();
            console.log(siswa_id);
            $.ajax({
                    url: `/siswa/${siswa_id}`,
                    type: "POST",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function() {
                        $(".e_input").removeClass("is-invalid");
                        $(".e_pesan_error").text("");
                    }
                })
                // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapannya
                .done(function(resp) {
                    console.log(resp);
                    // jika validasi menemukan error
                    // jika tanggapan status nya sama dengan 0
                    if (resp.status === 0) {
                        // lakukan pengulangan terhadap semua value attribute name yang error dan semua pesan erronya
                        // key berisi semua value attribute name yang error
                        // value berisi semua pesan errornya
                        $.each(resp.errors, function(key, value) {
                            // contohnya, panggil .e_nama_siswa_input lalu tambah class .is-invalid
                            $(`.e_${key}_input`).addClass('is-invalid');
                            // contohnya, panggil .e_nama_siswa_error lalu isi textnya menggunakan pesan error
                            $(`.e_${key}_error`).text(value);
                        });
                    }
                    // jika validasi berhasil dan aku berhasil memperbarui siswa
                    // lain jika tanggapan.status sama dengan 200
                    else if (resp.status === 200) {
                        // tutup modal
                        // panggil #modal_edit lalu modalnya ditutup
                        $("#modal_edit").modal("hide");
                        // notifikasi menggunakan toastr AdminLTE
                        // toastr tipe sukses berisi panggil resp.pesan
                        toastr.success(`${resp.pesan}`);
                        // muat ulang ajax pada table
                        // panggil variable table, ajaxnya di muat ulang
                        table.ajax.reload();
                    };
                });
        });


        $("#tombol_hapus").on("click", function(e) {
            if ($("input.pilih:checked").length === 0) {
                // tampilkan notifikasi
                Swal.fire('Anda belum memilih siswa');
            }
            // jika input yang di centang adalah class pilih yang dicentang, panjang nya lebih besar dari 0 berarti minimal 1
            else if ($("input.pilih:checked").length > 0) {
                // konfirmasi menggunakan sweetalert
                Swal.fire({
                        title: 'Apakah anda yakin?',
                        text: "Anda tidak akan dapat mengembalikan ini!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!'
                    })
                    // .kemudian, hasilnya
                    .then((result) => {
                        // jika kemudian hasilnya di konfirmasi
                        if (result.isConfirmed) {
                            // alasan menggunakan syntax ini adalah karena input name berisi siswa_ids[]
                            // .serialize akan mengirimkan semua data pada table karena table disimpan di dalam form
                            $.post('{{ route('siswa.destroy') }}', $('#form_siswa').serialize())
                                .done(function(resp) {
                                    // notifkasi
                                    Swal.fire(
                                        'Dihapus!',
                                        'Berhasil menghapus siswa yang dipilih.',
                                        'success'
                                    );
                                    // reload ajax table
                                    table.ajax.reload();
                                });
                        };
                    });
            }
        });
    </script>
    @endpush
