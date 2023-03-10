{{-- memperluas parentnya yaitu layouts.app --}}
@extends('layouts.app')

{{-- dorong css lalu tangkap mengguakan $stack('css') --}}
@push('css')
<style>
    #table_penyuplai {
        width: 100% !important;
    }
</style>
@endpush

{{-- kirim value section title ke @Yield('title') --}}
@section('title', 'Pembelian')

{{-- kirim value section konten ke @yield('konten') --}}
@section('konten')
    <!-- Content Wrapper. Contains page content -->
    <div class="row mb-2">
        <div class="col-md-12 mt-2">
            {{-- termasuk ada jika modal dipanggil --}}
            {{-- panggil modal penyuplai --}}
            @includeIf('pembelian.modal_penyuplai')

            <div class="mb-3">
                {{-- jika tombol di click maka panggil fungsi pilih_penyuplai --}}
                <button id="tombol_pembelian_baru" class="btn btn-purple btn-sm mr-1">
                    <i class="mdi mdi-plus"></i> Pembelian Baru
                </button>
                @if(session('id_pembelian'))
                    <a href="{{ url('/pembelian-detail') }}" class="btn btn-info mr-1"><i class="fa fa-money-bill"></i> Transaksi Aktif</a>
                @endif
            </div>

            {{-- termasuk element table dan form --}}
            @include('pembelian.table')

            <div class="mt-2">
                {{-- Fitur hapus beberapa pembelian berdasarkan kotak centang yang di checklist --}}
                <button id="tombol_hapus" type="button" class="btn btn-danger btn-flat btn-sm">
                    <i class="mdi mdi-delete"></i>
                    Hapus
                </button>
            </div>

        </div>
    </div>
@endsection


@push('script')
<script>
// untuk mengecilkan sidebar

let table1;

let table = $("#table-pembelian").DataTable({
    processing: true,
    autoWidth: false,
    serverSide: true,
    ajax: {
        url: "{{ route('pembelian.data') }}"
    },
    language: {
            url: "/terjemahanDatatable/indonesia.json"
    },
    columns: [
        {data: 'DT_RowIndex', searchable: false, sortable: false},
        {data: 'tanggal'},
        {data: 'penyuplai'},
        {data: 'total_item'},
        {data: 'total_harga'},
        {data: 'diskon'},
        {data: 'bayar'},
        {data: 'action', searchable: false, sortable: false}
    ],
});

// table detail
table1 = $(".table-detail").DataTable({
    processing: true,
    bsort: false,
    dom: 'Brt',
    columns: [
        // nomor
        {data: 'DT_RowIndex', searchable: false, sortable: false},
        {data: 'kode_produk'},
        {data: 'nama_produk'},
        {data: 'harga_beli'},
        {data: 'jumlah'},
        {data: 'subtotal'},
    ]
});

// read data penyuplai
// panggil #table_penyuplai lalu gunakan datatable
$("#table_penyuplai").DataTable({
    // Jika penyuplai sedang dimaut maka tampilkan processing nya dulu
    processing: true,
    // server side akan menangani data yang lebih besar dari 10.000
    serverSide: true,
    // lakukan ajax, dan panggil route pnyuplai.index
    ajax: "{{ route('pembelian.penyuplai') }}",
    // buat tbody, tr dan td lalu isi datanya
    columns: [
        {
            // pengulangan nomor
            // DT_RowIindex didapatkan ari laravel datatable
            data: 'DT_RowIndex',
            name: 'DT_RowIndex',
            sortable: false
        },
        {
            data: 'nama_penyuplai',
            name: 'nama_penyuplai'
        },
        {
            data: 'telepon_penyuplai',
            name: 'telepon_penyuplai'
        },
        {
            data: 'alamat_penyuplai',
            name: 'alamat_penyuplai'
        },
        {
            data: 'action',
            name: 'action',
            sortable: false,
            searchable: false
        }
    ],
    language: {
        url: "/terjemahan_datatable/indonesia.json"
    }
});

function pilih_penyuplai() {
    $("#modal_penyuplai").modal("show");
};

// Jika #tombol_pembelian_baru di click maka jalankan fungsi berikut
$("#tombol_pembelian_baru").on("click", function() {
    // apnggil #modal_penyuplai lalu modal nya di munculkan
    $("#modal_penyuplai").modal("show");
})


// jika modal tambah dikirim
$("#form_tambah").on("submit", function(e) {
    e.preventDefault();
    $.ajax({
        url: "{{ route('pembelian.store') }}",
        type: "POST",
        data: new FormData(this),
        processData: false,
        contentType: false,
        beforeSend: function() {
            $(".input").removeClass("is-invalid");
            $(".pesan_error").text("");
        }
    })
        .done(function(resp) {
            if (resp.status === 0) {
                // lakukan pengulangan
                // key berisi semua nilai name.
                // value berisi array yang menyimpan semua pesan error
                $.each(resp.errors, function(key, value) {
                    $(`.${key}_input`).addClass("is-invalid");
                    $(`.${key}_error`).text(value[0]);
                });
            } else if (resp.status === 200) {
                // // reset formulir
                $("#form_tambah")[0].reset();
                // nama pembelian di focuskan
                $("#deskripsi").focus();
                // muat ulang table ajax
                table.ajax.reload();
                // notifikasi
                toastr.success(`${resp.pesan}.`);
            };
        });
});

// detail pembelian
function showDetail(url) {
    $("#modal_detail").modal("show");
    table1.ajax.url(url);
    table1.ajax.reload();
};

function deleteData(url) {
    if (confirm('Yakin Ingin Menghapus Data Terpilih?')) {
        $.post(url, {
            '_token': $('[name=csrf-token]').attr('content'),
            '_method': 'delete'
        })
            .done((response) => {
                table.ajax.reload();
            })
            .fail((errors) => {
                alert('Tidak Dapat Menghapus Data');
                return;
            })
    }
}


// Delete
$("#tombol_hapus").on("click", function(e) {
    if ($("input:checked").length > 0) {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Anda tidak akan dapat mengembalikan ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                // alasan menggunakan syntax ini adalah karena input name berisi id[]
                // .serialize akan mengirimkan semua data pada table karena table disimpan di dalam form
                $.post('/pembelian/hapus-terpilih', $('.form-pembelian').serialize())
                    .done(function(resp) {
                        // notifkasi
                        Swal.fire(
                            'Dihapus!',
                            'Berhasil menghapus pembelian yang dipilih.',
                            'success'
                        );
                        // reload ajax table
                        table.ajax.reload();
                    });
            };
        });
    } else {
        Swal.fire('Anda belum memilih baris data');
    };
});
</script>
@endpush