{{-- memperluas parent nya yaitu layouts.app --}}
@extends('layouts.app')

{{-- kirimkan value section title ke parent nya yaitu layouts.app --}}
@section('title', 'Selesai Menjual')

@section('konten')
<div class="row mb-2">
    <div class="col-sm-12 mt-2">
        <div class="alert alert-success">Data Penjualan Telah Selesai</div>

        {{-- jika value $detail_pengaturan, column tipe_nota_perusahaan sama dengan "1" --}}
        @if ($detail_pengaturan->tipe_nota_perusahaan === "1")
            <button class="btn btn-sm btn-success" onclick="nota_kecil('{{ route('penjualan.nota_kecil') }}', 'Nota Kecil')">Cetak Nota Kecil</button>
        @elseif($detail_pengaturan->tipe_nota_perusahaan === "2")
            <button class="btn btn-sm btn-success" onclick="nota_besar('{{ route('penjualan.nota_besar') }}', 'Nota PDF')">Cetak Nota Besar</button>
        @endif

        <a href="{{ route('penjualan_detail.create') }}" class="btn btn-primary btn-sm">Penjualan Baru</a>
    </div>
</div>
@endsection

{{-- dorong script --}}
@push('script')
<script>
    // tambahkan untuk delete cookie innerHeight terlebih dahulu
    // Menghapus cookie sangat sederhana. Cukup atur parameter kedaluwarsa ke tanggal yang telah berlalu:
    document.cookie = "innerHeight=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    
    // untuk mencetak nota kecil
    function nota_kecil(url, title) {
        // 625 adalah lebar, 900 adalah tinggi
        popup_center(url, title, 625, 500);
    };

    // untuk mencetak nota besar
    function nota_besar(url, title) {
        popup_center(url, title, 900, 675);
    };

    function popup_center(url, title, w, h) {
        // semua kode dibawah, ada penjelasannya di w3schools
        // window.screenLeft berfungsi mengembalikkan koordinat x(horizontal) jendela(window)
        // dualScreenLeft akan berisi 0
        // jika window.screenleft tidak sama dengan undefined maka dia akan berisi 0, kalau undefined maka dia akan berisi 0 
        const dualScreenLeft = window.screenLeft !==  undefined ? window.screenLeft : window.screenX;
        //  window.screentop berfungsi mengembalikkan kordinat y(vertikal) jendela(window):
        // dualScreenTop akan berisi 0
        const dualScreenTop  = window.screenTop  !==  undefined ? window.screenTop  : window.screenY;

        // bawaan window.innerWidth adalah 668
        // jika window.innerWidth ada maka pake 668, kalau tidak ada 
        // Properti clientWidthmenampilkan lebar elemen yang dapat dilihat dalam piksel, termasuk padding, tetapi bukan batas, bilah gulir, atau margin.
        const width  = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        // availWidth berisi 1366
        // Properti availWidthmengembalikan lebar layar pengguna.
        // Properti availWidthmengembalikan lebar dalam piksel.
        const systemZoom = width / window.screen.availWidth;
        const left       = (width - w) / 2 / systemZoom + dualScreenLeft
        const top        = (height - h) / 2 / systemZoom + dualScreenTop
        // Metode open()membuka jendela browser baru, atau tab baru, tergantung pada pengaturan browser Anda dan nilai parameter.
        const newWindow  = window.open(url, title, 
        `
            scrollbars=yes,
            width  = ${w / systemZoom}, 
            height = ${h / systemZoom}, 
            top    = ${top}, 
            left   = ${left}
        `
        );

        // Buka jendela baru dan setel fokus ke sana:
        if (window.focus) {
            newWindow.focus();
        };
    };
</script>
@endpush