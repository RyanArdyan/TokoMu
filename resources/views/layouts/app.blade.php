<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8" />
        <title>@yield('title')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        {{-- csrf token --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- App favicon -->
        {{-- logo web di sebelah title --}}
        {{-- asset berarti memanggil folder public --}}
        <link rel="shortcut icon" href="{{ asset('storage/logo_web/logo.jpg') }}">



        <!-- Bootstrap -->
        <link href="{{ asset('adminto') }}/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        {{-- icons --}}
        <link href="{{ asset('adminto') }}/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        {{-- app --}}
        <link href="{{ asset('adminto') }}/assets/css/app.min.css" rel="stylesheet" type="text/css" />
        {{-- datatables css --}}
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.13.2/datatables.min.css"/>

        {{-- toastr css --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

        {{-- berfungsi menangkap @push('css') --}}
        @stack('css')
        
        <style>
            /* template adminto itu tinggi halaman nya fixed makanya menulis ini agar tinggi halamanannya panjang */
            html{height:100% !important;width:100% !important; margin:0px; padding:0px;}

            body{height:500% !important;width:100% !important; margin:0px; padding:0px;}

        </style>
    </head>

    <body>
        <!-- Begin page -->
        <div id="wrapper">

            <!-- Topbar Start -->
            @include('layouts.top-navbar')
            <!-- end Topbar -->

            <!-- ========== Left Sidebar Start ========== -->
            @include('layouts.left_side')
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">
                        @yield('konten')
                    </div> <!-- container-fluid -->

                </div> <!-- content -->

                <!-- Footer Start -->

                <!-- end Footer -->

            </div>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->


        </div>
        <!-- END wrapper -->

        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        {{-- JQuery --}}
        {{-- asset akan memanggil folder public --}}
        <script src="{{ asset('js_saya/jquery-3.6.3.min.js') }}"></script>
        <!-- Vendor js -->
        <script src="{{ asset('adminto') }}/assets/js/vendor.min.js"></script>



        <!-- knob plugin -->
        <script src="{{ asset('adminto') }}/assets/libs/jquery-knob/jquery.knob.min.js"></script>





        <!-- App js -->
        <script src="{{ asset('adminto') }}/assets/js/app.min.js"></script>
        {{-- datatables js --}}
        <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.13.2/datatables.min.js"></script>
        {{-- sweetalert 2 --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        {{-- toastr js --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" ></script>
        {{-- input mask agar bisa mengubah 1000 menjadi Rp 1.000 --}}
        <script src="{{ asset('inputmask_5') }}/dist/jquery.inputmask.js"></script>
        <script src="{{ asset('inputmask_5') }}/dist/bindings/inputmask.binding.js"></script>
        {{-- Script child akan di push kesini(parent) menggunakna @push('script') --}}
        @stack('script')
        {{-- fitur tooltip atau misalnya aku hover tombol hapus maka muncul sebuah text box yang menyatakan hapus --}}
        <script>
            // jika document siap maka jalankan fungsi
            $(document).ready(function () {
                // panggil attribute data-toggle yang berisi keterangan_alat
                $('[data-toggle="keterangan_alat"]').tooltip()
            });
        </script>
    </body>
</html>