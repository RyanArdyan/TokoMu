{{-- memperluas parentnya yaitu layouts.app --}}
@extends('layouts.app')

{{-- dorong css lalu tangkap mengguakan $stack('css') --}}
@push('css')
    {{-- buat element style --}}
    <style>

    </style>
@endpush

{{-- kirim value section title ke @Yield('title') --}}
@section('title', 'Penjualan Detail')

{{-- kirim value section konten ke @yield('konten') --}}
@section('konten')
    <!-- Content Wrapper. Contains page content -->
    <div class="row mb-2">
        <div class="col-md-12 mt-2">
            <div class="card">
                <div class="card-body">

                    {{-- termasuk ada jika modal penjualan_detail.modal_produk di panggil --}}
                    @includeIf('penjualan_detail.modal_produk')
                    {{-- termasuk ada jika modal penjualan_detail.modal_member di panggil --}}
                    @includeIf('penjualan_detail.modal_member')

                    <div class="row mb-2">
                        <div class="col-sm-6">

                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <button id="tampilkan_produk" class="btn btn-success btn-sm">
                                    <i class="fa fa-shopping-bag"></i>
                                    Pilih Produk
                                </button>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->

                    {{-- termasuk tampilan penjualan_detail.table_penjualan_detail --}}
                    @include('penjualan_detail.table_penjualan_detail')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-default">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-bullhorn"></i>
                                        Total Pembayaran
                                    </h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="callout callout-danger">
                                        <h1 id="total_pembayaran"></h1>

                                        <p id="total_pembayaran_format_terbilang">
                                        </p>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>

                        {{-- col-md-6 akan membuat 2 column --}}
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <!-- /.card-header -->
                                {{-- termasuk penjualan_detail.form_penjualan --}}
                                @include('penjualan_detail.form_penjualan')
                            </div>
                        </div>


                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection


{{-- dorong script berikut ke dalam @stack('script') --}}
@push('script')
<script>
    // jadi nanti value nya akan di tambah jika aku menambah baris baru penjualan detail
    // berisi angka 0
    let baris = 0;
    // untuk menyimpan total barang, jadi pada awalnya 0 lalu aku click tombol pilih di modal pilih produk maka variable total_barang jadi 1, aku click lagi jadi 2
    let total_barang = 0;
    // untuk menyimpan total harga, jadi pada awalnya 0 lalu aku click tombol pilih di modal pilih produk maka variable total_harga ditambah value detail_produk, column harga_jual, aku click lagi jadi 2
    let total_harga = 0;

    // fitur mengisi input tanggal & waktu secara otomatis berdasarkan waktu saat ini
    // jika document siap maka jalankan fungsi berikut
    $(document).ready(function() {
        // Dapatkan waktu sekarang dalam format yang sesuai dengan datetime-local (YYYY-MM-DDTHH:mm)
        var now = new Date();
        var year = now.getFullYear();
        var month = ('0' + (now.getMonth() + 1)).slice(-2);
        var day = ('0' + now.getDate()).slice(-2);
        var hours = ('0' + now.getHours()).slice(-2);
        var minutes = ('0' + now.getMinutes()).slice(-2);

        var waktuSekarang = year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;

        // Set nilai input
        // panggil #tanggal_dan_waktu lalu value nya diisi value variable waktuSekarang
        $("#tanggal_dan_waktu").val(waktuSekarang);
    });

    // fungsi untuk memperbarui input total_harga versi rupiah dan input harus_bayar versi rupiah
    // parameter total_harga berisi value argument total_harga yang aku kirim
    function update_total_harga_versi_rupiah(total_harga) {
        // berisi mengubah 1000000 menjadi Rp 1.000.000
        // value parameter total_harga diubah menjadi bentuk rupiah
        let total_harga_versi_rupiah = total_harga.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });
        // panggil #total_rp lalu value atau nilai nya diisi dengan nilai variable total_harga_versi_rupiah
        $("#total_rp").val(total_harga_versi_rupiah);
        // panggil #bayar_rp lalu value atau nilai nya diisi dengan value variable total_harga_versi_rupiah
        $("#bayar_rp").val(total_harga_versi_rupiah);
        // panggil #uang_diterima lalu value atau nilai nya diisi dengan value variable total_harga_versi_rupiah
        $("#uang_diterima").val(total_harga_versi_rupiah);
    };

    // jika .tombol_piih_produk di click maka jalankan fungsi berikut
    $(".tombol_pilih_produk").on("click", function() {
        // berisi panggil .tombol_pilih_produk lalu ambil value attribute data-produk-id, anggaplah 1
        let produk_id = $(this).data('produk-id');

        // ambil text dari misalnya #stok_1
        // anggaplah berisi panggil #stok_1 lalu ambil textnya lalu anggaplah hasilnya 3.000
        let stok_versi_string = $(`#stok_${produk_id}`).text();
        // misalnya ubah 3.000 menjadi 3000 lalu dikurangi 1 maka akan menjadi 2999
        // berisi menguraikan_integer(stok.ganti(".", ""), 10)
        let stok = parseInt(stok_versi_string.replace(".", ""), 10) - 1;

        // jika value stok sama dengan 0 maka
        if (stok === 0) {
            //  hapus tombol pilih
            // anggapalh panggil #pilih_1 lalu hapus tombol pilih nya karena stok nya 0
            $(`#pilih_${produk_id}`).remove();
            // buat tombol habis
        };


        // panggil misalnya #stok_1 lalu text nya diisi value variable stok yang sudah dikurangi 1, misalnya nilai nya 3000 maka akan menjadi 2999, ubah dulu 2999 menjadi 2.999
        $(`#stok_${produk_id}`).text(stok.toLocaleString());

        // jquery lakukan ajax
        $.ajax({
            // url memanggil route penjualan_detail.ambil_detail_produk
            url: "{{ route('penjualan_detail.ambil_detail_produk') }}",
            // panggil route tipe kirim
            type: 'POST',
            // untuk keamanan dari serangan csrf
            // tajuk-tajuk berisi object
            headers: {
                // berisi panggil meta[name="csrf-token"], ambil value attribute content
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            // kirim data berupa object
            data: {
                // key produk_id berisi value dari variable produk_id
                produk_id: produk_id
            }
        })
        // jika selesai dan berhasil maka jalankan fungsi berikut
        // parameter response akan menangkap value yang dikirim PenjualanDetailController, method ambil_detail_produk()
        .done(function(response) {
            // panggil variable total_barang lalu value nya ditambah 1, lalu nanti di click lagi maka value nya jadi 2
            total_barang += 1;
            // panggil variable total_harga lalu value nya di tambah dengan value response.harga_jual
            total_harga += response.harga_jual;

            // panggil #total_barang lalu value atau nilai nya diisi dengan value variabel total_barang
            $("#total_barang").val(total_barang);
            // panggil #total_harga lalu value atau nilai nya diisi dengan value variabel total_harga
            $("#total_harga").val(total_harga);
            // panggil #total_bayar lalu value atau nilai nya diisi dengan value variabel total_harga
            $("#harus_bayar").val(total_harga);

            // panggil fungsi update_total_harga_versi_rupiah lalu kirimkan value variable total_harga
            update_total_harga_versi_rupiah(total_harga);

            // jika value #diskon tidak sama dengan 0 berarti aku sudah click tombol pilih di modal pilih member agar mendapat diskon maka value input harus_bayar akan berubah value nya karena sudah dikurangi diskon
            if ($("#diskon").val() != 0) {
                // panggil fungsi pilih_member agar mendapat diskon atau value input harus_bayar nya akan terpengaruh
                pilih_member();
            };


            // berisi mengubah 1000 menjadi Rp 1.000
            let harga_jual = response.harga_jual.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });

            // jadi nanti value nya akan di tambah jika aku click tombol pilih produk di modal pilih produk
            // panggil variable baris lalu value nya di tambah 1
            baris += 1;
            // jadi nanti akan ada class="baris1", baris2 dan seterusnya untuk fitur menambah baris
            // .baris_baru aku gunakan agar setelah aku click tombol simpan maka semua data disimpan lalu aku hapus .baris_baru dan turunannya
            var html = `<tr class="baris${baris} baris_baru">`;
                // berisi panggil value variable html lalu isinya ditambah
                // aku tidak perlu attribute name jadi aku akan mengambil value-value lewat class
                html += `<th>${baris}</th>`;
                // data-produk-id yang menyimpan value detail_produk, column produk_id, aku gunakan di fitur simpan penjualan detail setelah aku click tombol Simpan Penjualan
                // cetak value detail_produk, column kode_produk
                html += `<td class="kode_produk" data-produk-id="${response.produk_id}">${response.kode_produk}</td>`;
                // cetak value detail_produk, column nama_produk
                html += `<td class="nama_produk">${response.nama_produk}</td>`;
                // cetak value variable harga_jual
                html += `<td class="harga_jual">${harga_jual}</td>`;
                // attribtue data-baris aku gunakan untuk fitur perubahan subtotal setelah aku mengubah input jumlah, misalnya harga_jualnya 30.000 lalu jumlah nya jadi 2 maka subtotal nya jadi 60.000, anggaplah berisi 1, 2 dan seterusnya
                // buat attribute data-harga-jual agar bisa menyimpan value detail_produk, column harga_jual
                // value=1 artinya bawaan value nya adalah 1
                html += `<td><input type="number" class="jumlah" data-baris=${baris} data-harga-jual=${response.harga_jual} data-produk-id="${response.produk_id}" value=1></td>`;
                // .subtotal aku gunakan di fitur total harga dan menyimpan penjualan_detail
                // .subtotal_${baris} aku gunakan untuk fitur perubahan subtotal ketika jumlah barang nya di ubah, anggaplah berisi subtotal_1, subtotal_2, dan seterusnya
                html += `<td class="subtotal subtotal_${baris}">${harga_jual}</td>`;
                // .hapus aku gunakan untuk fitur hapus penjualan detail
                // attribute data-rows anggaplah berisi baris1, baris2, dst.
                html += "<td><button class='hapus btn btn-danger' data-row='baris"+ baris +"'>-</button></td>";
                html += "</tr>";

            // panggil #tbody_penjualan_detail lalu tambahkan value variable html sebagai anak terakhir
            $("#tbody_penjualan_detail").append(html);

            // tampilkan notifikasi menggunakan package toastr
            toastr.success('Berhasil memilih produk.');
        });
    });

    // ini untuk mendapatkan total barang dan total harga dengan cara looping semua value dari input jumlah dan td subtotal
    function update_total_barang_dan_harga() {
        // atur total_barang kembali ke 0 agar program nya benar
        total_barang = 0;
        // atur total_harga kembali ke 0 agar program nya benar
        total_harga = 0;

        // untuk fitur menghitung total_barang
        // lakukan pengulangan terhadap semua .jumlah
        // setiap .jumlah, jalankan fungsi berikut, aku melakukan pengulangan karena pada baris 1 dan seterusnya aku buat di script makanya pakai each
        $(".jumlah").each(function() {
            // mulai pengulangan
            // value dari .jumlah akan berubah-ubah karena dia input
            // berisi panggil value dari semua .jumlah lalu ubah string menjadi menjadi angka menggunakan parseInt, anggaplah baris 1 ada 2 jumlah, baris 2 ada 3 jumlah
            let value_dari_class_jumlah = parseInt($(this).val());

            // panggil variable total_barang lalu value nya di tambah sama dengan value variable value_dari_class_jumlah, anggaplah pada awal nya value total_barang berisi 0 lalu pada baris 1, jumlah nya 2 lalu pada baris 2 jumlah nya 3 maka total_barang berisi 5
            total_barang += value_dari_class_jumlah;
        });

        // lakukan pengulangan terhadap semua .subtotal
        // setiap .subtotal, jalankan fungsi berikut, aku melakukan pengulangan karena pada baris 1 dan seterusnya aku buat di script makanya pakai each
        $(".subtotal").each(function() {
            // mulai pengulangan
            // panggil .subtotal lalu ambil textnya
            let subtotal_versi_rupiah = $(this).text();
            // mengubah misalnya Rp 60.000 menjadi 60000
            let subtotal_versi_angka = parseInt($(this).text().replace(/[^0-9]/g, ''));

            // panggil total_harga lalu value nya di tambah sama dengan value variable subtotal_versi_angka, anggaplah pada awal nya value total_harga berisi 0 lalu pada baris 1, subtotal nya 30000 lalu pada baris 2 subtotal nya 80000 maka total_harga berisi 110000
            total_harga += subtotal_versi_angka;
        });

        // panggil #total_barang lalu value atau nilai nya diisi dengan value variabel total_barang
        $("#total_barang").val(total_barang);

        // panggil #total_harga lalu value atau nilai nya diisi dengan value variabel total_harga
        $("#total_harga").val(total_harga);
        // panggil #harus_bayar lalu value nya diisi dengan value dari $total_harga
        $("#harus_bayar").val(total_harga);

        // panggil fungsi update_total_harga_versi_rupiah lalu kirimkan value atau nilai variable total_harga agar input total_harga dan harus_bayar versi rupiah terisi
        update_total_harga_versi_rupiah(total_harga);


        // jika value #diskon tidak sama dengan 0 berarti aku sudah click tombol pilih di modal pilih member agar mendapat diskon maka value input harus_bayar akan berubah value nya karena sudah dikurangi diskon
        if ($("#diskon").val() != 0) {
            // panggil fungsi pilih_member agar mendapat diskon atau value input harus_bayar nya akan terpengaruh
            pilih_member();
        };
    };

    // jika document di masukkan angka atau di ubah yang class nya adalah .jumlah maka jalankan fungsi berikut
    $(document).on('input', '.jumlah', function() {
        // console.log($(this).val());

        // // panggil .jumlah, value dari attribute data-id
        // let penjualan_detail_id = $(this).data('id');

        // berisi panggil input jumlah
        let input_jumlah = $(this);

        // ambil value dari input jumlah, ubah string menjadi angka menggunakan parseInt
        // panggil .jumlah lalu ambll value nya
        let jumlah = parseInt($(this).val());
        // panggil .jumlah, lalu ambil value dari attribute data-harga-jual yaitu berisi value detail_produk, column harga_jual
        let harga_jual = $(this).data('harga-jual');
        // panggil .jumlah lalu ambil value attribute data-baris, baris 1 akan menghasilkan angka 1, baris 2 menghasilkan angka 2, dan seterusnya
        let baris = $(this).data('baris');
        // berisi panggil .jumlah lalu ambil value dari attribute data-produk-id
        let produk_id = $(this).data("produk-id");

        // jika value jumlah lebih kecil atau sama dengan 0
        if (jumlah <= 0) {
            // panggil .jumlah lalu value atau nilai nya di set atau di tetapkan ke 1
            $(this).val(1);
            // tampilkan peringatan berikut
            Swal.fire('Jumlah tidak boleh kurang dari 1');
            // kode selesai dan berhenti
            return;
        }
        // lain jika tidak ada value dari .jumlah maksudnya ada angka 1 di input jumlah lalu aku seleksi angka nya lalu aku hapus
        else if (!jumlah) {
            // panggil .jumlah, lalu value atau nilai nya di set atau di tetapkan ke 1
            $(this).val(1);
            // kode selesai dan berhenti
            return;
        };

        // lakukan ajax untuk mengecek jumlah detail_produk, column stok jadi misalnya stok nya 80, lalu value input jumlah aku atur ke 1000 maka dia akan kembali menjadi 80
        $.ajax({
            // panggil url: route penjualan_detail.cek_stok_produk
            url: "{{ route('penjualan_detail.cek_stok_produk') }}",
            // panggil route tipe POST
            type: "POST",
            // kirimkan data berupa object
            data: {
                // key jumlah berisi value variable jumlah
                jumlah: jumlah,
                // kirimkan value detail_penjuala_detail, column produk_id
                // key produk_id berisi value variable produk_id
                produk_id: produk_id
            },
            // laravel mewajibkan keamanan dari serangan csrf
            // tajuk-tajuk berisi objeck
            headers: {
                // key X-CSRF-TOKEN berisi panggil meta name csrf-token, value attribute content
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapan nya
        .done(function(response) {
            // jika value input jumlah lebih besar dari value detail_produk, column stok_produk atau value tanggapan.stok_produk, anggaplah value input jumlah nya adalah 1000 lalu value detail_produk column stok nya 80 maka
            if (jumlah > response.stok_produk) {
                // tampilkan notifikasi pakai sweetalert
                // Swal.api() panggil value tanggapan.pesan, tanda backtick bisa mencetak value variable di dalam string
                Swal.fire(`${response.message}`);
                // kembalikkan value nya ke jumlah maksimal detail_produk, column stok_produk
                // panggil .jumlah lalu value nya diisi dengan value tanggapan.stok_produk
                input_jumlah.val(response.stok_produk);
                // cetak value dari input_jumlah
                let stok_produk = input_jumlah.val();


                // berisi value variable stok_produk anggaplah 2 dikali value harga_jual anggaplah 30.000 berarti 60.000
                let subtotal = stok_produk * harga_jual;
                // anggaplah panggil .subtotal_1 lalu ubah text nya mengikuti value variable subtotal lalu ubah anggaplah 60000 akan menjadi Rp 60.000
                $(`.subtotal_${baris}`).text(subtotal.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }));


                // panggil fungsi update_totaL_barang_dan_harga();
                update_total_barang_dan_harga();
            };
        });



        // berisi value variable jumlah anggaplah 2 dikali value harga_jual anggaplah 30.000 berarti 60.000
        let subtotal = jumlah * harga_jual;
        // anggaplah panggil .subtotal_1 lalu ubah text nya mengikuti value variable subtotal lalu ubah anggaplah 60000 akan menjadi Rp 60.000
        $(`.subtotal_${baris}`).text(subtotal.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }));


        // panggil fungsi update_totaL_barang_dan_harga();
        update_total_barang_dan_harga();
    });


    // aku perlu $(document) karena tombol hapus nya ditambah setelah aku click tombol pilih di modal pilih produk atau tombol hapus nya di buat di script
    // jika document di click, yang id nya adalah #hapus maka jalankan fungsi berikut
    $(document).on('click', '.hapus', function() {
        // berisi panggil .hapus lalu panggil value dari attribute data-row nya, anggaplah berisi baris1, baris2, dan seterusnya
        let baris = $(this).data('row');
        // anggaplah ada 3 baris penjualan_detal, baris 1, jumlah produk nya 4, baris 2, jumlah produk nya 3, baris 3 jumlah produk nya 2, aku menghapus baris 1, yang jumlah produk nya 4
        // anggaplah panggil .baris1 lalu hapus
        $('.' + baris).remove();

        // panggil fungsi total_barang_dan_harga();
        update_total_barang_dan_harga();
    });

    // table produk gunakan datatable
    $('#table_produk').DataTable();

    // table member gunakan datatable
    $(`#table_member`).DataTable();

    // hanya izinkan user memasukkan angka di input yang telah di tentukan
    function number(event) {
        let charCode = (event.which) ? event.which : event.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        } else {
            return true;
        };
    };

    // package Input Mask - Robin Herbots
    // aku perlu ini agar Rp 1.000 akan menjadi 1000 ketika sudah di controller
    // 1000 akan menjadi 1.000
    $(".input_angka").inputmask();

    // tampilkan modal pilih produk
    // jika #tampilkan_produk di click maka jalankan fungsi berikut
    $("#tampilkan_produk").on("click", function() {
        // panggil #modal_produk lalu modal nya di tampilkan
        $("#modal_produk").modal("show");
    });

    // jika #button_tampilkan_member di click maka jalankan fungsi berikut
    $("#button_tampilkan_member").on("click", function() {
        // panggil #modal_member lalu modal nya di tampilkan
        $(`#modal_member`).modal(`show`);
    });


    // Fitur pilih member agar total_harga dikurangi diskon
    function pilih_member(member_id, kode_member) {
        // berisi pangil #total_harga lalu ambil value atau nilai nya, anggaplah berisi 100.000 versi integer
        let total_harga = $("#total_harga").val();
        // diskon didapatkan dari PenjualanDetailLController, method index
        let diskon = "{{ $diskon }}";
        // KUKABATAKU(kurung, kali, bagi, tambah, kurang)
        // anggaplah berisi (5 / 100) * 100.000 = 5.000
        let nilai_diskon = (diskon / 100) * total_harga;
        // anggaplah berisi 100.000 - 5.000 = 95.000
        let total_setelah_diskon = total_harga - nilai_diskon;

        $("#member_id").val(member_id);

        // panggil #diskon lalu value nya diisi dengan variable diskon
        $('#diskon').val(diskon);

        // panggil #harus_bayar lalu value atau nilai nya diisi value variable total_setelah_diskon
        $("#harus_bayar").val(total_setelah_diskon);
        // panggil #bayar_rp lalu value nya diisi dengan value total_setelah diskon versi rupiah
        // kode berikut mengubah 1000000 menjadi Rp 1.000.000
        $("#bayar_rp").val(total_setelah_diskon.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }));

        // panggil #uang_diterima lalu value atau nilai nya diisi 0, lalu focuskan
        $('#uang_diterima').val(0);
        // sembunyikan modal member
        $('#modal_member').modal('hide');
    };

    // ada nilai bawaan, jadi jika aku tidak mengirim argumen maka tidak akan error
    function muat_ulang_form(diskon = 0, uang_diterima = 0) {
        // panggil text dari .total_harga dari PenjualanDetailController, method data
        let total_harga = $(".total_harga").text();
        let total_barang = $(".total_barang").text();

        // panggil #total_harga lalu value nya di ambil dari value variable total_harga
        $('#total_harga').val(total_harga);
        // panggil #total_barang lalu value nya di ambil dari text .total_barang
        $('#total_barang').val(total_barang);

        // kirim data lewat url
        // lakukan ajax tipe dapatkan, panggil url berikut lalu kirimkan 3 argument
        $.get(`/penjualan-detail/muat-ulang-form/${diskon}/${total_harga}/${uang_diterima}`)
            // jika selesai dan berhasil maka jalankan arrow function dambil tangapan nya
            .done(response => {
                // panggil #total_rp lalu value nya diisi value response.data.total_rp
                $('#total_rp').val(response.data.total_rp);
                $('#bayar_rp').val(response.data.bayar_rp);
                $('#harus_bayar').val(response.data.harus_bayar);
                $('#total_pembayaran').text('Bayar: ' + response.data.bayar_rp);
                $('#total_pembayaran_format_terbilang').text(response.data.terbilang);
                $('#uang_kembalian_pelanggan').val(response.data.kembali_rp);

                // jika value dari #uang_diterima tidak sama dengan 0 maka
                if ($('#uang_diterima').val() != 0) {
                    // panggil #total_pemabayan lalu text nya diisi string bayar di tambah value dari response.data.bayar_rp
                    $('#total_pembayaran').text('Bayar: ' + response.data.bayar_rp);
                    // panggil #total_pembayaran_format_terbilang lalu text nya diisi response.data.terbilang
                    $('#total_pembayaran_format_terbilang').text(response.data.terbilang);
                };
            });
    };

    // hapus 1 baris data dari table penjualan_detail
    function hapus_data(url) {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Anda tidak akan dapat mengembalikkan ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(url, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'delete'
                    })
                    .done((response) => {
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        );
                        table_penjualan_detail.ajax.reload(() => muat_ulang_form($('#diskon').val()));
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menghapus data karena kesalahan code.');
                        return;
                    });

            }
        })
    };

    // Jika input #uang_diterima dimasukkan angka atau diubah maka jalnkan fungsi berikut
    $('#uang_diterima').on('input', function() {
        // jika input #uang_diterima, valuenya ada lalu dihapus sampai kosong maka
        // jika tidak ada value di input uang_diterima
        if (!$(this).val()) {
            // input uang_diterima diisi dengan 0 lalu ada efek pilih
            $(this).val(0).select();
        };

        // panggil value input uang_diterima lalu ubah misalnya value nya Rp 100.000 menjadi 100000 lalu simpan ke variable uang_diterima
        let uang_diterima = parseInt($(this).val().replace(/[^0-9]/g, ''));;
        // panggil value input harus_bayar yang hidden atau panggil #harus_bayar lalu ambil value input nya
        let harus_bayar = $("#harus_bayar").val();

        // jika value input uang_diterima(pelanggan) misalnya Ro.10.000 lebih kecil dari value input harus_bayar(total_harga) misalnya Rp 120.000(integer) maka
        if (uang_diterima < harus_bayar) {
            // panggil #uang_kembalian lalu vaue nya diatur ke 0, sudah ada Rp nya karena sudah menggunakan package inputmask
            $("#uang_kembalian_pelanggan").val(0);
        }
        // lain jika value input uang_diterima(pelanggan) misalnya 150.000(integer) sama dengan value input harus_bayar
        else if (uang_diterima == harus_bayar) {
            //  panggil #uang_kembalian lalu value nya diatur ke 0, sudah ada Rp nya karena sudah menggunakan package inputmask
            $("#uang_kembalian_pelanggan").val(0);
        }
        // lain jika value input uang_diterima(pelanggan) misalnya 150.000(integer) lebih besar dari input harus_bayar misalnya Rp 120.000(integer) maka
        else if (uang_diterima > harus_bayar) {
            // berisi value variable uang_diterima dikurang value variable harus_bayar
            let uang_kembalian = uang_diterima - harus_bayar;
            // hasilnya masuk ke value input uang_kembalian
            $("#uang_kembalian_pelanggan").val(uang_kembalian);
            // console.log(uang_kembalian);
        };
    });


    // jika #tombol_simpan_penjualan di click maka jalankan fungsi berikut
    $("#tombol_simpan_penjualan").on("click", function() {
        // jika value input #keterangan_penjualan sama dengan tidak ada
        if (!$("#keterangan_penjualan").val()) {
            // tampilkan notifikasi menggunakan sweetalert yang berisi pesan berikut
            Swal.fire("Input Keterangan Harus Diisi");
            // matikan kode dengan cara kembali agar kode dibawahnya tidak berjalan atau agar ajax tidak berjalan
            return;
        };

        // jika value input #tanggal_dan_waktu sama dengan tidak ada maka
        if (!$("#tanggal_dan_waktu").val()) {
            // tampilkan notifikasi menggunakan sweetalert yang berisi pesan berikut
            Swal.fire("Input tanggal dan waktu Harus Diisi");
            // matikan kode dibawah beserta ajax nya dengan cara return
            return;
        };

        // jika value input uang_diterima sama dengan tidak ada
        if (!$("#uang_diterima").val()) {
            // tampilkan notifikasi menggunakan sweetalert yang berisi pesan berikut
            Swal.fire('Input Uang Diterima Harus Diisi.');
            // matikan kode dengan cara kembali agar kode dibawah nya tidak berjalan atau agar ajax tidak berjalan
            return;
        }
        // jika value input uang_diterima sama dengan 0
        else if ($("#uang_diterima").val() === "0") {
            // tampilkan notifikasi menggunakan sweetalert yang berisi pesan berikut
            Swal.fire('Input Uang Diterima tidak boleh sama dengan 0.');
            // matikan kode dengan cara kembali
            return;
        };

        // berisi array agar aku bisa menyimpan semua produk_id yang ada di table penjualan_detail.index
        let semua_produk_id = [];
        let semua_kode_produk = [];
        let semua_nama_produk = [];
        let semua_harga_jual = [];
        let semua_jumlah = [];
        let semua_subtotal = [];





        // lakukan pengulangan terhadap semua .kode_produk
        // setiap .kode_produk, jalankan fungsi berikut, aku melakukan pengulangan karena pada baris pertama dan seterusnya, .kode_produk di buat di script
        $(".kode_produk").each(function() {
            // mulai pengulangan
            // berisi panggil semua .kode_produk, lalu ambil semua value dari attribute data-produk-id
            let produk_id = $(this).data("produk-id");

            // panggil array semua_produk_id, dorong setiap value dari variable produk_id
            semua_produk_id.push(produk_id);

            // berisi panggil semua .kode_produk lalu ambil text nya
            let kode_produk = $(this).text();
            // panggil array semua_produk_id lalu dorong semua value variable kode_produk ke dalam array itu
            semua_kode_produk.push(kode_produk);
        });

        console.log(semua_produk_id);

        // lakukan pengulangan terhadap semua .nama_produk
        // setiap .nama_produk, jalankan fungsi berikut, aku melakukan pengulangan karena pada baris pertama dan seterusnya, .nama_produk di buat di script
        $(".nama_produk").each(function() {
            // berisi panggil semua .nama_produk lalu ambil text nya
            let nama_produk = $(this).text();
            // panggil array semua_nama_produk lalu dorong semua value variable nama_produk ke dalam array itu
            semua_nama_produk.push(nama_produk);
        });

        // lakukan pengulangan terhadap semua .harga_jual
        // setiap .harga_jual, jalankan fungsi berikut, aku melakukan pengulangan karena pada baris pertama dan seterusnya, .harga_jual di buat di script
        $(".harga_jual").each(function() {
            // berisi panggil semua .harga_jual lalu ambil text nya, ubah string misalnya Rp 100.000 menjadi 100000
            let harga_jual = parseInt($(this).text().replace(/[^0-9]+/g,''));
            // panggil array semua_harga_jual lalu dorong semua value variable harga_jual ke dalam array itu
            semua_harga_jual.push(harga_jual);
        });

        // lakukan pengulangan terhadap semua .jumlah
        // setiap .jumlah, jalankan fungsi berikut, aku melakukan pengulangan karena pada baris pertama dan seterusnya, .jumlah di buat di script
        $(".jumlah").each(function() {
            // berisi panggil semua .jumlah lalu ambil value nya, lalu ubah string ke integer, misalnya "10" akan menjadi 10
            let jumlah = parseInt($(this).val().replace(/[^0-9]+/g,''));
            // panggil array semua_jumlah lalu dorong semua value variable jumlah ke dalam array itu
            semua_jumlah.push(jumlah);
        });

        // lakukan pengulangan terhadap semua .subtotal
        // setiap .subtotal, jalankan fungsi berikut, aku melakukan pengulangan karena pada baris pertama dan seterusnya, .subtotal di buat di script
        $(".subtotal").each(function() {
            // berisi panggil semua .subtotal lalu ambil text nya, ubah string misalnya "Rp 1.000.000" menjadi 1000000
            let subtotal = parseInt($(this).text().replace(/[^0-9]+/g,''));
            // panggil array semua_subtotal lalu dorong semua value variable subtotal ke dalam array itu
            semua_subtotal.push(subtotal);
        });

        // panggil #member_id lalu ambil value nya
        let member_id = $("#member_id").val();
        // panggil #total_barang lalu ambil value nya
        let total_barang = $("#total_barang").val();
        // panggll #total_harga lalu ambil value nya
        let total_harga = $("#total_harga").val();
        // panggil #diskon lalu ambil value nya
        let diskon = $("#diskon").val();
        // panggil #harus_bayar lalu ambil value nya
        let harus_bayar = $("#harus_bayar").val();
        // panggil #uang_diterima lalu ambil value nya
        let uang_diterima = $("#uang_diterima").val();
        // panggil #keterangan_penjualan lalu ambil value nya
        let keterangan_penjualan = $("#keterangan_penjualan").val();
        // panggil #tanggal_dan_waktu lalu ambil value nya
        let tanggal_dan_waktu = $("#tanggal_dan_waktu").val();

        // lakukan ajax untuk mengirim semua value input
        $.ajax({
            // url panggil route penjualan_detail.store
            url: "{{ route('penjualan_detail.store') }}",
            // tipe memanggil route tipe post / kirim
            type: 'POST',
            // kirimkan aata berupa object yang berisi key dan value
            data: {
                // untuk keamanan dari serangan csrf
                // key _token berisi cetak fungsi csrf_token()
                "_token": "{{ csrf_token() }}",
                // key semua_produk_id berisi value array semua_produk_id
                semua_produk_id: semua_produk_id,
                semua_kode_produk: semua_kode_produk,
                semua_nama_produk: semua_nama_produk,
                semua_harga_jual: semua_harga_jual,
                semua_jumlah: semua_jumlah,
                semua_subtotal: semua_subtotal,
                member_id: member_id,
                total_barang: total_barang,
                total_harga: total_harga,
                diskon: diskon,
                harus_bayar: harus_bayar,
                uang_diterima: uang_diterima,
                keterangan_penjualan: keterangan_penjualan,
                tanggal_dan_waktu: tanggal_dan_waktu
            }
        })
        // jika selesai dan berhasil maka jalankan fungsi berikut lalu ambil tanggapan nya
        .done(function(resp) {
            // jika value tanggapan.status sama dengan 200 maka
            if (resp.status === 200) {
                // tampilkan notifikasi menggunakan package sweetalert
                Swal.fire({
                    icon: 'success',
                    title: 'Bagus',
                    text: 'Berhasil Melakukan Penjualan.',
                })
                // kemudian hasilnya maka jalankan fungsi berikut dan ambil hasil nya
                .then((result) => {
                    // berisi menangkap value resp.penjualan_id
                    let penjualan_id = resp.penjualan_id;

                    // jika aku click oke pada pop up sweetalert maka
                    // jika hasilnya dikonfirmasi maka
                    if (result.isConfirmed) {
                        // buka tab baru, panggil route penjualan_detail.index, _kosong
                        window.open("{{ route('penjualan_detail.index') }}", "_blank");
                        // pindah rute
                        // berisi panggil url /penjualan/nota-kecil/ lalu kirimkan value variable penjualan_id
                        window.location.href = `/penjualan/nota-kecil/${penjualan_id}`;
                    };
                });
            };
        });

    });
</script>
@endpush
