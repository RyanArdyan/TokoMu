{{-- memperluas parentnya yaitu layouts.app --}}
@extends('layouts.app')

{{-- dorong css lalu tangkap menggunakan $stack('css') --}}
@push('css')
@endpush

{{-- kirim value section title ke @Yield('title') --}}
@section('title', 'Pengeluaran')

{{-- kirim value section konten ke @yield('konten') --}}
@section('konten')
    <!-- Content Wrapper. Contains page content -->
    <div class="row mb-2">
        <div class="col-md-12 mt-2">

            {{-- termasuk element table dan form --}}
            @include('pengeluaran.table')

            {{-- import pengeluran dari file excel --}}
            <form action="{{ route('pengeluaran.import_excel') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                @csrf
                <input type="file" name="file" class="form-control">
                <button class="btn btn-info" class="form-control">Upload</button>
            </form>


            <div class="mt-2">
                {{-- jika aku click tombol pengeluaran baru maka pindah url --}}
                {{-- cetak panggil route pengeluaran.create --}}
                <a href="{{ route('pengeluaran.create') }}" class="btn btn-purple btn-sm">
                    <i class="mdi mdi-minus"></i>
                    Pengeluaran Baru
                </a>

                {{-- Fitur hapus beberapa pengeluaran berdasarkan kotak centang yang di checklist --}}
                <button id="tombol_hapus" type="button" class="btn btn-danger btn-flat btn-sm">
                    <i class="mdi mdi-delete"></i>
                    Hapus
                </button>

                {{-- export semua data pengeluran ke file excel --}}
                {{-- panggil route pengeluaran.export_excel --}}
                <a href="{{ route('pengeluaran.export_excel') }}" class="btn btn-sm btn-success">
                    <i class="mdi mdi-file-excel"></i> Excel</a>
            </div>

        </div>
    </div>
@endsection

@push('script')
<script>
    // read daftar pengeluaran
    // berisi panggil table pengeluaran, gunakan datatable
    let table = $("table").DataTable({
        // ketika data masih di muat, tampilkan animasi processing
        // processing: benar
        processing: true,
        // serverSide digunakan agar ketika data sudah lebih dari 10.000 maka web masih lancar
        // sisi server: benar
        serverSide: true,
        // lakukan ajax, ke route pengeluaran.read yang tipe nya adalah dapatkan
        ajax: "{{ route('pengeluaran.read') }}",
        // jika berhasil maka buat element <tbody>, <tr> dan <td> lalu isi td nya dengan data pengeluaran
        // kolom-kolom berisi array, di dalamnya ada object
        columns: [
            // kotak centang
            {
                data: "select",
                // menonaktifkan fungsi icon anak panah atau fitur balikkan data
                sortable: false
            },
            // lakukan pengulangan
            // DT_RowIndex di dapatkan dari laravel datatable
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                sortable: false
            },
            {
                data: 'tanggal_pengeluaran',
                name: 'tanggal_pengeluaran'
            },
            {
                data: 'nama_pengeluaran',
                name: 'nama_pengeluaran'
            },
            {
                data: 'total_pengeluaran',
                name: 'total_pengeluaran'
            },
            {
                data: 'action',
                name: 'action',
                sortable: false,
                searchable: false
            }
        ],
        // menggunakan bahasa indonesia di package datatables
        // bahasa berisi object
        language: {
            // url memanggil folder public/
            url: "/terjemahan_datatable/indonesia.json"
        }
    });

    // pilih semua
    // jika #pilih_semua di click maka jalankan fungsi berikut
    $("#select_all").on("click", function() {
        // jika #pilih_semua di centang maka
        if ($("#select_all").prop("checked")) {
            // panggil .pilih lalu centang nya benar
            $(".pilih").prop("checked", true);
        } 
        // jika #pilih_semua tidak di centang maka
        else {
            // panggil .pilih lalu centang nya dihapus atau salah
            $(".pilih").prop("checked", false);
        };
    });

    // Delete
    // jika #tombol_hapus di click maka jalankan fungsi berikut dan ambil event nya
    $("#tombol_hapus").on("click", function(e) {
        // jika input .pilih yang di centang panjang nya sama dengan 0 maka
        if ($("input.pilih:checked").length === 0) {
            // tampilkan notifikasi menggunakan sweetalert yang menyatakan pesan berikut
            Swal.fire('Anda belum memilih baris data');
        }
        // jika input .pilih yang di centang panjang nya lebih atau sama dengan 1 maka
        else if ($("input.pilih:checked").length >= 1) {
            // tampilkan konfirmasi penghapusan menggunakan sweetalert
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Anda tidak akan dapat mengembalikan ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            })
            // kmeudian hasilnya, jalankan fungsi berikut, parameter result
            .then((result) => {
                // jika hasilnya di konfirmasi
                if (result.isConfirmed) {
                    // .serialize akan mengirimkan semua data pada table karena table disimpan di dalam form 
                    // sebenarnya aku mengirim beberapa value input name="pengeluaran_ids" yang di centang
                    // jquery lakukan ajax tipe kirim, ke url /pengeluarn/destroy, panggil #form_pengeluaran, kirimkan value input
                    $.post('/pengeluaran/destroy', $('#form_pengeluaran').serialize())
                        // 
                        .done(function(resp) {
                            // notifkasi
                            Swal.fire(
                                'Dihapus!',
                                'Berhasil menghapus pengeluaran yang dipilih.',
                                'success'
                            );
                            // reload ajax table
                            table.ajax.reload();
                        });
                };
            });
        };
    });
</script>
@endpush
