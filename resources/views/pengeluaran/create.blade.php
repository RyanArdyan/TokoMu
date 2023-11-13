{{-- memperluas parentnya yaitu layouts.app --}}
@extends('layouts.app')

{{-- dorong css lalu tangkap menggunakan $stack('css') --}}
@push('css')
@endpush

{{-- kirim value section title ke @Yield('title') --}}
@section('title', 'Pengeluaran Baru')

{{-- kirim value section konten ke @yield('konten') --}}
@section('konten')
    <!-- Content Wrapper. Contains page content -->
    <form id="form_tambah">
        {{-- laravel mewajibkan keamanan dari serangan csrf --}}
        @csrf
        <div class="row">
            <div class="col-sm-3">
                {{-- is-invalid --}}
                {{-- waktu_pengeluaran --}}
                <div class="form-group">
                    <label for="waktu_pengeluaran">Waktu Pengeluaran (bulan/tanggal/waktu)<span class="text-danger"> *</span></label>
                    {{-- attribute name digunakan untuk mengirim value attribute value --}}
                    <input id="waktu_pengeluaran" name="waktu_pengeluaran" class="waktu_pengeluaran_input input form-control"
                        type="datetime-local" style="width: 250px">
                    {{-- pesan error --}}
                    <span class="waktu_pengeluaran_error pesan_error text-danger"></span>
                </div>
            </div>

            <div class="col-sm-3">
                {{-- is-invalid --}}
                {{-- diterima_oleh --}}
                <div class="form-group">
                    <label for="diterima_oleh">Diterima Oleh<span class="text-danger"> *</span></label>
                    {{-- attribute name digunakan untuk mengirim value attribute value --}}
                    <input id="diterima_oleh" name="diterima_oleh" class="diterima_oleh_input input form-control"
                        type="text" placeholder="Diterima Oleh" autocomplete="off" value="{{ auth()->user()->name }}">
                    {{-- pesan error --}}
                    <span class="diterima_oleh_error pesan_error text-danger"></span>
                </div>
            </div>

            <div class="col-sm-6">
                {{-- is-invalid --}}
                {{-- nama_pengeluaran --}}
                <div class="form-group">
                    <label for="nama_pengeluaran">Nama Pengeluaran<span class="text-danger"> *</span></label>
                    {{-- attribute name digunakan untuk mengirim value attribute value --}}
                    <input id="nama_pengeluaran" name="nama_pengeluaran" class="nama_pengeluaran_input input form-control"
                        type="text" placeholder="Masukkan Nama Pengeluaran" autocomplete="off">
                    {{-- pesan error --}}
                    <span class="nama_pengeluaran_error pesan_error text-danger"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                {{-- Total Jumlah --}}
                <div class="form-group">
                    <label for="jumlah_pengeluaran">Total Jumlah<span class="text-danger"> *</span></label>
                    {{-- pada saat kunci ditekan maka panggil fungsi number lalu kirimkan acaraya agar aku bisa menonaktifkan value huruf dan hanya memperbolehkan value angka --}}
                    {{-- attribute data-inputmask agar 1000 menjadi 1.000 --}}
                    <input id="jumlah_pengeluaran" name="jumlah_pengeluaran"
                        class="input_angka jumlah_pengeluaran_input input form-control" type="text" autocomplete="off" value="0"
                        onkeypress="return number(event)"
                        data-inputmask="'alias': 'decimal', 'groupSeparator':  '.',  'removeMaskOnSubmit': true, 'autoUnMask': true, 'rightAlign': false, 'radixPoint': ','"
                        readonly>
                    {{-- pesan error --}}
                    <span class="jumlah_pengeluaran_error pesan_error text-danger"></span>
                </div>
            </div>

            <div class="col-sm-6">
                {{-- total_pengeluaran --}}
                <div class="form-group">
                    <label for="total_pengeluaran">Total Pengeluaran<span class="text-danger"> *</span></label>
                    {{-- pada saat kunci ditekan maka panggil fungsi number lalu kirimkan acaraya agar aku bisa menonaktifkan value huruf dan hanya memperbolehkan value angka --}}
                    {{-- attribute data-inputmask agar 1000 menjadi Rp 1.000 --}}
                    <input id="total_pengeluaran" name="total_pengeluaran"
                        class="input_angka total_pengeluaran_input input form-control" type="text" autocomplete="off" value="0"
                        onkeypress="return number(event)"
                        data-inputmask="'alias': 'decimal', 'prefix': 'Rp ', 'groupSeparator':  '.',  'removeMaskOnSubmit': true, 'autoUnMask': true, 'rightAlign': false, 'radixPoint': ','"
                        readonly>
                    {{-- pesan error --}}
                    <span class="total_pengeluaran_error pesan_error text-danger"></span>
                </div>
            </div>
        </div>
    </form>

    <button id="tombol_tambah_detail_pengeluaran_baru" class="btn btn-success btn-sm" type="button">Tambah Detail Pengeluaran Baru</button> 
    <a href="{{ route('pengeluaran.index') }}" class="btn btn-sm btn-danger">Kembali</a>
    <h4 class="my-3 text-center text-bold">Detail Pengeluaran</h4>
    
    {{-- aku menyimpan formulir pengeluaran detail di dalam table beriut --}}
    <table id="table_pengeluaran_detail" class="table table-striped table-sm table-bordered" style="width:100%">
        <thead class="bg-primary">
            <tr>
                <th scope="col">Nama Pengeluaran</th>
                <th scope="col">Jumlah</th>
                <th scope="col">Harga Satuan</th>
                <th scope="col">Subtotal</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
    </table>

    <button id="tombol_simpan_pengeluaran" class="btn btn-purple" type="button">Simpan Pengeluaran</button>
@endsection

{{--  --}}

{{-- dorong value script --}}
@push('script')
    <script>
        // package Input Mask - Robin Herbots
        // aku perlu ini agar Rp 1.000 akan menjadi 1000 ketika sudah di controller
        // 1000 akan menjadi 1.000
        $(".input_angka").inputmask();


        // hanya izinkan user memasukkan angka di input yang telah di tentukan
        function number(event) {
            let charCode = (event.which) ? event.which : event.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            } else {
                return true;
            };
        };

        // awal fitur isi input_waktu_pengeluaran secara otomatis() {
        // berisi memanggil #waktu_pengeluaran
        const input_waktu_pengeluaran = $("#waktu_pengeluaran");
        // berisi waktu sekarang
        let waktu_sekarang = new Date();
        var opsi_zona_waktu = { timeZone: 'Asia/Jakarta' };


        // Dapatkan tanggal dan waktu terpisah
        var tahun = waktu_sekarang.getFullYear();
        var bulan = String(waktu_sekarang.getMonth() + 1).padStart(2, '0');
        var tanggal = String(waktu_sekarang.getDate()).padStart(2, '0');
        var jam = String(waktu_sekarang.getHours()).padStart(2, '0');
        var menit = String(waktu_sekarang.getMinutes()).padStart(2, '0');
        // Gabungkan tanggal dan waktu dalam format yang sesuai dengan input datetime-local
        var waktu_final = `${tahun}-${bulan}-${tanggal}T${jam}:${menit}`;

        // memanggil variable input_waktu_pengeluaran lalu value input nya diisi value variable waktu_yg_diformat
        input_waktu_pengeluaran.val(waktu_final);
        // akhir fitur isi input_waktu_pengeluaran secara_otomatis() 

        // panggil #table_pengeluaran_detail lalu gunakan datatable - datatables package
        let table_pengeluaran_detail = $("#table_pengeluaran_detail").DataTable({
            // hilangkan pencarian
            // pencarian: mati
            searching: false,
            // hilangkan paginasi
            // paginasi: mati
            paging: false
        });

        // inisialisasi angka untuk fitur melakukan perkalian kepada value input jumlah ke harga satuan, jadi jumlah * harga satuan
        // initializing the numbers for the feature multiplies the input value, quantity to unit price so quantity * unit price
        let angka = 0;

        // cALL #tombol_tambah_detail_pengeluaran_baru, if the button is clicked then run following function
        $("#tombol_tambah_detail_pengeluaran_baru").on("click", function() {
            // panggil value variable angka lalu ditambah 1, misalnya 0 + 1 = 1
            angka += 1;

            // panggil #table_pengeluaran_detail yg sudah menggunakan datatable lalu tambahkan baris berikut
            table_pengeluaran_detail.row
                // .tambahkan array berikut
                .add([
                    `
                    <td>

                        <input name="nama_pengeluaran" type="text" class="nama_pengeluaran_detail nama_pengeluaran input_pengeluaran_detail form-control">
                    </td>`,
                    `<td>
                        <!-- anggaplah data-angka berisi 1, jadi untuk fitur,  melakukan perkalian kepada value input jumlah ke harga satuan, jadi jumlah * harga satuan  -->
                        <input name="jumlah" data-angka="${angka}" type="number" class="jumlah jumlah_${angka} input_pengeluaran_detail form-control" value="1">
                    </td>`,
                    `<td>
                        <input name="harga_satuan" type="text" class="harga_satuan harga_satuan_${angka} input_pengeluaran_detail form-control" data-angka="${angka}" onkeypress="return number(event)" value="1000">
                    </td>`,
                    `<td>
                        <input name="subtotal" type="text" class="subtotal subtotal_${angka} input_pengeluaran_detail form-control" onkeypress="return number(event)" readonly value="1000">
                    </td>`,
                    `<td>
                        <button data-angka="${angka}" class="tombol_hapus_detail_pengeluaran btn btn-sm btn-danger">Hapus</button>
                    </td>    
                    `
                ]).draw(false);
                

                
                // inputmask berfungsi misalnya mengubah 1000 menjadi Rp 1.000
                // panggil semua .subtotal lalu dikasi inputMask numeric
                $(".subtotal").inputmask('numeric',{
                    // lalu alias nya adalah numeric
                    // then the alias is numeric
                    alias: 'numeric',
                    // awalan nya adalah Rp
                    prefix: 'Rp ',
                    // mungkin ini desimal
                    radixPoint: ",",
                    // grupPemisah untuk ribuan
                    groupSeparator: ".",
                    // autoLepasTopeng: true, jadi ketika aku console.log pun dia masih 10000 bukan nya Rp 10.000
                    autoUnmask: true
                });

                // panggil semua .harga_satuan lalu dikasi inputMask numeric
                $(".harga_satuan").inputmask('numeric',{
                    // lalu alias nya adalah numeric
                    alias: 'numeric',
                    // awalan nya adalah Rp
                    prefix: 'Rp ',
                    radixPoint: ",",
                    // grupPemisah untuk ribuan
                    groupSeparator: ".",
                    // autoLepasTopeng: true, jadi ketika aku console.log pun dia masih 10000
                    autoUnmask: true
                });

                // berisi angka 0 untuk inisialisasi total_pengeluaran
                let total_pengeluaran = 0;

                // lakukan pengulangan terhadap semua .subtotal 
                // repeat all 
                // setiap .subtotal, jalankan fungsi berikut, aku melakukan pengulangan karena pada baris pertama dan seterusnya, .subtotal di buat di script
                $(".subtotal").each(function() {
                    // mulai pengulangan
                    // berisi panggil semua .subtotal, lalu setiap value dari input nya
                    let subtotal = $(this).val();

                    // panggil value total_pengeluaran lalu value nya ditambah sama dengan value variable subtotal 
                    total_pengeluaran += parseInt(subtotal);
                });

                // panggil #total_pengeluaran lalu value nya diisi oleh value variable total_pengeluaran
                // call #total_expenses then the value is filled in by the  total variable value
                $("#total_pengeluaran").val(total_pengeluaran);


                // berisi angka 0 untuk inisialisasi total_jumlah
                let total_jumlah = 0;

                // lakukan pengulangan terhadap semua .jumlah 
                // repeat all 
                // setiap .jumlah, jalankan fungsi berikut, aku melakukan pengulangan karena pada baris pertama dan seterusnya, .jumlah di buat di script
                $(".jumlah").each(function() {
                    // mulai pengulangan
                    // berisi panggil semua .jumlah, lalu setiap value dari input nya
                    let jumlah = $(this).val();

                    // panggil value total_jumlah lalu value nya ditambah sama dengan value variable jumlah 
                    total_jumlah += parseInt(jumlah);
                });

                // panggil #total_jumlah lalu value nya diisi oleh value variable total_jumlah
                // call #total_expenses then the value is filled in by the  total variable value
                $("#jumlah_pengeluaran").val(total_jumlah);
        });

        // panggil tbody yang berada di dalam #table_pengeluaran_detail, jika di click yg class nya adalah class berikut, maka jalankan fungsi berikut
        // call tbody which is in #table_pengeluaran_detail, if you click the class is the following class, then run the following function
        $("#table_pengeluaran_detail tbody").on("click", '.tombol_hapus_detail_pengeluaran', function() {
            // panggil .tombol_hapus_detaiL_pengeluaran lalu ambil value dari attribute data-angka
            // call .tombol_hapus_detail_pengeluaran then take the value from the data-number attribute
            let angka = $(this).data('angka');
            
            // panggil value dari #total_pengeluaran
            // call the value of #total_expense
            let total_pengeluaran = parseInt($("#total_pengeluaran").val());

            // berisi misalnya .subtotal_1 lalu ambil value nya
            let subtotal = $(`.subtotal_${angka}`).val();

            // berisi total_pengeluaran - subtotal dan dimasukkan ke dalam variable total_pengeluaran
            total_pengeluaran = total_pengeluaran - subtotal;

            // panggil #total_pengeluaran lalu value nya diisi dengan value variable total_pengeluaran
            $("#total_pengeluaran").val(total_pengeluaran);

            // panggil value dari #jumlah_pengeluaran
            // call the value of #jumlah_pengeluaran
            let total_jumlah = parseInt($("#jumlah_pengeluaran").val());

            // berisi misalnya .jumlah_1 lalu ambil value nya lalu ubah tipe data nya dari string menjadi integer
            let jumlah = parseInt($(`.jumlah_${angka}`).val());

            // berisi total_jumlah - jumlah dan dimasukkan ke dalam variable total_jumlah
            total_jumlah = total_jumlah - jumlah;

            // panggil #jumlah_pengeluaran lalu value nya diisi dengan value variable total_jumlah
            $("#jumlah_pengeluaran").val(total_jumlah);

            // panggil variable table_pengeluaran_detail
            table_pengeluaran_detail
                // .baris, panggil class tombol_hapus_detail_pengeluaran, panggil orang tuanya yaitu tr
                .row($(this).parents('tr'))
                // hapus tr dan anak yg terpilih
                .remove()
                // menggambar atau refresh datatable
                .draw();
        });



        // jika document di masukkan angka atau di ubah yang class nya adalah .jumlah maka jalankan fungsi berikut
        $(document).on('input', '.jumlah', function() {
            // panggil .jumlah yang di click lalu ambil value attribute data-angka, anggaplah berisi 1
            let angka = $(this).data("angka");

            // // panggil .jumlah, value dari attribute data-id
            // let penjualan_detail_id = $(this).data('id');

            // berisi panggil class .jumlah yg di click lalu ambil value nya
            // let input_jumlah = $(this).val();

            // berisi panggil class .jumlah yg di click lalu ambil value nya
            let jumlah = $(this).val();

            // misalnya panggil class harga_satuan_1, lalu ambil value input nya
            let harga_satuan = $(`.harga_satuan_${angka}`).val();

            // console.log(typeof(harga_satuan));

            // jika value jumlah lebih kecil atau sama dengan 0
            if (jumlah <= 0) {
                // panggil .jumlah lalu value atau nilai nya di set atau di tetapkan ke 1
                $(this).val(1);
                // tampilkan peringatan berikut
                // display the following warning
                Swal.fire('Jumlah tidak boleh kurang dari 1');
                // kode selesai dan berhenti
                // code completes and stops
                return;
            }
            // lain jika tidak ada value dari .jumlah maksudnya ada angka 1 di input jumlah lalu aku seleksi angka nya lalu aku hapus
            // Otherwise, if there is no value from .amount, it means there is a number 1 in the amount input, then I select the number then I delete it
            else if (!jumlah) {
                // panggil .jumlah, lalu value atau nilai nya di set atau di atur ke 1
                // call .amount, then the value is set or set to 1
                $(this).val(1);
                // kode selesai dan berhenti
                // code completes and stops
                return;
            };

            // contains the value of the quantity variable * the value of the unit_price variable
            // berisi value variable jumlah * value variable harga_satuan 
            let hasil = jumlah * harga_satuan;

            // misalnya berisi panggil class .subtotal_1 lalu value input nya diisi menggunakan value variable hasil misalnya 10000
            $(`.subtotal_${angka}`).val(hasil);

            // berisi value 0
            let total_pengeluaran = 0;

            // lakukan pengulangan terhadap semua .subtotal 
            // repeat all 
            // setiap .subtotal, jalankan fungsi berikut, aku melakukan pengulangan karena pada baris pertama dan seterusnya, .subtotal di buat di script
            $(".subtotal").each(function() {
                // mulai pengulangan
                // berisi panggil semua .subtotal, lalu setiap value dari input nya
                let subtotal = $(this).val();

                // call the variable value total_pengeluaran then add the value to the value of the subtotal variable then convert it to an integer
                // panggil value variable total_pengeluaran lalu value nya ditambah sama dengan value variable subtotal lalu konversi ke integer
                total_pengeluaran += parseInt(subtotal);
            });

            // call #total_expenses then the value is filled in by the variable value total_expenses
            // panggil #total_pengeluaran lalu value nya diisi oleh value variable total_pengeluaran
            $("#total_pengeluaran").val(total_pengeluaran);




            // berisi value 0
            let total_jumlah = 0;

            // lakukan pengulangan terhadap semua .jumlah 
            // repeat all 
            // setiap .jumlah, jalankan fungsi berikut, aku melakukan pengulangan karena pada baris pertama dan seterusnya, .jumlah di buat di script
            $(".jumlah").each(function() {
                // mulai pengulangan
                // berisi panggil semua .jumlah, lalu setiap value dari input nya
                let jumlah = $(this).val();

                // panggil value variable total_jumlah lalu value nya ditambah sama dengan value variable jumlah
                total_jumlah += parseInt(jumlah);
            });

            // call #amount_of_expenses then the value is filled in by the variable value total_amount
            // panggil #jumlah_pengeluaran lalu value nya diisi oleh value variable total_jumlah
            $("#jumlah_pengeluaran").val(total_jumlah);
        });

        // jika document di masukkan angka atau di ubah yang class nya adalah .harga_satuan maka jalankan fungsi berikut
        $(document).on('input', '.harga_satuan', function() {
            // console.log($(this).val());

            // panggil .harga_satuan yang di click lalu ambil value attribute data-angka misalnya 1
            let angka = $(this).data("angka");

            // panggil class .harga_satuan yg di click lalu ambil value nya misalnya 2000
            let harga_satuan = $(this).val();

            // misalnya panggil class jumlah__1, lalu ambil value nya
            let jumlah = $(`.jumlah_${angka}`).val();

            // jika value harga_satuan lebih kecil atau sama dengan 0
            if (harga_satuan <= 0) {
                // panggil .harga_satuan lalu value atau nilai nya di set atau di tetapkan ke 1
                $(this).val(1);
                // tampilkan peringatan berikut
                Swal.fire('harga satuan tidak boleh kurang dari 1');
                // kode selesai dan berhenti
                return;
            }
            // lain jika tidak ada value dari .harga_satuan maksudnya ada angka 1 di input harga_satuan lalu aku seleksi angka nya lalu aku hapus
            else if (!harga_satuan) {
                // panggil .harga_satuan, lalu value atau nilai nya di set atau di tetapkan ke 1
                $(this).val(1);
                // kode selesai dan berhenti
                return;
            };

            // berisi value variable harga_satuan * value variable jumlah
            let hasil = harga_satuan * jumlah;

            // misalnya berisi panggil class .subtotal_1 lalu value nya diisi menggunakan value variable hasil
            $(`.subtotal_${angka}`).val(hasil);
            
            // berisi 0 
            let total = 0;

            // lakukan pengulangan terhadap semua .subtotal 
            // repeat all 
            // setiap .subtotal, jalankan fungsi berikut, aku melakukan pengulangan karena pada baris pertama dan seterusnya, .subtotal di buat di script
            $(".subtotal").each(function() {
                // mulai pengulangan
                // berisi panggil semua .subtotal, lalu setiap value dari input nya
                let subtotal = $(this).val();

                // panggil value total lalu value nya ditambah 
                total += parseInt(subtotal);
            });

            // panggil #total_pengeluaran lalu value nya diisi oleh value variable total
            // call #total_expenses then the value is filled in by the  total variable value
            $("#total_pengeluaran").val(total);
        });


        // start of save expense feature
        // awal fitur simpan pengeluaran
        // if the #save_expense button is clicked then run the following function
        // jika #tombol_simpan_pengeluaran di click maka jalankan fungsi berikut
        $("#tombol_simpan_pengeluaran").on("click", function() {
            // if the input value #expend_time is equal none
            // jika value input #waktu_pengeluaran sama dengan tidak ada
            if (!$("#waktu_pengeluaran").val()) {
                // display a notification using sweetalert that containing the following message
                // tampilkan notifikasi menggunakan sweetalert yang berisi pesan berikut
                Swal.fire("Input Waktu Keterangan Harus Diisi");
                // turn off the code below, so that the code below does not run or so that ajax does not run
                // matikan kode dibawahnya, agar kode dibawahnya tidak berjalan atau agar ajax tidak berjalan
                return;
            };

            // jika value input #diterima_oleh sama dengan tidak ada
            if (!$("#diterima_oleh").val()) {
                // tampilkan notifikasi menggunakan sweetalert yang berisi pesan berikut
                Swal.fire("Input 'Diterima Oleh' Harus Diisi");
                // matikan kode dengan cara kembali agar kode dibawahnya tidak berjalan atau agar ajax tidak berjalan
                return;
            };

            // jika value input #nama_pengeluaran sama dengan tidak ada
            if (!$("#nama_pengeluaran").val()) {
                // tampilkan notifikasi menggunakan sweetalert yang berisi pesan berikut
                Swal.fire("Input 'Nama Pengeluaran' Harus Diisi");
                // matikan kode dengan cara kembali agar kode dibawahnya tidak berjalan atau agar ajax tidak berjalan
                return;
            };

            // if the input value #expense_amount is less than or equal to 0
            // jika value input #jumlah_pengeluaran lebih kecil atau sama dengan 0
            if ($("#jumlah_pengeluaran").val() <= 0) {
                // tampilkan notifikasi menggunakan sweetalert yang berisi pesan berikut
                Swal.fire("Input 'Jumlah Total' Tidak boleh lebih kecil atau sama dengan 0");
                // matikan kode dengan cara kembali agar kode dibawahnya tidak berjalan atau agar ajax tidak berjalan
                return;
            };

            // jika value input #total_pengeluaran lebih kecil atau sama dengan 0
            if ($("#total_pengeluaran").val() <= 0) {
                // tampilkan notifikasi menggunakan sweetalert yang berisi pesan berikut
                Swal.fire("Input 'Total Pengeluaran' Tidak boleh lebih kecil atau sama dengan 0");
                // matikan kode dengan cara kembali agar kode dibawahnya tidak berjalan atau agar ajax tidak berjalan
                return;
            };

            // call #expense_time, then take the value
            // panggil #waktu_pengeluaran, lalu ambil value nya
            let waktu_pengeluaran = $("#waktu_pengeluaran").val();
            let diterima_oleh = $("#diterima_oleh").val();
            let nama_pengeluaran = $("#nama_pengeluaran").val();
            // Convert DATA TYPE FROM STRING TO INTEGER belonging to value #expenditure_amount, then take the value
            // KONVERT TIPE DATA DARI STRING KE INTEGER milik value #jumlah_pengeluaran, lalu ambil value nya
            // 
            let jumlah_pengeluaran = parseInt($("#jumlah_pengeluaran").val());
            let total_pengeluaran = parseInt($("#total_pengeluaran").val());

            // Create an array of expenditure_form_data to hold the input2x values
            // Membuat array data_formulir_pengeluaran untuk menampung value input2x nya
            let data_formulir_pengeluaran = [];

            // push the objects into the data array
            // push object2x ke dalam array
            data_formulir_pengeluaran.push(
                // index 0
                {
                    // key waktu_pengeluaran diisi dengan value variable waktu_pengeluaran
                    "waktu_pengeluaran": waktu_pengeluaran,
                    "diterima_oleh": diterima_oleh,
                    "nama_pengeluaran": nama_pengeluaran,
                    "total_jumlah": jumlah_pengeluaran,
                    "total_pengeluaran": total_pengeluaran
                },
            );

            // call all .detail_expense_name
            // panggil semua .nama_pengeluaran_detail
            const semua_nama_pengeluaran_detail = $(".nama_pengeluaran_detail");
            const semua_jumlah_pengeluaran_detail = $(".jumlah");
            const semua_harga_satuan_pengeluaran_detail = $(".harga_satuan");
            const semua_subtotal_pengeluaran_detail = $(".subtotal");
            
            // Create an array to hold the input values from the detailed expenditure form
            // Buat array untuk menampung value input2x dari formulir pengeluaran detail
            let data_formulir_pengeluaran_detail = [];

            // Loop to retrieve data from input expense_name, amount and others
            // Loop untuk mengambil data dari input nama_pengeluaran, jumlah dan lain-lain
            // Repeat as many times as the length of the expenditure_name input, for example 2.
            // Lakukan pengulangan sebanyak panjang input nama_pengeluaran misalnya 2.
            for (let i = 0; i < semua_nama_pengeluaran_detail.length; i++) {
                // then push the data containing the object into the detailed expense form data variable
                // lalu push data yang berisi objek ke dalam variable data_formulir_pengeluaran_detail
                data_formulir_pengeluaran_detail.push({
                    // expense_name key containing example all_detail_expense_name[0].value contains "Raw Goods A", all_detail_expense_name[1].value contains "Raw Goods B"
                    // key nama_pengeluaran berisi misalnya semua_nama_pengeluaran_detail[0].value berisi "Barang mentah A", semua_nama_pengeluaran_detail[1].value berisi "Barang Mentah B"
                    'nama_pengeluaran': semua_nama_pengeluaran_detail[i].value,
                    // parseInt means changing the data type of the argument from string to integer
                    // parseInt berarti mengubah tipe data argumnt nya dari string menjadi integer
                    'jumlah': parseInt(semua_jumlah_pengeluaran_detail[i].value),
                    'harga_satuan': parseInt(semua_harga_satuan_pengeluaran_detail[i].value),
                    'subtotal': parseInt(semua_subtotal_pengeluaran_detail[i].value),
                });
            };


            // timpa value variable data_formulir_pengeluaran_detail
            // JSON.stringify berfungsi mengonversi data javascript menjadi string JSON
            data_formulir_pengeluaran_detail = JSON.stringify(data_formulir_pengeluaran_detail);


            // JQuery do ajax
            // jquerry lakukan ajax
            $.ajax({
                // url calls route expense.store
                // url memanggil route pengeluaran.store
                url: "{{ route('pengeluaran.store') }}",
                // contains calling route type POST
                // berisi memanggil route tipe POST
                type: "POST",
                // contains sending an object
                // berisi mengirimkan sebuah objek
                data: {
                    // laravel requires security from csrf attacks
                    // laravel mewajibkan keamanan dari serangan csrf
                    // _token key contains print method csrf_token()
                    // kunci _token berisi mencetak method  csrf_token()
                    "_token": "{{ csrf_token() }}",
                    // key expense_form_data contains the value of expense_form_data variable that contains the object
                    // key data_formulir_pengeluaran berisi value variable data_formulir_pengeluaran yg berisi objek
                    "data_formulir_pengeluaran": data_formulir_pengeluaran,
                    'data_formulir_pengeluaran_detail': data_formulir_pengeluaran_detail
                },
            })
            // if completed and successful then run the following function and take the response
            // jika selesdi dan berhasil maka jalankan fungsi berikut dan ambil tanggapannya
            .done(function(resp) {
                // if the data was saved successfully
                // jika data berhasil disimpan
                // if response.status equals 200
                // jika tanggapan.status sama dengan 200
                if (resp.status === 200) {
                    // reset formulir pengeluaran
                    // panggil #form_tambah, index 0, lalu atur ulang
                    $("#form_tambah")[0].reset();
                    // menghapus tbody dan turunannya agar input2x pengeluaran detail di hapus
                    $("td").remove();
                    // nama member di focuskan
                    // panggil #waktu_pengeluaran lalu di fokuskan
                    $("#waktu_pengeluaran").focus();
                    // notifikasi menggunakan toastr
                    // toastr tipe sukses warna hijau dan tampilkan pesan
                    toastr.success(`${resp.pesan}.`);
                };
            });
        });
        // akhir fitur simpan pengeluaran

    </script>
@endpush
