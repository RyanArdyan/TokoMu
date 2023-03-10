{{-- memperluas parentnya yaitu layouts.app --}}
@extends('layouts.app')

{{-- dorong css lalu tangkap menggunakan $stack('css') --}}
@push('css')
{{-- panggil public/css_saya/style.css --}}
<link rel="stylesheet" href="{{ asset('css_saya/style.css') }}">
@endpush

{{-- kirim value section title ke @Yield('title') --}}
@section('title', 'Manajemen Kasir')

{{-- kirim value section konten ke @yield('konten') --}}
@section('konten')
<div class="row mb-2">
    <div class="col-sm-12 mt-2">
        <ol class="breadcrumb">
            {{-- termasuk ada jika modal dipanggil --}}
            @includeIf('manajemen_kasir.modal_create')

            

            <div class="table-responsive">
                {{-- aku membungkus table menggunakan form agar aku bisa mengirim value table --}}
                <form>
                    {{-- untuk keamanan --}}
                    @csrf
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <!-- Pilih -->
                                <th scope="col" width="5%">
                                    <input type="checkbox" name="select_all" id="select_all">
                                </th>
                                <th scope="col" width="5%">No</th>
                                <th scope="col" width="22%">Name</th>
                                <th scope="col">Email</th>
                            </tr>
                        </thead>
                    </table>
                </form>
            </div>

            {{-- jika aku click id tombol tambah_tambah maka panggil modal --}}
            <button id="tombol_tambah" class="btn btn-purple btn-sm mt-3">
                <i class="mdi mdi-plus"></i>
                Tambah Kasir
            </button>

            <div class="form-check mt-3">
                {{-- Fitur hapus beberapa kasir berdasarkan kotak centang yang di checklist --}}
                <button id="tombol_hapus" type="button"
                    class="btn btn-danger btn-flat btn-sm">
                    <i class="fa fa-trash"></i>
                    Hapus
                </button>
            </div>
        </ol>
    </div><!-- /.col -->
</div><!-- /.row -->
@endsection

{{-- dorong script dan tangkap kasir --}}
@push('script')
    <script>
        // ketika document siap maka jalankan fungsi berikut untuk memanggil semua data kasir
        let table = $("table").DataTable({
            // ada efek processing sebelum data dimuat
            processing: true,
            // serverside agar ketika data sudah diatas 10.000, itu lancaar
            serverSide: true,
            // lakukan ajax, ke route kasir.index
            ajax: "{{ route('manajemen_kasir.index') }}",
            // jika kemudian berhasil maka buat tbody dan td dan berisi data
            columns: [{
                    // fitur centang agar bisa menghapus banyak data berdasarkan kotak centang
                    data: "select_all",
                    // sortable untuk menghiangkan icon panah
                    sortable: false
                },
                // DT_RowIndex adalah kode dari laravl datatables untuk pengeluangan nomor
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    sortable: false
                },
                {
                    // column name dari table kasirs
                    data: 'name',
                    // berikan key name berisi key name agar aku bisa mengirim memasukkan table ke dalam form lalu mengirimnya
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
            ],
            // ubah bahasa yang digunakan datatables
            language: {
                // panggil url berikut
                url: "{{ asset('terjemahan_datatable/indonesia.json') }}"
            }
        });

        // tampilkan modal tambah
        // #tombol_tambah di click, jalankan fungsi berikut
        $("#tombol_tambah").on("click", function() {
            // show modal tambah
            $("#modal_tambah").modal("show");
        });

        // simpan kasir baru
        // jika #form_tambah dikirim maka jalankan fungsi berikut dan ambil tanggapannya
        $("#form_tambah").on("submit", function(e) {
            // cegah bawaannya yaitu reload 
            e.preventDefault();
            // lakukan ajax
            $.ajax({
                    // panggil url milik route manajemen_kasr.store
                    url: "{{ route('manajemen_kasir.store') }}",
                    // panggil route type post
                    type: "POST",
                    // kirimkan data formulir atau input dari #form_tambah
                    data: new FormData(this),
                    // aku butuh 2 baris kode dibawah
                    processData: false,
                    contentType: false,
                    // sebelum kirim, hapus validasi error atau pesan error pada input
                    // sebelum kirim, jalankan fungsi berikut
                    beforeSend: function() {
                        // jadi secara default input tidak memiliki .is-invalid tapi ketika error maka .is-invalid akan dibuat
                        $(".input").removeClass("is-invalid");
                        // panggil .pesan_error lalu kosognkan textnya
                        $(".pesan_error").text("");
                    }
                })
                // jika selesai maka jalankan fungsi berikut dan tangkap responsenya
                .done(function(resp) {
                    // jika validasi menemukan error atau jika kasir tidak mengisi formulir atau kasir salah memasukkan data
                    if (resp.status === 0) {
                        // lakukan pengulangan
                        // key berisi semua nilai attribute name yang error.
                        // value berisi array yang menyimpan semua pesan error
                        $.each(resp.errors, function(key, value) {
                            // contohnya panggil .name_error tambah .is-invalid
                            $(`.${key}_input`).addClass("is-invalid");
                            // contohnya panggil .name_error kasi text yang berisi array value index 0
                            $(`.${key}_error`).text(value[0]);
                        });
                    // jika lolos dari validasi dan aku berhasil menyimpan kasir di table
                    // lain jika tanggapan.status sama dengan 200
                    } else if (resp.status === 200) {
                        // // reset formulir
                        // #form_tambah, index 0, atur ulang
                        $("#form_tambah")[0].reset();
                        // name di focuskan
                        $("#name").focus();
                        // notifikasi misalnya kasir budi berhasil disimpan
                        // notifikasi toastr
                        toastr.success(`Berhasil menyimpan kasir.`);
                        // muat ulang table ajax
                        // variabel table, muat ulang ajaxnya
                        table.ajax.reload();
                    };
                });
        });

        
        // ketika modal tambah ditutup maka reset formulir, dan hapus error validasi
        $(".tutup").on("click", function() {
            // reset semua value pada input formulir
            $("form")[0].reset();
            // hapus error validasi pada input
            $(".input").removeClass("is-invalid");
            $(".pesan_error").text("");
        });

        // pilih semua
        // Jika #select_all di click maka jalankan fungsi berikut
        $("#select_all").on("click", function() {
            // jika #pilih_semua di centang
            if ($("#select_all").prop("checked")) {
                // panggil .pilih lalu centangnya adalah true
                $(".pilih").prop("checked", true);
            } 
            // lain jika #pilih_all tidak dicentang
            else {
                // panggil .pilih lalu centangnya adalah false
                $(".pilih").prop("checked", false);
            };
        });

        // Fitur hapus beberapa kasir berdasarkan kotak yang dicentang
        // jika #tombol_hapus di click maka jalankan fungsi berikut
        $("#tombol_hapus").on("click", function() {
            // jika input yang dicentang lebih besar atau sama dengan satu, atau jika aku centang pilih semua maka ada banyak kotak centann
            if ($("input:checked").length >= 1) {
                // konfirmasi penghapusan menggunakan sweetalert
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: `Anda akan menghapus ${$("input:checked").length} kasir yang dipilih!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                })
                // jika kemudian hasilnya adalah aku pilih ya, hapus maka
                .then((result) => {
                    // jika hasil di konfimasi atau aku pilih ya
                    if (result.isConfirmed) {
                        // alasan menggunakan syntax ini adalah karena input name berisi user_id[]
                        // .serialize akan mengirimkan semua data pada table karena table disimpan di dalam form
                        // anggaplah aku mengirim formulir
                        $.post(`{{ route('manajemen_kasir.destroy') }}`, $('form').serialize())
                            // jika kemudian selesai dan berhasil maka jalankan fungsi beikut sambil mengambil response
                            .done(function(resp) {
                                // jika resp.status sama dengan 200 maka
                                if (resp.status === 200) {
                                    // notifkasi
                                    Swal.fire(
                                        'Dihapus!',
                                        'Berhasil menghapus kasir yang dipilih.',
                                        'success'
                                    );
                                    // reload ajax table
                                    table.ajax.reload();
                                }; 
                            });
                    };
                });
            // jika input yang dicentang adalah 0 atau tidak ada yg dicentang
            } else if ($(`input:checked`).length === 0) {
                // Tampilkan notifikasi
                Swal.fire('Anda belum memilih kasir untuk dihapus.');
            };
        });

        // jika #lihat_password di click maka jalankan fungsi berikut
        $(".lihat_password").on("click", function() {
            // jika #lihat_passwored memiliki .fa-eye
            if ($(this).hasClass("fa-eye")) {
                // .ubah_type_password panggil attribute type lalu ubah menjadi type text
                $(".ubah_type_password").attr("type", "text");
                $(this).text('Sembunyikan Password');
                $(this).removeClass("fa-eye");
                $(this).addClass("fa-eye-slash");
            }
            // jika #lihatPasswored memiliki .fa-eye-slash
            else if ($(this).hasClass('fa-eye-slash')) {
                // .ubah_type_password panggil attribute type lalu ubah menjadi type text
                $(".ubah_type_password").attr("type", "password");
                $(this).text('Lihat Password');
                // #lihatPassword hapus .fa-eye.slash
                $(this).removeClass("fa-eye-slash");
                // #lihatPassword tambah class .fa-eye
                $(this).addClass("fa-eye");
                // logikanya sama saja, hanya saja terbalik secara kode 
            };
        });
    </script>
@endpush

