{{-- memperluas parentnya yaitu layouts.app --}}
@extends('layouts.app')

{{-- dorong css lalu tangkap menggunakan $stack('css') --}}
@push('css')
@endpush

{{-- kirim value section title ke @Yield('title') --}}
@section('title', 'Produk')

{{-- kirim value section konten ke @yield('konten') --}}
@section('konten')
    <div class="row mb-2">
        <div class="col-sm-12 mt-2">
            {{-- agar tablenya responsive --}}
                <table class="table table-striped table-sm">
                    <thead class="bg-primary">
                        <tr>
                            <th scope="col" width="5%">No</th>
                            <th scope="col">Nama Produk</th>
                            <th scope="col">Kategori</th>
                            <th scope="col">Merk</th>
                            <th scope="col">Harga Jual</th>
                            <th scope="col" width="5%">Diskon</th>
                            <th scope="col">Stok</th>
                            <th scope="col" width="5%">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('script')


<script>
        // Ini berarti jika form nya dikirim maka hapus input mask nya, contoh Rp 1.000 akan menjadi 1000
        $(".input_angka").inputmask();

        // read data produk
        // berisi panggil element table, gunakan datatable
        let table = $("table").DataTable({
            // tampilkan processing, sebelum datanya di muat
            processing: true,
            // jika produk sudah lebih dari 10.000 maka loading nya tidak akan lama karena server side nya true
            serverSide: true,
            // lakukan ajax, panggil route produk.index
            ajax: "{{ route('produk.index') }}",
            // jika selesai dan berhasil maka buat element tbody, tr, td dan isi valuenya
            columns: [
                {
                    // lakukan pengulangan terhadap nomor
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    // sortable: false, berarti aku menghilangkan icon anak panah agar aku tidak membalik data
                    sortable: false
                },
                //
                {
                    data: 'nama_produk',
                    name: 'nama_produk'
                },
                {
                    data: 'nama_kategori',
                    // ini berarti relasi, produk berelasi dengan kategori
                    name: 'nama_kategori.nama_kategori'
                },
                {
                    data: 'merk',
                    name: 'merk'
                },

                {
                    data: 'harga_jual',
                    name: 'harga_jual'
                },
                {
                    data: 'diskon',
                    name: 'diskon'
                },
                {
                    data: 'stok',
                    name: 'stok'
                },
                // tombol edit
                {
                    data: 'action',
                    name: 'action',
                    sortable: false,
                    // aku mematikan pencarian column yang  berisi tombol edit, jadi ketika aku mencari edit maka data kosong
                    searchable: false
                }
            ],
            // datatable nya akan menggunakan bahasa indonesia
            language: {
                url: "/terjemahan_datatable/indonesia.json"
            }
        });

        // hanya izinkan user memasukkan angka di input yang telah ditentukan
        function number(event) {
            let charCode = (event.which) ? event.which : event.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            } else {
                return true;
            };
        };

        // jika document di click yang class nya adalah class tombol_keranjang maka jalankan fungsi berikut
        $(document).on("click", ".tombol_keranjang", function () {
            // panggil tombol keranjang yg di click lalu panggil value attribute data-produk-id nya
            let produk_id = $(this).data('produk-id');
            let user_id = $(this).data('user-id');
            // lakukan ajax untuk menyimpan data ke table keranjang
            //  lakukan ajax
            $.ajax({
                // panggil route keranjang.store
                url: `{{ route('keranjang.store') }}`,
                // panggil route type POST
                type: 'POST',
                // data harus mengirimkan object
                // new FormData(this) secara otomatis membuat object
                // karena kita mengirimkan objek maka kita tidak butuh cache, processData dll
                data: {
                    // kunci produk_id berisi value variable produk_id
                    produk_id: produk_id,
                    // kunci user_id_id berisi value variable produk_id
                    user_id: user_id,
                },
                // laravel butuh csrf
                headers: {
                    // panggil tag meta, name nya csrf-token, ambil value attribute content
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapannya
            .done((response) => {
                // tampilkan notifikasi menggunakan package sweetalert
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Berhasil memasukkan ke keranjang',
                })
                // kemudian hasilnya maka jalankan fungsi berikut dan ambil hasil nya
                .then((result) => {
                    // jika aku click oke pada pop up sweetalert maka
                    // jika hasilnya dikonfirmasi maka
                    if (result.isConfirmed) {
                        // // pindahkan ke route login.index
                        // // jendela.lokasi.href
                        // location.href = `{{ route('login.index') }}`;
                    };
                });
            });
        });
    </script>
@endpush
