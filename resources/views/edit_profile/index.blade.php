{{-- memperluas parentnya --}}
@extends('layouts.app')

{{-- kirimkna valuenya ke @yield('title') --}}
@section('title', 'Edit Profile')

{{-- kirimkan valuenya ke @yield('konten') --}}
@section('konten')
    <div class="row">
        <div class="col-md-12">
            <div class="card-box">
                {{-- formulir edit data --}}
                @include('edit_profile.form_edit')
                {{-- modal edit password --}}
                @include('edit_profile.modal_edit_password')
            </div>
        </div>
        <!-- end col -->
    </div>
@endsection

@push('script')
<script>
    // tampilkan pratinjau gambar ketika user mengubah gambar
    // jika #pilih_gambar diubah maka jalankan fungsi berikut
    $("#pilih_gambar").on("change", function() {
        // ambil gambarnya, this berarti @pilih_gamabr
        let gambar = this.files[0];
        // jika ada gambar yang di pilih
        if (gambar) {
            // pnnggil baru FilePembaca
            let filePembaca = new FileReader();
            // file pembaca ketika dimuad maka jalankan fungsi berikut dan tangkap eventnya
            filePembaca.onload = function(e) {
                // panggil #pratinjau_gambar lalu pangil attribute src diisi dengan acara.target.hasil
                $("#pratinjau_gambar").attr("src", e.target.result);
            };
            // new FileReader() baca data sebagai url dari this.file[0]
            filePembaca.readAsDataURL(gambar);
        };
    });

    // jika #perbarui_profil dikirim maka jalankan fungsi berikut dan tangkap eventnya
    $("#perbarui_profile").on("submit", function(e) {
        // cegah bawaanya yaitu reload
        e.preventDefault();
        // lakukan ajax
        $.ajax({
                // ke route update_profile
                url: `{{ route('update_profile') }}`,
                // ke route type post
                type: "POST",
                // kirimkan data formulir dari #perbarui_profile
                data: new FormData(this),
                // aku butuh 3 baris kode dibawah kecuali jika aku mengirimkan data menggunakan object manual
                processData: false,
                contentType: false,
                cache: false,
                // menghapus validasi error sebelum form dikirim agar ada efek refresh validasi error
                // sebelum kirim, jalankan fungsi berikut
                beforeSend: function() {
                    // panggil .input lalu hapus .is-invalid
                    $(".input").removeClass("is-invalid");
                    // panggil .error lalu kosongkan textnya
                    $(".error").text("");
                }
            })
            // jika selesai maka jalankan fungsi berikut
            .done(function(resp) {
                // jika validasi menemukan error
                if (resp.status === 0) {
                    // console.log(resp.errors);
                    $.each(resp.errors, function(key, value) {
                        $(`.input_${key}`).addClass('is-invalid');
                        $(`.error_${key}`).text(value);
                    });
                    // jika berhasil memperbarui profll
                } else if (resp.status === 200) {
                    // perbarui nama user di layouts/top-navbar
                    $(".nama_user").text(resp.detail_user.name);
                    // perbarui foto profil di layouts/top-navbar
                    $(".foto_profil").attr("src", `/storage/foto_profil/${resp.detail_user.gambar}`);
                    // notifikasi menggunakan sweetalert 2
                    Swal.fire(
                        'Berhasil',
                        'Profile Berhasil Diperbarui',
                        'success'
                    );
                };
            });
    });

    // jika .lihat_password di click maka jalankan fungsi berikut
    $(".lihat_password").on("click", function() {
        // jika .lihat_passwored memiliki .fa-eye
        if ($(this).hasClass("fa-eye")) {
            // .ubah_type_password panggil attribute type lalu ubah menjadi type text
            $(".ubah_type_password").attr("type", "text");
            // .lihat_password, textnya menjadi sembunyikan password
            $(this).text('Sembunyikan Password');
            // .lihat_password, hapus class fa-eye
            $(this).removeClass("fa-eye");
            // .lihat_password tambah .fa-eye-slah
            $(this).addClass("fa-eye-slash");
        }
        // jika .lihat_passwored memiliki .fa-eye-slash
        else if ($(this).hasClass('fa-eye-slash')) {
            // .ubah_type_password panggil attribute type lalu ubah menjadi type text
            $(".ubah_type_password").attr("type", "password");
            // .lihat_password, ubah teks nya menjadi lihat_password
            $(this).text('Lihat Password');
            // .lihat_password hapus .fa-eye.slash
            $(this).removeClass("fa-eye-slash");
            // .lihat_password tambah class .fa-eye
            $(this).addClass("fa-eye");
            // logikanya sama saja, hanya saja terbalik secara kode 
        };
    });

    // Update password
    // jika #form_edit_password dikirim maka jalankan fungsi berikut
    $("#form_edit_password").on("submit", function(e) {
        // cegah bawaannya yaitu reload
        e.preventDefault();
        // lakukan ajax
        $.ajax({
                // ke route edit_profile.update_password
                url: `{{ route('edit_profile.update_password') }}`,
                // panggil route type post
                type: "POST",
                // kirimkan data formulir atau data input2x
                data: new FormData(this),
                // 3 baris kode dibawah ini wajib
                processData: false,
                contentType: false,
                cache: false,
                // sebelum kirim, hapus validasi error
                // sebelum kirim, jalankan fungsi berikut
                beforeSend: function() {
                    // panggil .e_input lalu hapus .is-invalid, defaultnya adalah tidak ada
                    $(".e_input").removeClass("is-invalid");
                    // panggil .e_pesan_error lalu kosongkan textnya
                    $(".e_pesan_error").text("");
                }
            })
            // jika selesai dan berhasil maka jalankan fungsi berikut sambil mengambil response
            .done(function(resp) {
                // jika validasi error
                if (resp.status === 0) {
                    // aku melakukan pengulangan untuk error
                    // key berisi setiap value attribute name dan value berisi pesan errornya
                    $.each(resp.errors, function(key, value) {
                        // contohnya panggil .e_password_lama_input lalu tambah .is-invalid
                        $(`.e_${key}_input`).addClass('is-invalid');
                        // value berisi pesan error
                        // contohnya panggil .e_password_lama_error lalu textnya diisi dengan pesan errorya
                        $(`.e_${key}_error`).text(value);
                    });
                // Jika user memasukkan password yang salah di input password lama
                // lain jika resp.pesan sama dengan "Password salah"
                } else if (resp.pesan === "Password lama salah") {
                    // panggil #e_password_lama lalu tambah .is-invalid
                    $(`#e_password_lama`).addClass('is-invalid');
                    // panggil .e_password_error_lama_error lalu kasi text "Password lama salah"
                    $(`.e_password_lama_error`).text('Password lama salah.');
                }
                // jika user memasukkan password lama di input password baru maka
                else if (resp.pesan === "Password baru tidak boleh sama dengan password lama") {
                    // panggil #e_password_baru lalu tambah .is-invalid
                    $(`#e_password_baru`).addClass('is-invalid');
                    // panggil .e_password_error_baru_error lalu kasi text "Password baru tidak boleh sama dengan password lama."
                    $(`.e_password_baru_error`).text(
                    'Password baru tidak boleh sama dengan password lama.');
                }
                // Password berhasil diperbarui
                else if (resp.status === 200) {
                    // kosongkan value #e_password_lama dan #e_password_baru
                    $("#e_password_lama").val("");
                    $("#e_password_baru").val("");
                    // tutup modal
                    $("#modal_edit_password").modal("hide");
                    // notifikasi menggunakan sweetalert 2
                    Swal.fire(
                        'Berhasil',
                        'Password Berhasil Diperbarui',
                        'success'
                    );
                };
            });
    });
</script>
@endpush
