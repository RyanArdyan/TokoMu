{{-- memperluas parentnya yaitu layouts.app --}}
@extends('layouts.app')

{{-- dorong css lalu tangkap mengguakan $stack('css') --}}
@push('css')
    {{-- buat element style --}}
    <style>
    </style>
@endpush


{{-- kirim value section title ke @Yield('title') --}}
@section("title", "Laporan")

{{-- kirim value section konten ke @yield('konten') --}}
@section('konten')
    {{-- cetak panggil fungsi tanggal-indoensia milik helpers lalu kirimkan value $tanggal_awal --}}
    <h3>Laporan Pendapatan {{ tanggal_indonesia($tanggal_awal) }} s/d
        {{ tanggal_indonesia($tanggal_hari_ini) }}</h3>
    <!-- Content Wrapper. Contains page content -->
    <div class="row mb-2">
        <div class="col-md-12 mt-2">
            <div class="mb-3">
                {{-- Jika tombol Ubah Periode di click maka panggil modal ubah periode --}}
                <button id="tombol_ubah_periode" type="button" class="btn btn-purple btn-sm">
                    <i class="mdi mdi-clock-check"></i> Ubah Periode
                </button>
                {{-- export semua data laporan ke file excel --}}
                {{-- panggil route laporan.cetak_pdf, jika aku sudah pernah mengubah periode di modal ubah periode maka aku akan menggunakan value $tangal_awal dan value $tanggal_akhir milik method ubah_periode --}}
                <a href="{{ route('laporan.cetak_pdf', [$tanggal_awal, $tanggal_hari_ini]) }}" class="btn btn-sm btn-success">
                    <i class="mdi mdi-file-excel"></i> Export PDF</a>
            </div>

            {{-- termasuk element table --}}
            @include('laporan.table')
            {{-- termasuk ada jika modal_ubah_periode di panggil --}}
            @include('laporan.modal_ubah_periode')
        </div>
    </div>
@endsection


@push('script')
<script>
    // read data menggunakan javascript, package laravel datatables dan datatables
    // berisi panggil #table_laporan_pendapatan guanakan datatable
    let table_laporan_pendapatan = $("#table_laporan_pendapatan").DataTable({
        // ketika data masih dimuat maka tampilkan animasi processing
        // pemrosesan: benar
        processing: true,
        // ambil data menggunakan server side
        // serverSisi: benar
        serverSide: true,
        // autoLebar mati
        autoWith: false,
        // lakukan ajax lalu ke route laporan.data, kirimkan $tangal_awal dan $tanggal_akhir yg didapatkan dari LaporanController method index atau method ubah_periode
        ajax: "{{ route('laporan.data', [$tanggal_awal, $tanggal_hari_ini]) }}",
        // ada name agar aku bisa mengirimkan datanya menggunakan formulir, anggaplah itu input name=""
        columns: [
            {
                // didapatkan dari LaporanController, method dapatkan_data
                data: 'tanggal',
                name: 'tanggal'
            },
            {
                data: 'penjualan',
                name: 'penjualan'
            },
            {
                data: 'pembelian',
                name: 'pembelian'
            },
            {
                data: 'pengeluaran',
                name: 'pengeluaran'
            },
            {
                data: 'pendapatan',
                name: 'pendapatan'
            },
        ],
        dom: 'Brt',
        // hilangkan dropdown sort atau fitur menampilkan 10 data, 50 data dan fitur search
        bSort: false,
        // menghilangkan paginasi
        bPaginate: false,
        // package datatable akan menggunakan bahasa indonesia
        language: {
            // berisi panggil public/terjemahan_datatable
            url: "/terjemahan_datatable/indonesia.json"
        }
    });

    // jika #tombol_ubah_periode di click maka jalankan fungsi berikut
    $("#tombol_ubah_periode").on("click", function() {
        // panggil #modal_ubah_periode lalu modalnya ditampilkan
        $("#modal_ubah_periode").modal('show')
    });
</script>
@endpush
