<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Pendapatan</title>

    <style>
        /* semua element */
        * {
            margin: 0;
            padding: 0;
        }

        /* semua .my-5 */
        .my-5 {
            margin-top: 30px;
            margin-bottom: 20px;
        }

        .mb-30 {
            margin-bottom: 30px;
        }

        .container {
            margin-left: 30px;
            margin-right: 30px;
        }

        .text-center {
            text-align: center;
        }

        table {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        /* table td dan table th */
        table td, table th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .tebal-normal {
            font-weight: normal;
        }
    </style>

</head>
<body>
    <div class="container">
    <h1 class="text-center my-5">Laporan Tutup Penjualan</h1>
        {{-- cetak value variable $tanggal_hari_ini yang dikirim controller --}}
    <h3 class="tebal-normal">Tanggal: {{ $tanggal_hari_ini }}</h3>
    <h3 class="tebal-normal">Jam Tutup: {{ $waktu_tutup }}</h3>
    <h3 class="tebal-normal mb-5">Nama: {{ $name }}</h3>

    <br>

    <div class="mt-5">
        <table class="mt-5">
            <thead>
                <th>Nama Produk</th>
                <th>Harga Satuan</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </thead>

            <tbody>
                {{-- looping baris --}}
                @foreach($beberapa_penjualan_detail as $penjualan_detail)
                    <tr>
                        {{-- cetak value detail penjualan_detail, column nama_produk, bisa melakukan ini karena table penjualan_detail sudha di join atau gabung dengan table produk  --}}
                        <td>{{ $penjualan_detail->nama_produk }}</td>
                        <td>{{ rupiah_bentuk($penjualan_detail->harga_jual) }}</td>
                        <td>{{ $penjualan_detail->jumlah }}</td>
                        <td>{{ rupiah_bentuk($penjualan_detail->subtotal) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2"></th>
                    <th>{{ $total_produk }}</th>
                    <th>{{ rupiah_bentuk($total_harga) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>

</body>
</html>
