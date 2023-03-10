{{-- memperluas parentnya yaitu layouts.app --}}
@extends('layouts.app')

{{-- dorong css lalu tangkap menggunakan $stack('css') --}}
@push('css')
@endpush

{{-- kirim value section title ke @Yield('title') --}}
@section('title', 'Kategori')

{{-- kirim value section konten ke @yield('konten') --}}
@section('konten')
    <div class="row mb-2">
        <div class="col-sm-12 mt-2">
            <ol class="breadcrumb">
                {{-- termasuk ada jika modal dipanggil --}}
                {{-- panggil modal tambah --}}
                @includeIf('kategori.modal_create')
                {{-- panggil modal edit --}}
                @includeIf('kategori.modal_edit')


                <div class="table-responsive">
                    <div id="loading">Loading...</div>
                </div>


            </ol>

            {{-- jika aku click tombol Tambah Kategori maka panggil fungsi tampilkan_modal_tambah --}}
            <button onclick="tampilkan_modal_tambah()" class="btn btn-purple btn-sm">
                <i class="mdi mdi-plus"></i>
                Tambah kategori
            </button>

            {{-- Fitur hapus beberapa kategori berdasarkan kotak centang yang di checklist --}}
            <button id="tombol_hapus" type="button" class="btn btn-danger btn-flat btn-sm"><i class="fa fa-trash"></i>
                Hapus</button>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

{{-- dorong script dan tangkap kategori --}}
@push('script')
<script>
    // read data kategori
    // buat fungsi read
    function read() {
        // lakukan ajax
        $.ajax({
                // url ke route kategori.index
                url: "{{ route('kategori.index') }}",
                // panggil route tipe dapatkan
                type: "GET",

            })
            // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil response berupa element table html
            .done(function(resp) {
                // panggil #loading, lalu isi dengan table
                $("#loading").html(resp);
                // pangil table lalu gunakan datatables client side
                $("table").DataTable({
                    // gunakan bahasa indonesia di datatable
                    language: {
                        // panggil folder public
                        url: '{{ asset('terjemahan_datatable/indonesia.json') }}'
                    }
                });
            });
    };
    read();


    // tampilkan modal tambah
    function tampilkan_modal_tambah() {
        // tampilkan modal tambah
        // panggil #modal_tambah lalu modalnya di tampilkan
        $("#modal_tambah").modal("show");
    };

    // simpan kategori
    // #form_tambah dikirim, maka jalankan fungsi berikut dan ambil eventnya
    $("#form_tambah").on("submit", function(e) {
        // cegah bawaannya yaitu reload
        e.preventDefault();
        // lakukan ajax
        $.ajax({
                // url ke route kategori.store
                url: "{{ route('kategori.store') }}",
                // panggil route tipe post
                type: "POST",
                // kirimkan formulir data dari #form_tambah
                data: new FormData(this),
                // aku butuh 3 baris kode berikut karena aku menggunakan new FormData(), keculai aku emngirim object secara manual
                processData: false,
                contentType: false,
                cache: false,
                // sebelum kirim, hapus validasi error
                beforeSend: function() {
                    // panggil .input lalu hapus class is-invalid
                    $(".input").removeClass("is-invalid");
                    // pnaggil class pesan_error lalu kosongkan textnya
                    $(".pesan_error").text("");
                }
            })
            // jika selesai dan berhasil maka jalankan fungsi berikut sambil mengambil tanggapanya
            .done(function(resp) {
                // jika validasi menemukan error
                // jika tanggapan.status sama dengan 0
                if (resp.status === 0) {
                    // lakukan pengulangan
                    // key berisi semua nilai attribute name yang error.
                    // value berisi array yang menyimpan semua pesan error
                    $.each(resp.errors, function(key, value) {
                        // contohnya panggil .nama_kategori tambah .is-invalid
                        $(`.${key}_input`).addClass("is-invalid");
                        // contohnya panggil .nama_kategori_error kasi text yang berisi array value index 0
                        $(`.${key}_error`).text(value[0]);
                    });
                } else if (resp.status === 200) {
                    // reset formulir
                    // panggil #form_tambah, index ke 0, atur ulang
                    $("#form_tambah")[0].reset();
                    // notifikasi menggunakan toastr
                    toastr.success(`Kategori ${resp.nama_kategori} berhasil disimpan.`);
                    // fokuskan nama kategori
                    $("#nama_kategori").focus();
                    // reload data kategori
                    // panggil fungsi read
                    read();
                };
            });
    });

    // hapus validasi error ketika aku menutup modal tambah
    // jika .tutup di click maka jalankan fungsi berikut
    $(".tutup").on("click", function() {
        // reset formulir
        // id form_tambah, index ke 0, kita atur ulang
        $("form")[0].reset();
        // .input, hapus class is-invalid
        $(".input").removeClass("is-invalid");
        // panggil .pesan_error lalu kosongkan textnya
        $(".pesan_error").text("");
    });

    // Tampilkan modal edit
    // jika dokumen, yang class nya adalah .tombol_edit di click maka jalankan fungsi berikut
    $(document).on("click", ".tombol_edit", function() {
        // ambil nilai attribut data-id
        // .tombol_edit ambil value attribute data-id
        let kategori_id = $(this).attr("data-id");
        // lakukan ajax
        $.ajax({
                // url ke route kategori.show, lalu kirimkan kategori_id
                url: "/kategori/" + kategori_id,
                // panggil route type dapatkan
                type: "GET"
            })
            // jika 
            .done(function(resp) {
                // isi input
                // panggil id e_nama_kategori lalu diisi dengan tanggapan.nama_kategori
                $("#edit_kategori_id").val(resp.kategori_id);
                $("#edit_nama_kategori").val(resp.nama_kategori);
                $("#edit_deskripsi_kategori").val(resp.deskripsi_kategori);
                // tampilkan modal
                $("#modal_edit").modal("show");
            });
    });

    // Update kategori
    // jika #form_edit di kirim, jalankan fungsi berikut dan ambil tanggapannya
    $("#form_edit").on("submit", function(e) {
        // cegah bawaannya yaitu reload
        e.preventDefault();
        // panggil value dari #kategori_id
        let kategori_id = $("#edit_kategori_id").val();
        // lakukan ajax
        $.ajax({
                // ke method update
                // panggil url berikut lalu kirimkan kategori_id lewat url
                url: `/kategori/${kategori_id}`,
                // tipe routenya adalah PUT karena sudah aku ubah di formulir
                type: "POST",
                // kirimkan formulir data dari #form_edit
                data: new FormData(this),
                // aku butuh 3 baris kode dibawah ini
                processData: false,
                contentType: false,
                cache: false,
                // sebelum dikirim, hapus validasi error dulu
                beforeSend: function() {
                    // pang
                    $(".input").removeClass("is-invalid");
                    $(".pesan_error").text("");
                }
            })
            // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapannya
            .done(function(resp) {
                // jika validasi error
                // jika resp,status sama dengan 0
                if (resp.status === 0) {
                    // lakukan pengulangan
                    // key berisi semua nilai attribute name yang error.
                    // value berisi array yang menyimpan semua pesan error
                    $.each(resp.errors, function(key, value) {
                        // contohnya panggil #e_nama_kategori tambah .is-invalid
                        $(`.${key}_input`).addClass("is-invalid");
                        // contohnya panggil #e_nama_kategori_error kasi text yang berisi array value index 0
                        $(`.${key}_error`).text(value[0]);
                    });
                    // jika kategori berhasil diperbarui
                    // lain jika resp.status sama dengan 200
                } else if (resp.status === 200) {
                    // tutup modal
                    // panggil #modal_edit lalu tutup modal
                    $("#modal_edit").modal("hide");
                    // panggil fungsi read untuk reload data di table kategori
                    read();
                    // notifikasi menggunakan toastr AdminLTE
                    toastr.success(`Kategori ${resp.nama_kategori} berhasil diperbarui.`);
                };
            });
    });

    // pilih semua
    // jika document di click yang id nya adalah #pilih_semua maka jalankan fungsi berikut
    $(document).on("click", "#pilih_semua", function() {
        // jika #pilih_semua di centang maka
        if ($(this).prop("checked")) {
            // panggil .pilih lalu centang semua dengan cara centangnya true
            $(".pilih").prop("checked", true);
        }
        // lain jika #pilih_semua tidak dicentang
        else if (!$(this).prop("checked")) {
            // panggil .pilih lalu hapus semua centang dengan cara centangnya false
            $(".pilih").prop("checked", false);
        };
    });

    // Delete
    // jika #tombol_hapus di click maka jalankan fungsi berikut dan ambil acaranya
    $("#tombol_hapus").on("click", function(e) {
        // buat array uuntuk menyimpan semua kategori_id
        let semua_kategori_id = [];
        // lakukan pengulangan
        // ambil input nam="kategori_ids" yang dicentang
        $("input:checkbox[name=kategori_ids]:checked").each(function() {
            // dorong value input name="kateegori_ids" ke array semua_kategori_id
            semua_kategori_id.push($(this).val());
        });

        if (semua_kategori_id.length === 0) {
            // tampilkan notifikasi menggunakna sweetalert
            Swal.fire('Anda belum memilih kategori');
            // lain jika semu_kategori_id, panjangnya lebih besar atau sama dengan 1
        } else if (semua_kategori_id.length >= 1) {
            // konfirmasi sweetalert 2
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Anda akan menghapus kategori dan produk nya yang terkait!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                // jika hasilnya di konfirmasi maka
                if (result.isConfirmed) {
                    // lakukan ajax
                    $.ajax({
                            // url memanggil route kategori.destroy
                            url: "{{ route('kategori.destroy') }}",
                            // panggil route tipe 
                            type: 'DELETE',
                            // kirimkan data berupa object
                            data: {
                                // kirim 
                                semua_kategori_id: semua_kategori_id
                            },
                            // buat csrf token untuk keamanan
                            headers: {
                                // panggil value meta name="csrf-token" attribute conntent
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                    'content')
                            },
                            // success
                        })
                        .done(function(resp) {
                            if (resp.status === 200) {
                                // console.log(resp.semua_kategori);

                                // panggil fungsi read
                                read();
                                // notifikasi
                                Swal.fire(
                                    'Terhapus!',
                                    'Berhasil menghapus kategori yang dipilih.',
                                    'success'
                                );
                            };
                        });
                };
            });
        }
    });
</script>
@endpush
