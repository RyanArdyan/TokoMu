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
                            <th scope="col">Diskon</th>
                            <th scope="col">Stok</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('script')

@endpush
