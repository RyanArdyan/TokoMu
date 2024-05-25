{{-- memperluas parentnya yaitu layouts.app --}}
@extends('layouts.app')

{{-- dorong css lalu tangkap menggunakan $stack('css') --}}
@push('css')
@endpush

{{-- kirim value section title ke @Yield('title') --}}
@section('title', 'Member')

{{-- kirim value section konten ke @yield('konten') --}}
@section('konten')
    <!-- Content Wrapper. Contains page content -->
    <div class="row mb-2">
        <div class="col-md-12 mt-2">
            {{-- termasuk ada jika modal dipanggil --}}
            {{-- panggil modal tambah --}}
            @includeIf('member.modal_create')
            {{-- panggil modal edit --}}
            @includeIf('member.modal_edit')

            {{-- termasuk element table dan form --}}
            @include('member.table')

            <div class="mt-2">
                {{-- jika aku click tombol Tambah member maka panggil modal member --}}
                <button id="tombol_tambah" class="btn btn-purple btn-sm">
                    <i class="mdi mdi-plus"></i>
                    Tambah Member
                </button>

                {{-- Cetak Kartu Member --}}
                <button id="cetak_kartu_member" type="button" class="btn btn-success btn-sm">
                    <i class="mdi mdi-card-account-details"></i>
                    Cetak Kartu
                </button>

                {{-- Fitur hapus beberapa member berdasarkan kotak centang yang di checklist --}}
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
        // read atau baca data table member
        // berisi panggil element table lalu gunakan datatable
        let table = $("table").DataTable({
            // ketika data masih proses dimuat maka ada efek processing
            // prosesnya benar
            processing: true,
            // gunakan serverSide agar ketika memebr sudah lebih dari 10.000 maka masih lancar
            // sisi server nya benar
            serverSide: true,
            // lakukan ajax, ke route member.read
            ajax: "{{ route('member.read') }}",
            // jika berhasil
            columns: [{
                    // berisi penngulangan kotak centang
                    data: "select",
                    // sortable akan menghilangkan icon anak panah yang berfungsi membalikkan urutan data
                    sortable: false
                },
                {
                    // DT_RowIndex di dapatkan dari code yajra/laravel-datatables
                    // aku mengambil pengulangan nomor dari addIndexColumn milik MemberController
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    // sortable: false
                },
                {
                    // berisi pengulangan semua value column kode_member
                    data: 'kode_member',
                    name: 'kode_member'
                },
                {
                    data: 'nama_member',
                    name: 'nama_member'
                },
                {
                    data: 'telepon_member',
                    name: 'telepon_member'
                },
                {
                    data: 'alamat_member',
                    name: 'alamat_member'
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
            // datatables nya menggunakan bahasa indonesia
            language: {
                // panggil folder public/terjemahan_datatable
                url: "/terjemahan_datatable/indonesia.json"
            }
        });

        // hanya izinkan user memasukkan angka, jadi huruf tidak boleh
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
            // show modal tambah
            // panggil #modal_tambah, lalu modalnya di munculkan
            $("#modal_tambah").modal("show");
        });

        // ketika modal tambah ditutup maka reset formulir, dan hapus error validasi
        $(".tutup").on("click", function() {
            $("form")[0].reset();
            $(".input").removeClass("is-invalid");
            $(".pesan_error").text("");
        });

        // jika modal tambah dikirim
        // jika #form_tambah di kirim maka jalankan fungsi berikut dan ambil eventnya
        $("#form_tambah").on("submit", function(e) {
            // cegah bawaanya yaitu reload
            e.preventDefault();
            // lakukan ajax
            $.ajax({
                // url memanggil route member.store
                url: "{{ route('member.store') }}",
                // berisi memanggil route tipe POST
                type: "POST",
                // berisi kirimkan formulir data dari #form_tambah, this adalah semua value dari input dari #form_data
                data: new FormData(this),
                // aku memanggil ketiga baris kode dibawah
                processData: false,
                contentType: false,
                cache: false,
                // hapus validasi error atau effect validasi error
                // sebelum kirim, jalankan fungsi berikut
                beforeSend: function() {
                    // panggil .input lalu hapus class is-invalid
                    $(".input").removeClass("is-invalid");
                    // panggil .pesan_error lalu kosongkan textnya
                    $(".pesan_error").text("");
                }
            })
            // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapannya
            .done(function(resp) {
                // jika user tidak memasukkan data yang benar di formulir maka tampilkan validasi error
                // jika value tanggapan.status sama dengan 0
                if (resp.status === 0) {
                    // lakukan pengulangan terhadap tangapan.errors lalu jalankan fungsi berikut
                    // key berisi semua value attribute name yang error
                    // value berisi semua pesan error
                    $.each(resp.errors, function(key, value) {
                        // contohnya panggil .nama_member_input lalu tambah class is-invalid
                        $(`.${key}_input`).addClass("is-invalid");
                        // contohnya panggil .nama_member_error lalu isi textnya dengan pesan errornya dengan cara parameter value index 0
                        $(`.${key}_error`).text(value[0]);
                    });
                }
                // lain jika user memasukkan nomor telepon indonesia yang salah
                // lain jika resp.message sama dengan 'Tolong masukkan nomor handphone indonesia yang benar.'
                else if (resp.message === 'Tolong masukkan nomor handphone indonesia yang benar.') {
                    // pangil #telepon_member lalu tambah class is-invalid
                    $("#telepon_member").addClass('is-invalid');
                    // panggil .telepon_member_error lalu text nya di isi pesan berikut
                    $('.telepon_member_error').text('Tolong masukkan nomor handphone indonesia yang benar.');
                }
                // lain jika member berhasil disimpan
                // lain jika tanggapan.status sama dengan 200
                else if (resp.status === 200) {
                    // // reset formulir
                    // panggil #form_tambah, index 0, lalu atur ulang
                    $("#form_tambah")[0].reset();
                    // nama member di focuskan
                    // panggil #nama_member lalu di fokuskan
                    $("#nama_member").focus();
                    // muat ulang table ajax
                    // panggil variable table, lalu ajaxnya dimuat ulang
                    table.ajax.reload();
                    // notifikasi menggunakan toastr
                    // toastr tipe sukses warna hijau dan tampilkan pesan
                    toastr.success(`${resp.pesan}.`);
                };
            });
        });

        // Edit
        // jika document di click, yang classnya adalah .tombol_edit maka jalankan fungsi berikut lalu ambil event atau acaranya
        $(document).on("click", ".tombol_edit", function(e) {
            // event cegah bawaan nya yaitu reload
            e.preventDefault();
            // berisi panggil .tombol_edit lalu ambil value attribute data-id, anggaplah berisi 1
            let member_id = $(this).attr('data-id');
            // lakukan ajax
            $.ajax({
                    // ke method show
                    // panggil url /member/ lalu kirimkan member_id lewat url
                    url: "/member/" + member_id,
                    // panggil route tipe dapatkan
                    type: "GET"
                })
                // jika selesai dan berhasil, maka jalankan fungsi berikut, dan ambil tanggapannya
                .done(function(resp) {
                    // tampilkan modal
                    $("#modal_edit").modal("show");
                    // isi value ke dalam input yang memiliki attribute name
                    // panggil #e_member_d lalu isi value dengan resp.member_id milik table member
                    $("#e_member_id").val(resp.member_id);
                    // panggil #e_nama_member lalu isi value nya dengan tanggapan.detail_member.nama_member milik table member
                    $("#e_nama_member").val(resp.detail_member.nama_member);
                    $("#e_telepon_member").val(resp.detail_member.telepon_member);
                    $("#e_alamat_member").val(resp.detail_member.alamat_member);
                });

        });

        // ketika modal edit ditutup maka reset formulir, dan hapus error validasi
        $(".e_tutup").on("click", function() {
            $("#form_edit")[0].reset();
            $(".e_input").removeClass("is-invalid");
            $(".e_pesan_error").text("");
        });

        // Update
        // jika #form_edit dikirim maka jalankan fungsi berikut dan ambil event nya
        $("#form_edit").on("submit", function(e) {
            // event cegah bawaannya yaitu reload
            e.preventDefault();

            // panggil #e_member_id lalu ambil valuenya anggaplah 1
            let member_id = $("#e_member_id").val();
            // lakukan ajax
            $.ajax({
                    // ke method update
                    url: `/member/${member_id}`,
                    // aku mengubah methodnya menjadi PUT di modal_edit.blade menggunakan #method('PUT')
                    type: "POST",
                    // kirimkan Form Data dari #form_edit, this adalah semua value dari input dari #form_data
                    data: new FormData(this),
                    // aku butuh 3 baris kode dibawah
                    processData: false,
                    contentType: false,
                    cache: false,
                    // sebelum kirim, hapus validasi errornya
                    // sebelum kirim, jalankan fungsi berikut
                    beforeSend: function() {
                        // panggil .e_input lalu hapus .is-invalid
                        $(".e_input").removeClass("is-invalid");
                        // panggil .e_pesan_error lalu kosongkan textnya
                        $(".e_pesan_error").text("");
                    }
                })
                // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapannya
                .done(function(resp) {
                    // jika validasi menemukan error
                    // jika tanggapan status nya sama dengan 0
                    if (resp.status === 0) {
                        // lakukan pengulangan terhadap semua value attribute name yang error dan semua pesan erronya
                        // key berisi semua value attribute name yang error
                        // value berisi semua pesan errornya
                        $.each(resp.errors, function(key, value) {
                            // contohnya, panggil .e_nama_member_input lalu tambah class .is-invalid
                            $(`.e_${key}_input`).addClass('is-invalid');
                            // contohnya, panggil .e_nama_member_error lalu isi textnya menggunakan pesan error
                            $(`.e_${key}_error`).text(value);
                        });
                    }
                    // lain jika user memasukkan nomor telepon indonesia yang salah
                    // lain jika resp.message sama dengan 'Tolong masukkan nomor handphone indonesia yang benar.'
                    else if (resp.message === 'Tolong masukkan nomor handphone indonesia yang benar.') {
                        // pangil #telepon_member lalu tambah class is-invalid
                        $("#e_telepon_member").addClass('is-invalid');
                        // panggil .telepon_member_error lalu text nya di isi pesan berikut
                        $('.e_telepon_member_error').text('Tolong masukkan nomor handphone indonesia yang benar.');
                    }
                    // jika validasi berhasil dan aku berhasil memperbarui member
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

        // pilih semua
        $("#select_all").on("click", function() {
            if ($("#select_all").prop("checked")) {
                $(".pilih").prop("checked", true);
            } else {
                $(".pilih").prop("checked", false);
            };
        });

        // Delete
        // jika #tombol_hapus di click maka jalankan fungsi berikut lalu ambil event
        $("#tombol_hapus").on("click", function(e) {
            // jika input.pilih yang di centang, panjangnya sama dengan 0
            if ($("input.pilih:checked").length === 0) {
                // tampilkan notifikasi
                Swal.fire('Anda belum memilih member');
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
                            // alasan menggunakan syntax ini adalah karena input name berisi member_ids[]
                            // .serialize akan mengirimkan semua data pada table karena table disimpan di dalam form
                            $.post('{{ route('member.destroy') }}', $('#form_member').serialize())
                                .done(function(resp) {
                                    // notifkasi
                                    Swal.fire(
                                        'Dihapus!',
                                        'Berhasil menghapus member yang dipilih.',
                                        'success'
                                    );
                                    // reload ajax table
                                    table.ajax.reload();
                                });
                        };
                    });
            }
        });

        // Cetak kartu member
        // jika #cetak_kartu_member di click maka jalankan fungsi berikut
        $("#cetak_kartu_member").on("click", function() {
            // jika input class pilih yang dicentang panjangnya lebih kecil atau sama dengan 0 maka
            if ($("input.pilih:checked").length <= 0) {
                // tampilkan notifkasi yang menyatakan "Silahkan pilih member"
                Swal.fire('Silahkan pilih member');
            }
            // lain jika input .pilih yang di centang, panjangnya lebih besar atau sama dengan 1
            else if ($("input.pilih:checked").length >= 1) {
                // panggil #form_member lalu buat attribute target berisi blank, blank akan melakukan new tab
                $("#form_member").attr("target", "_blank")
                    // buat attribute action dan method
                    .attr({
                        //  memanggil route berikut
                        "action": `{{ route('member.cetak_kartu') }}`,
                        // panggil route tipe kirim
                        "method": "POST",
                    })
                    // kirim formulir
                    .submit();
            };
        });
    </script>
@endpush
