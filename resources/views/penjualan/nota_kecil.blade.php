<html>

<head>
    <title>Faktur Pembayaran</title>
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
                <td width='10%'>Produk</td>
                <td width='13%'>Harga</td>
                <td width='4%'>Jumlah</td>
                <td width='7%'>Diskon</td>
                <td width='13%'>Subtotal</td>
            <tr>
                <td colspan='5'>
                    <hr>
                </td>
            </tr>
            </tr>
            {{-- looping data-data table penjualan_detail --}}
            {{-- @untuk_setiap($detail_penjualan->penjualan_detail sebagai $penjualan_detail --}}
            @foreach($semua_penjualan_detail as $penjualan_detail)
            <tr>
                {{-- tulis kode php --}}
                @php
                    // berisi value $penjualan->detail, column nama_produk
                    $nama_produk = $penjualan_detail->produk->nama_produk;
                    // Memisahkan kata dalam kalimat menjadi array
                    $kata = explode(' ', $nama_produk);
                    // Mengambil 4 kata pertama dari array
                    $duaKata = array_slice($kata, 0, 4);
                    // anggaplah berisi "Framework laravel"
                    $hasil = implode(' ', $duaKata);
                @endphp
                {{-- cetak value $hasil --}}
                <td style='vertical-align:top'>{{ $hasil }}.</td>
                {{-- cetak value $penjualan_detail, column harga_jual lalu panggil helpers rupiah_bentuk agar ada format rupiah --}}
                <td style='vertical-align:top; text-align:right; padding-right:10px'>{{ rupiah_bentuk($penjualan_detail->produk->harga_jual) }}</td>
                {{-- cetak value $penjualan_detail, column jumlah lalu panggil angka_bentuk agar ketika sudah 1000 maka menjadi 1.000 --}}
                <td style='vertical-align:top; text-align:right; padding-right:10px'>{{ angka_bentuk($penjualan_detail->jumlah) }}</td>
                <td style='vertical-align:top; text-align:right; padding-right:10px'>{{ $penjualan_detail->produk->diskon }}%</td>
                <td style='text-align:right; vertical-align:top'>{{ rupiah_bentuk($penjualan_detail->subtotal) }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan='5'>
                    <hr>
                </td>
            </tr>
            <tr>
                <td colspan='4'>
                    <div style='text-align:right; color:black'>Total Barang : </div>
                </td>
                <td style='text-align:right; font-size:16pt; color:black'>{{ $detail_penjualan->total_barang }}</td>
            </tr>
            <tr>
                <td colspan='4'>
                    <div style='text-align:right; color:black'>Total Harga : </div>
                </td>
                <td style='text-align:right; font-size:16pt; color:black'>{{ rupiah_bentuk($detail_penjualan->total_harga) }}</td>
            </tr>
            <tr>
                <td colspan='4'>
                    <div style='text-align:right; color:black'>Diskon</div>
                </td>
                {{-- jika $detail_penjualan, column diskon sama dengan 0 maka cetak "Bukan Member" --}}
                @if ($detail_penjualan->diskon === 0)
                <td style='text-align:right; font-size:16pt; color:black'>Bukan Member</td>
                {{-- lain jika $detail_penjualan, column diskon sama dengan 5 maka cetak value $detail_penjualan, column diskon --}}
                @elseif ($detail_penjualan->diskon === 5)
                <td style='text-align:right; font-size:16pt; color:black'>{{ $detail_penjualan->diskon }}%</td>
                @endif
            </tr>
            <tr>
                <td colspan='4'>
                    <div style='text-align:right; color:black'>Harus Membayar : </div>
                </td>
                <td style='text-align:right; font-size:16pt; color:black'>{{ rupiah_bentuk($detail_penjualan->harus_bayar) }}</td>
            </tr>
            <tr>
                <td colspan='4'>
                    <div style='text-align:right; color:black'>Anda Membayar : </div>
                </td>
                <td style='text-align:right; font-size:16pt; color:black'>{{ rupiah_bentuk($detail_penjualan->uang_diterima) }}</td>
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


