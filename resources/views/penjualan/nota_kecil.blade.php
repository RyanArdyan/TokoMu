<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nota Kecil</title>

    <?php
        $style = '
            <style>
                /* * berarti semuah element */
                * {
                    font-family: "consolas", sans-serif;
                }
                p {
                    /* Displays an element as a block element (like <p>). It starts on a new line, and takes up the whole width */
                    display: block;
                    // margin adalah memberi jarak element dengan element yang berada diluarnya
                    margin: 3px;
                    font-size: 10pt;
                }
                table td {
                    font-size: 9pt;
                }
                .text-center {
                    /* perataan teks: tengah */
                    text-align: center;
                }
                .text-right {
                    text-align: right;
                }

                @media print {
                    @page {
                        margin: 0;
                        size: 75mm 
            ';
    ?>

    <?php 
        $style .= 
            ! empty($_COOKIE['innerHeight'])
                ? $_COOKIE['innerHeight'] .'mm; }'
                : '}';
    ?>
    <?php
        $style .= '
                    html, body {
                        width: 70mm;
                    }
                    .btn-print {
                        /* none berarti tidak ada */
                        display: none;
                    }
                }
            </style>';
    ?>

    {!! $style !!}
</head>
{{-- ketika dimuat window tolong lakukan print --}}
<body onload="window.print()">
    <button class="btn-print" style="position: absolute; right: 1rem; top: rem;" onclick="window.print()">Print</button>
    <div class="text-center">
        <h3 style="margin-bottom: 5px;">{{ strtoupper($detail_pengaturan->nama_perusahaan) }}</h3>
        {{-- str to upper berarti semua huruf akan menjadi kapital --}}
        <p>{{ strtoupper($detail_pengaturan->alamat_perusahaan) }}</p>
    </div>
    <br>
    <div>
        {{-- float: left akan memaksa element berada di kiri --}}
        <p style="float: left;">{{ date('d-m-Y') }}</p>
        {{-- cetak nama user yang login pake huruf kapital --}}
        <p style="float: right">{{ strtoupper(auth()->user()->name) }}</p>
    </div>
    {{-- kasi clear: both agar element berada di baris baru --}}
    <div class="clear-both" style="clear: both;"></div>
    <p>No: {{ tambah_nol_didepan($detail_penjualan->penjualan_id, 10) }}</p>
    <p class="text-center">===================================</p>
    
    <br>
    <table width="100%" style="border: 0;">
        {{-- pengulangan terhadap $beberapa_penjualan_detail --}}
        @foreach ($beberapa_penjualan_detail as $detail_penjualan_detail)
            <tr>
                {{-- value detail_penjualan_detail yang berelasi dengan produk, column nama_produk --}}
                <td colspan="3">{{ $detail_penjualan_detail->produk->nama_produk }}</td>
            </tr>
            <tr>
                {{-- cetak value detail_penjualan, column jumlah x --}}
                <td>{{ $detail_penjualan_detail->jumlah }} x {{ rupiah_bentuk($detail_penjualan_detail->harga_jual) }}</td>
                <td></td>
                <td class="text-right">{{ rupiah_bentuk($detail_penjualan_detail->jumlah * $detail_penjualan_detail->harga_jual) }}</td>
            </tr>
        @endforeach
    </table>
    <p class="text-center">-----------------------------------</p>

    <table width="100%" style="border: 0;">
        <tr>
            <td>Total Harga:</td>
            <td class="text-right">{{ rupiah_bentuk($detail_penjualan->total_harga) }}</td>
        </tr>
        <tr>
            <td>Total Barang:</td>
            <td class="text-right">{{ $detail_penjualan->total_barang }}</td>
        </tr>
        <tr>
            <td>Diskon:</td>
            <td class="text-right">{{ $detail_penjualan->diskon }}%</td>
        </tr>
        <tr>
            <td>Total Bayar:</td>
            <td class="text-right">{{ rupiah_bentuk($detail_penjualan->harus_bayar) }}</td>
        </tr>
        <tr>
            <td>Diterima:</td>
            <td class="text-right">{{ rupiah_bentuk($detail_penjualan->uang_diterima) }}</td>
        </tr>
        <tr>
            <td>Kembalian:</td>
            <td class="text-right">{{ rupiah_bentuk($detail_penjualan->uang_diterima - $detail_penjualan->harus_bayar) }}</td>
        </tr>
    </table>

    <p class="text-center">===================================</p>
    <p class="text-center">-- TERIMA KASIH --</p>

    <script>
        // document.body menghaslkan body
        let body = document.body;
        // docuumnet element menghasilkan html
        let html = document.documentElement;
        // Math.max mengembalikkan angka dengan value tertinggi
        let height = Math.max(
                // body.scrollHeight berfungsi Dapatkan tinggi dan lebar elemen, termasuk padding:
                // body.offsetHeight 
                body.scrollHeight, body.offsetHeight,
                html.clientHeight, html.scrollHeight, html.offsetHeight
            );

        document.cookie = "innerHeight=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "innerHeight="+ ((height + 50) * 0.264583);
    </script>
</body>
</html>