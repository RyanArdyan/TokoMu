<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <title>Registrasi Form</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Halaman registrasi" name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    {{-- cetak csrf_token() milik laravel agar ajax jquery tipe kirim dan update nya berfungsi --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('storage/gambar_pengaturan/logo_perusahaan.png') }}">

    <!-- App css -->
    <!-- bootstrap.min.css -->
    <link href="{{ asset('adminto') }}/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- icons.min.css -->
    <link href="{{ asset('adminto') }}/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- app.min.css -->
    <link href="{{ asset('adminto') }}/assets/css/app.min.css" rel="stylesheet" type="text/css" />

    {{-- CSS Saya --}}
    <link rel="stylesheet" href="{{ asset('css_saya/style.css') }}">
</head>


<body class="authentication-bg">
    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="text-center">

                    </div>
                    <div class="card">

                        <div class="card-body p-4">

                            <div class="text-center mb-4">
                                <h4 class="text-uppercase mt-0">Formulir Registrasi</h4>
                            </div>

                            <form id="form_registrasi">
                                {{-- laravel mewajibkan keamanan dari serangan csrf --}}
                                @csrf
                                <div class="form-group mb-3">
                                    <label for="name">Nama Lengkap</label>
                                    {{-- untuk membuat efek error di input, aku butuh is-invalid --}}
                                    <input name="name" class="input name_input form-control" type="text" id="name"
                                        placeholder="Nama Lengkap" autocomplete="off">
                                    {{-- untuk menampilkan pesan error --}}
                                    <p class="name_error pesan_error text-danger"></p>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="email">Email</label>
                                    {{-- untuk membuat efek error di input, aku butuh is-invalid --}}
                                    <input name="email" class="input email_input form-control" type="text" id="email"
                                        placeholder="Email" autocomplete="off">
                                    {{-- untuk menampilkan pesan error --}}
                                    <p class="email_error pesan_error text-danger"></p>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="password">Password</label>
                                    {{-- Untuk membuat efek validasi error di input, aku butuh is-invalid --}}
                                    <input name="password" class="input lihat_dan_sembunyikan_password password_input form-control" type="password" id="password" placeholder="Password" autocomplete="off">
                                    {{-- untuk menampilkan pesan error --}}
                                    <p class="password_error pesan_error text-danger"></p>
                                </div>

                                <div class="form-group mb-2">
                                    <label for="password_confirmation">Konfirmasi Password</label>
                                    {{-- Untuk membuat efek validasi error di input, aku butuh is-invalid --}}
                                    <input name="password_confirmation" class="input lihat_dan_sembunyikan_password password_confirmation_input form-control" type="password" id="password_confirmation" placeholder="Konfirmasi Password" autocomplete="off">
                                    {{-- untuk menampilkan pesan error --}}
                                    <p class="password_confirmation_error pesan_error text-danger"></p>
                                </div>

                                <div id="lihat_password" class="mb-3 text-primary jadikan_pointer">Lihat password</div>

                                <div class="form-group mb-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkbox-signin" checked>
                                        <label class="custom-control-label" for="checkbox-signin">Ingat Saya</label>
                                    </div>
                                </div>

                                <div class="form-group text-center">
                                    <button class="btn btn-primary btn-block" type="submit">
                                        <i class="mdi mdi-registrasi"></i>
                                        Registrasi
                                    </button>
                                </div>

                                <p class="mt-3"><a href="pages-recoverpw.html" class="text-muted"><i
                                            class="fa fa-lock mr-1"></i>Anda lupa password?</a></p>
                                {{-- cetak rute login.index --}}
                                <p class="mt-2"><a href="{{ route('login.index') }}" class="text-muted"><i
                                                class="fa fa-registered mr-1"></i>Sudah registrasi? silahkan click</a></p>
                            </form>


                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <!-- Vendor js -->
    {{-- asset akan memanggil public/adminto --}}
    <script src="{{ asset('adminto') }}/assets/js/vendor.min.js"></script>
    <!-- App js -->
    <script src="{{ asset('adminto') }}/assets/js/app.min.js"></script>
    {{-- sweetalert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- jquery --}}
    <script src="{{ asset('js_saya/jquery-3.6.3.min.js') }}"></script>
    {{-- script saya --}}
    <script>
        // lihat password dan sembunyikan password
        $("#lihat_password").on("click", function() {
            // jika text pada #lihat_password sama dengan Lihat passsword maka
            if ($(this).text() === "Lihat password") {
                // #password, attribute type nya, ubah ke text
                $(".lihat_dan_sembunyikan_password").attr("type", "text");
                // #lihat_password, ubah textnya ke Sembunyikan password
                $(this).text("Sembunyikan password");
                // lain jika #lihat_password, textnya sama dengan sembunyikan password maka
            } else if ($(this).text() === "Sembunyikan password") {
                // #password, attribute type nya menjadi password
                $(".lihat_dan_sembunyikan_password").attr("type", "password");
                // #lihat_password, textnya menjadi lihat password
                $(this).text("Lihat password");
            };
        });

        // registrasi
        // jika #form_registrasi di kirim maka jalankan fungsi berikut lalu ambil event atau acaranya  nya
        $("#form_registrasi").on("submit", function(event) {
            // cegah bawaan yaitu reload
            // acara.cegahBawaan();
            event.preventDefault();
            //  lakukan ajax
            $.ajax({
                // panggil route registrasi.store
                url: `{{ route('registrasi.store') }}`,
                // panggil route type POST
                type: 'POST',
                // data harus mengirimkan object
                // new FormData(this) secara otomatis membuat object
                data: new FormData(this),
                // aku butuh 3 baris kode dibawah jika menggunakan new FormData
                processData: false,
                contentType: false,
                cache: false,
                // sebelum kirim hapus validasi error
                beforeSend: () => {
                    // panggil .input lalu hapus class is-invalid
                    $(".input").removeClass("is-invalid");
                    // panggil .pesan_error lalu kosongkan text nya
                    $(".pesan_error").text("");
                },
                // laravel butuh csrf
                headers: {
                    // panggil tag meta, name nya csrf-token, ambil value attribute content
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            // jika selesai dan berhasil maka jalankan fungsi berikut
            .done((response) => {
                // jika validasi biasa error
                if (response.message === 'Validasi Biasa Errors') {
                    // lakukan pengulangan
                    // key berisi semua value attribute name yang error
                    // value berisi pesan errornya
                    $.each(response.errors, function(key, value) {
                        // contoh nya panggil .email_input lalu tambahkan .is-invalid
                        $(`.${key}_input`).addClass("is-invalid");
                        // contohnya panggil .email_error lalu tambahkan pesan error nya
                        $(`.${key}_error`).text(value);
                    });
                }
                // lain jika berhasil registrasi
                else {
                    // tampilkan notifikasi menggunakan package sweetalert
                    Swal.fire({
                        icon: 'success',
                        title: 'Silahkan Login',
                        text: 'Silahkan login menggunakan email dan password barusan',
                    })
                    // kemudian hasilnya maka jalankan fungsi berikut dan ambil hasil nya
                    .then((result) => {
                        // jika aku click oke pada pop up sweetalert maka
                        // jika hasilnya dikonfirmasi maka
                        if (result.isConfirmed) {
                            // pindahkan ke route login.index
                            // jendela.lokasi.href
                            location.href = `{{ route('login.index') }}`;
                        };
                    });
                }
            });
        });
    </script>
</body>

</html>
