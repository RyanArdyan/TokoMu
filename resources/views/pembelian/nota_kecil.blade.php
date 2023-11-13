<html>
<head>
    <title>Faktur Pembelian</title>
    <style>
        #tabel {
            font-size: 15px;
            border-collapse: collapse;
        }

        #tabel td {
            padding-left: 5px;
            border: 1px solid black;
        }
    </style>
</head>

<body style='font-family:tahoma; font-size:8pt;'>
    <center>
        <table style='width:600px; font-size:16pt; font-family:calibri; border-collapse: collapse;' border='0'>
            <td width='70%' align='CENTER' vertical-align:top'><span style='color:black;'>
                    <b>{{ $nama_perusahaan }}</b></br>{{ $alamat_perusahaan }}</span></br>
                <span style='font-size:12pt'>{{ now() }}  </span></br>
            </td>
        </table>
        <style>
            hr {
                display: block;
                margin-top: 0.5em;
                margin-bottom: 0.5em;
                margin-left: auto;
                margin-right: auto;
                border-style: inset;
                border-width: 1px;
            } 
        </style>
        <table cellspacing='0' cellpadding='0' style='width:600px; font-size:12pt; font-family:calibri;  border-collapse: collapse;' border='0'>

            <tr align='center'>
                <td width='70%'>Produk</td>
                <td width='13%'>Harga</td>
                <td width='4%'>Jumlah</td>
                <td width='13%'>Subtotal</td>
            <tr>
                <td colspan='5'>
                    <hr>
                </td>
            </tr>
            </tr>
            {{-- looping data-data table pembelian_detail --}}
            {{-- @untuk_setiap($detail_pembelian->pembelian_detail sebagai $pembelian_detail --}}
            @foreach($semua_pembelian_detail as $pembelian_detail)
            <tr>
                {{-- tulis kode php --}}
                @php
                    // berisi value $pembelian->detail, column nama_produk
                    $nama_produk = $pembelian_detail->produk->nama_produk;
                    // Memisahkan kata dalam kalimat menjadi array
                    $kata = explode(' ', $nama_produk);
                    // Mengambil 4 kata pertama dari array
                    $duaKata = array_slice($kata, 0, 4);
                    // anggaplah berisi "Framework laravel"
                    $hasil = implode(' ', $duaKata);
                @endphp
                {{-- cetak value $hasil --}}
                <td style='vertical-align:top'>{{ $hasil }}.</td>
                {{-- cetak value $pembelian_detail, column harga_jual lalu panggil helpers rupiah_bentuk agar ada format rupiah --}}
                <td style='vertical-align:top; text-align:right; padding-right:10px'>{{ rupiah_bentuk($pembelian_detail->produk->harga_jual) }}</td>
                {{-- cetak value $pembelian_detail, column jumlah lalu panggil fungsi helpers angka_bentuk agar ketika sudah 1000 maka menjadi 1.000 --}}
                <td style='vertical-align:top; text-align:right; padding-right:10px'>{{ angka_bentuk($pembelian_detail->jumlah) }}</td>
                <td style='text-align:right; vertical-align:top'>{{ rupiah_bentuk($pembelian_detail->subtotal) }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan='5'>
                    <hr>
                </td>
            </tr>
            <tr>
                <td colspan='4'>
                    <div style='color:black; display:inline-block'>Total Barang : </div>
                    <div style='color:black; display:inline-block'>{{ $detail_pembelian->total_barang }}</div>
                </td>
            </tr>
            <tr>
                <td colspan='4'>
                    <div style='color:black; display:inline-block'>Total Harga : </div>
                    <div style='color:black; display:inline-block'>{{ rupiah_bentuk($detail_pembelian->total_harga) }}</div>
                </td>
            </tr>
        </table>
        <table style='width:350; font-size:12pt;' cellspacing='2'>
            <tr></br>
                <td align='center'>****** TERIMAKASIH ******</br></td>
            </tr>
        </table>
    </center>
</body>

</html>


