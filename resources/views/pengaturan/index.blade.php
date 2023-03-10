{{-- memperluas parentnya --}}
@extends('layouts.app')

{{-- kirimkna valuenya ke @yield('title') --}}
@section('title', 'Pengaturan')

{{-- kirimkan valuenya ke @yield('konten') --}}
@section('konten')
    <div class="row">
        <div class="col-md-12">
            <div class="card-box">
                {{-- formulir edit data --}}
                {{-- termasuk pengaturan.form_edit --}}
                @include('pengaturan.form_edit')
            </div>
        </div>
        <!-- end col -->
    </div>
@endsection

@push('script')
<script>
// pratinjau gambar logo perusahaan
// jika #logo_perusahaan di ubah maka jalankan fungsi berikut
$("input#logo_perusahaan").on("change", function() {
    // jika ada gambar logo perusahaan baru yang dipilih
    // this berarti #logo_perusahaan
    if (this.files[0]) {
        // ambil gambar logo perusahaan yang baru
        let gambar_logo_perusahaan = this.files[0];
        // panggil pembaca file baru
        let file_pembaca = new FileReader();
        // file pembaca ketika dimuat maka jalankan fungsi berikut dan tangkap eventnya
        file_pembaca.onload = function(e) {
            // panggil #pratinjau_logo_perusahaan lalu attribute src nya diisi dengan e.target.result
            $("img#pratinjau_logo_perusahaan").attr("src", e.target.result);
        };
        // panggil file_pembaca lalu baca sebagai data url dari variable gambar_logo_perusahaan
        file_pembaca.readAsDataURL(gambar_logo_perusahaan);
    };
});

// pratinjau gambar kartu member
// jika input #kartu_member di ubah maka jalankan fungsi berikut
$("input#kartu_member").on("change", function() {
    // jika ada gambar kartu member baru yang dipilih
    // this berarti #kartu_member
    if (this.files[0]) {
        // ambil gambar kartu member yang baru lalu simpan ke dalam variable gambar_kartu_member
        let gambar_kartu_member = this.files[0];
        // panggil pembaca file baru
        let file_pembaca = new FileReader();
        // file pembaca ketika dimuat maka jalankan fungsi berikut dan tangkap eventnya
        file_pembaca.onload = function(e) {
            // panggil #pratinjau_kartu_member lalu attribute src nya diisi dengan e.target.result
            $("img#pratinjau_kartu_member").attr("src", e.target.result);
        };
        // panggil file_pembaca lalu baca sebagai data url dari variable gambar_kartu_member
        file_pembaca.readAsDataURL(gambar_kartu_member);
    };
});

// perbarui pengaturan perusahaan
// jika formnya dikirim maka jalankan fungsi berikut
$("form").on("submit", function(e) {
    // cegah defaultnya yaitu reload 
    e.preventDefault();
    // lakukan ajax
    $.ajax({
            // url ke route pengaturan.update
            url: `{{ route('pengaturan.update') }}`,
            // ke route type post
            type: "POST",
            // kirimkan form data dari form, this berarti form
            data: new FormData(this),
            // aku butuh ketiga baris kode dibawah ini
            processData: false,
            contentType: false,
            cache: false,
            // sebelum kirim hapus validasi error agar seperti ada efek refresh validasi error
            beforeSend: function() {
                // .input hapus class is-invalid
                $(".input").removeClass("is-invalid");
                // kosongkan text pada .error
                $(".error").text("");
            }
        })
        // jika selesai maka jalankan fungsi berikut dan tangkap tanggapanya
        .done(function(resp) {
            // jika resp.status sama dengan 0
            if (resp.status === 0) {
                // console.log(resp.errors);
                // lakukan pengulangan terhadap resp.errors dan ambil semua value dari attribute name yang error dan pesan errornya
                // key berisi semua value attribute name yg error, value berisi semua pesan error
                $.each(resp.errors, function(key, value) {
                    // contohnya panggil .input_nama_perusahaan lalu tambah .is-invalid
                    $(`.input_${key}`).addClass('is-invalid');
                    // contohnya panggil .error_nama_perusahaan lalu textnya diisi dengan pesan error
                    $(`.error_${key}`).text(value);
                });
            // jika aku berhasil memperbarui pengaturan
            // lain jika resp.status sama dengan 200 maka
            } else if (resp.status === 200) {
                // notifikasi menggunakan sweetalert 2
                Swal.fire(
                    'Berhasil',
                    'Pengaturan Berhasil Diperbarui',
                    'success'
                );
            };
        });
});
</script>
@endpush
