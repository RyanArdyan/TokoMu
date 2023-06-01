{{-- memperluas parentnya yaitu layouts.app --}}
@extends('layouts.app')

{{-- dorong css lalu tangkap menggunakan $stack('css') --}}
@push('css')
@endpush

{{-- kirim value section title ke @Yield('title') --}}
@section('title', 'Pengeluaran')

{{-- kirim value section konten ke @yield('konten') --}}
@section('konten')
    <!-- Content Wrapper. Contains page content -->
    <div class="row mb-2">
        <div class="col-md-12 mt-2">
            {{-- termasuk ada jika modal dipanggil --}}
            {{-- panggil modal tambah --}}
            @includeIf('pengeluaran.modal_create')
            {{-- panggil modal edit --}}
            @includeIf('pengeluaran.modal_edit')

            {{-- termasuk element table dan form --}}
            @include('pengeluaran.table')

            {{-- import pengeluran dari file excel --}}
            <form action="{{ route('pengeluaran.import_excel') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                @csrf
                <input type="file" name="file" class="form-control">
                <button class="btn btn-info" class="form-control">Upload</button>
            </form>


            <div class="mt-2">
                {{-- jika aku click tombol pengeluaran baru maka panggil modal pengeluaran --}}
                <button id="tombol_pengeluaran_baru" class="btn btn-purple btn-sm">
                    <i class="mdi mdi-minus"></i>
                    Pengeluaran Baru
                </button>

                {{-- Fitur hapus beberapa pengeluaran berdasarkan kotak centang yang di checklist --}}
                <button id="tombol_hapus" type="button" class="btn btn-danger btn-flat btn-sm">
                    <i class="mdi mdi-delete"></i>
                    Hapus
                </button>

                {{-- export semua data pengeluran ke file excel --}}
                {{-- panggil route pengeluaran.export_excel --}}
                <a href="{{ route('pengeluaran.export_excel') }}" class="btn btn-sm btn-success">
                    <i class="mdi mdi-file-excel"></i> Excel</a>
            </div>

        </div>
    </div>
@endsection

@push('script')
<script>
    // package Input Mask - Robin Herbots
    // aku perlu ini agar Rp 1.000 akan menjadi 1000 ketika sudah di controller
    // 1000 akan menjadi 1.000
    $(".input_angka").inputmask();

    // read daftar pengeluaran
    // berisi panggil table pengeluaran, gunakan datatable
    let table = $("table").DataTable({
            // ketika data masih di muat, tampilkan animasi processing
            // processing: benar
            processing: true,
            // serverSide digunakan agar ketika data sudah lebih dari 10.000 maka web masih lancar
            // sisi server: benar
            serverSide: true,
            // lakukan ajax, ke route pengeluaran.read yang tipe nya adalah dapatkan
            ajax: "{{ route('pengeluaran.read') }}",
            // jika berhasil maka buat element <tbody>, <tr> dan <td> lalu isi td nya dengan data pengeluaran
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
                    data: 'tanggal_pengeluaran',
                    name: 'tanggal_pengeluaran'
                },
                {
                    data: 'nama_pengeluaran',
                    name: 'nama_pengeluaran'
                },
                {
                    data: 'total_pengeluaran',
                    name: 'total_pengeluaran'
                },
                {
                    data: 'action',
                    name: 'action',
                    sortable: false,
                    searchable: false
                }
            ],
            // menggunakan bahasa indonesia di package datatables
            // bahasa berisi object
            language: {
                // url memanggil folder public/
                url: "/terjemahan_datatable/indonesia.json"
            }
        });

    // hanya izinkan user memasukkan angka di input yang telah di tentukan
    function number(event) {
        let charCode = (event.which) ? event.which : event.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        } else {
            return true;
        };
    };

    // tampilkan modal tambah
    // jika #tombol_pengeluaran_baru di click maka jalankan fungsi berikut
    $("#tombol_pengeluaran_baru").on("click", function() {
        // show modal tambah
        // panggil #modal_tambah lalu modalnya di tampilkan
        $("#modal_tambah").modal("show");
    });

    // ketika modal tambah ditutup maka reset formulir, dan hapus error validasi
    $(".tutup").on("click", function() {
        // reset formulir
        // panggil element form, index 0 lalu di atur ulang
        $("form")[0].reset();
        // panggil .input lalu hapus class is-invalid
        $(".input").removeClass("is-invalid");
        // panggil .pesan_error lalu text nya dikosongkan
        $(".pesan_error").text("");
    });

    // jika modal tambah dikirim
    // jika #form_tambah di kirim maka jalankan fungsi berikut, lalu kirimkan acaranya
    $("#form_tambah").on("submit", function(e) {
        // event cegah bawaanya yaitu reload
        e.preventDefault();
        // JQuery lakukan ajax
        $.ajax({
            // url panggil route pengeluaran.store
            url: "{{ route('pengeluaran.store') }}",
            // panggil route tipe POST
            type: "POST",
            // kirimkan data formulir dari #form_tambah
            data: new FormData(this),
            // aku butuh 2 baris kode berikut
            processData: false,
            contentType: false,
            // hapus validasi error sebelum formulir di kirim
            // sebelum kirim, jalankan fungsi berikut
            beforeSend: function() {
                // panggil .input lalu hapus class is-invalid
                $(".input").removeClass("is-invalid");
                // panggil .pesan_error lalu textnya di kosongkan
                $(".pesan_error").text("");
            }
        })
            // jika selesai dan berhasil maka jalankan fungsi berikut dan kemudian ambil tanggapannya
            .done(function(resp) {
                // jika validasi formulir menemukan error
                // jika tanggapan.status sama dengan 0
                if (resp.status === 0) {
                    // lakukan pengulangan
                    // key berisi semua nilai attribute name yang error
                    // value berisi array yang menyimpan semua pesan error
                    $.each(resp.errors, function(key, value) {
                        // contohnya panggil .nama_pengeluaran_input lalu tambah class is-invalid
                        $(`.${key}_input`).addClass("is-invalid");
                        // contohnya panggil .nama_pengeluaran_error lalu isi textnya dengan pesan error nya dengan cara memanggil parameter value, index ke 0
                        $(`.${key}_error`).text(value[0]);
                    });
                } 
                // lain jika tanggapan.status sama dengan 200
                else if (resp.status === 200) {
                    // reset formulir atau kosongkan value semua input
                    // panggil #form_tambah, index ke 0, di atur ulang
                    $("#form_tambah")[0].reset();
                    // nama pengeluaran di focuskan
                    // panggil #nama_pengeluaran
                    $("#nama_pengeluaran").focus();
                    // muat ulang table ajax
                    // panggil variabel table lalu ajaxnya di muat ulang
                    table.ajax.reload();
                    // notifikasi
                    // panggil package toastr, gunakan tipe sukses atau background warna hijau lalu panggil resp.pesan
                    toastr.success(resp.pesan);
                };
            });
    });

    // Edit pengeluaran
    // jika document di click yang id nya adalah .tombol_edit maka jalankan fungsi berikut dan ambil eventnya
    $(document).on("click", ".tombol_edit", function(e) {
        // event cegah bawaan
        e.preventDefault();
        // ambil nilai attr data-id
        // berisi panggil #tombol_edit lalu ambil value attribute data-id
        let pengeluaran_id = $(this).attr('data-id');
        // lakukan ajax
        $.ajax({
            // ke method show
            // panggil url /pengeluaran/ lalu kirimkan pengeluaran_id
            // aku mengirim pengeluaran_id lewat url
            url: "/pengeluaran/" + pengeluaran_id,
            // panggil route tipe dapatkan
            type: "GET",
        })
            // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tangapannya
            .done(function(resp) {
                // tampilkan modal
                // panggil #modal_edit lalu modalnya di tampilkan
                $("#modal_edit").modal("show");
                // panggil #e_pengeluaran_id lalu value nya diisi dengan resp.detail_pengeluaran.pengeluaran_id
                $("#e_pengeluaran_id").val(resp.detail_pengeluaran.pengeluaran_id);
                $("#e_nama_pengeluaran").val(resp.detail_pengeluaran.nama_pengeluaran);
                $("#e_total_pengeluaran").val(resp.detail_pengeluaran.total_pengeluaran);
                
            });

    });

    // ketika modal edit ditutup maka reset formulir, dan hapus error validasi
    $(".e_tutup").on("click", function() {
        $("#form_edit")[0].reset();
        $(".e_input").removeClass("is-invalid");
        $(".e_pesan_error").text("");
    });

    // Update
    // jika #form_edit di kirim, maka jalankan fungsi berikut dan ambil event nya
    $("#form_edit").on("submit", function(e) {
        // event cegah bawaan nya yaitu reload
        e.preventDefault();
        // berisi panggil #e_pengeluaran_id lalu ambil value nya
        let pengeluaran_id = $("#e_pengeluaran_id").val();
        // lakukan ajax
        $.ajax({
            // ke method update
            // panggil url /pengeluaran/ lalu kirimkan pengeluaran_id
            url: `/pengeluaran/${pengeluaran_id}`,
            // panggil route tipe PUT karena sudah aku paksa ubah di modal edit
            type: "POST",
            // kirimkan formulir data dari #form_edit
            data: new FormData(this),
            // aku butuh ketiga baris kode di bawah ini
            processData: false,
            contentType: false,
            cache: false,
            // hapus validasi error sebelum formulir di kirim
            // sebelum kirim, jalankan fungsi berikut
            beforeSend: function() {
                // panggil .e_input lalu hapus class is-invalid
                $(".e_input").removeClass("is-invalid");
                // panggil .e_pesan_error lalu kosongkan text nya
                $(".e_pesan_error").text("");
            }
        })
        // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tangggapannya
        .done(function(resp) {
            // jika validasi error
            // jika tangggapan.status sama dengan 0
            if (resp.status === 0) {
                // tampilkan validasi error
                // lakukan pengulangan kepada resp.error
                // key berisi semua value attribute name yang error, value berisi pesan error xznya
                // setiap resp.errors, jalankan fungsi berikut, parameter key dan value
                $.each(resp.errors, function(key, value) {
                    // contohnya panggil .e_nama_pengeluaran_input lalu tambah class is-invalid
                    $(`.e_${key}_input`).addClass('is-invalid');
                    // contohnya panggil .e_nama_pengeluaran_error lalu isi textnya dengan paramter value
                    $(`.e_${key}_error`).text(value);
                });
                // jika validasi berhasil
            } else if (resp.status === 200) {
                // tutup modal
                // panggil #modal_edit lalu modalnya di tutup
                $("#modal_edit").modal("hide");
                // notifikasi menggunakan toastr AdminLTE
                // panggil package toastr, tipe sukses atau background hijau lalu tempilkan pesan milik resp.pesan
                toastr.success(`${resp.pesan}`);
                // muat ulang ajax pada table
                // panggil variable table, lalu ajax nya di muat ulang
                table.ajax.reload();
            };
        });
    });


    // pilih semua
    $("#select_all").on("click", function() {
        if ($("#select_all").prop("checked")) {
            $(".pilih").prop("checked", true);
        } else {
            $(".pilih").prop("checked", false);
        };
    });



    // Deletejika 
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
                    // sebenarnya aku mengirim beberapa value input name="pengeluaran_ids" yang di centang
                    // jquery lakukan ajax tipe kirim, ke url /pengeluarn/destroy, panggil #form_pengeluaran, kirimkan value input
                    $.post('/pengeluaran/destroy', $('#form_pengeluaran').serialize())
                        // 
                        .done(function(resp) {
                            // notifkasi
                            Swal.fire(
                                'Dihapus!',
                                'Berhasil menghapus pengeluaran yang dipilih.',
                                'success'
                            );
                            // reload ajax table
                            table.ajax.reload();
                        });
                };
            });
        };
    });
</script>
@endpush
