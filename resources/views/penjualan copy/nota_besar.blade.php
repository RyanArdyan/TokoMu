<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nota PDF</title>

    <style>
        table td {
            /* font-family: Arial, Helvetica, sans-serif; */
            font-size: 14px;
        }
        /* table class data */
        table.data td,
        table.data th {
            border: 1px solid #ccc;
            /* pandding akan membesarkan ruang dalam */
            padding: 5px;
        }
        table.data {
            /* secara default, border pada table memiliki 2 garis, tapi karena aku menggunakan border-collapse: collapse maka garisnya 1 */
            border-collapse: collapse;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <table width="100%">
        <tr>
            {{-- rowspan akan menghapus border sebanyak 4 --}}
            <td rowspan="4" width="60%">
                <img src="{{ public_path('storage/gambar_pengaturan/' . $detail_pengaturan->logo_perusahaan) }}" alt="{{ $detail_pengaturan->path_logo }}" width="120">
                {{-- / berarti memanggil folder public --}}
                {{-- <img src="/storage/gambar_pengaturan/{{ $detail_pengaturan->logo_perusahaan }}" alt="" width="120px"> --}}
                <br>
                {{-- cetak value $detail_pengaturan, column alamat_perusahaan --}}
                {{ $detail_pengaturan->alamat_perusahaan }}
                <br>
                <br>
            </td>
            <td>Tanggal</td>
            <td>: {{ tanggal_indonesia(date('Y-m-d')) }}</td>
        </tr>
        <tr>
            <td>Kode Member</td>
            {{-- cetak value detail_penjualan yang berelasi dengan table member, colum kode_member --}}
            <td>: {{ $detail_penjualan->member->kode_member ?? '' }}</td>
        </tr>
    </table>

    <table class="data" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Harga Satuan</th>
                <th>Jumlah</th>
                <th>Diskon</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            {{-- lakukan pengulangan terhadap value $beberapa_penjualan_detail --}}
            {{-- @untuksetiap($beberapa_penjualan_detail sebagai $penjualan_detail) --}}
            @foreach ($beberapa_penjualan_detail as $penjualan_detail)
                <tr>
                    {{-- cetak pengulangan nomor --}}
                    <td class="text-center">{{ $loop->iteration }}</td>
                    {{-- cetak value penjualan_detail yang berelasi dengan table produk, column nama_produk --}}
                    <td>{{ $penjualan_detail->produk->kode_produk }}</td>
                    {{-- cetak value penjualan_detail yang berelasi dengan table produk, column kode_produk --}}
                    <td>{{ $penjualan_detail->produk->nama_produk }}</td>
                    {{-- panggil fungsi rupiah_bentuk milik helpers lalu kirimkan value $penjualan_detail, column harga_jual --}}
                    <td class="text-right">{{ rupiah_bentuk($penjualan_detail->harga_jual) }}</td>
                    {{-- panggil fungsi angka_bentuk milih helpers lalu kirimkan value $penjualan_detail, column jumlah --}}
                    <td class="text-right">{{ angka_bentuk($penjualan_detail->jumlah) }}</td>
                    {{-- cetak value $penjualan_detail, column diskon --}}
                    <td class="text-right">{{ $penjualan_detail->diskon }}%</td>
                    {{-- cetak value $penjualan_detail, column subtotal --}}
                    <td class="text-right">{{ rupiah_bentuk($penjualan_detail->subtotal) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                {{-- colspan akan menghapus border sebanyak 6  --}}
                <td colspan="6" class="text-right"><b>Total Harga</b></td>
                {{-- panggil fungsi rupiah_bentuk lalu kirimkan value $detail_penjualan, column total_harga --}}
                <td class="text-right"><b>{{ rupiah_bentuk($detail_penjualan->total_harga) }}</b></td>
            </tr>
            <tr>
                <td colspan="6" class="text-right"><b>Diskon</b></td>
                {{-- cetak value $detail_penjualan, column diskon --}}
                <td class="text-right"><b>{{ $detail_penjualan->diskon }}%</b></td>
            </tr>
            <tr>
                <td colspan="6" class="text-right"><b>Total Bayar</b></td>
                {{-- cetak panggil fungsi rupiah_bentuk lalu kirimkan value $detail_penjualan, column harus_bayar --}}
                <td class="text-right"><b>{{ rupiah_bentuk($detail_penjualan->harus_bayar) }}</b></td>
            </tr>
            <tr>
                <td colspan="6" class="text-right"><b>Diterima</b></td>
                {{-- cetak panggil fungsi rupiah_bentuk lalu kirimkan value $detail_penjualan, column uang_diterima --}}
                <td class="text-right"><b>{{ rupiah_bentuk($detail_penjualan->uang_diterima) }}</b></td>
            </tr>
            <tr>
                <td colspan="6" class="text-right"><b>Kembali</b></td>
                {{-- cetak panggil fungsi rupaih_bentuk milik helpers lalu kirimkan value $detail_penjualan, column uang_diterima dikurangi value $detail_penjualan. column harus_bayar --}}
                <td class="text-right"><b>{{ rupiah_bentuk($detail_penjualan->uang_diterima - $detail_penjualan->harus_bayar) }}</b></td>
            </tr>
        </tfoot>
    </table>

    <table width="100%">
        <tr>
            <td><b>Terimakasih telah berbelanja dan sampai jumpa</b></td>
            <td class="text-center">
                Kasir
                <br>
                <br>
                {{ auth()->user()->name }}
            </td>
        </tr>
    </table>
</body>
</html>