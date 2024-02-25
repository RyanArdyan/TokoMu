<p>Tanggal: {{ $tanggal_tutup }}</p>
<p>Jam Tutup: {{ $jam_tutup }}</p>
<p>Nama: {{ $nama_penjual }}</p>

<table style="border: 1px solid black;">
    <thead>
        <tr>
            <th style="font-weight: bold; padding: 5px; border: 1px solid black;">Nama Produk</th>
            <th style="font-weight: bold; padding: 5px; border: 1px solid black;">Harga Satuan</th>
            <th style="font-weight: bold; padding: 5px; border: 1px solid black;">Jumlah Barang</th>
            <th style="font-weight: bold; padding: 5px; border: 1px solid black;">Subtotal</th>
        </tr>
    </thead>
    <tbody>
    {{-- lakukan pengulangan terhadap value variable beberapa_penjualan_detail --}}
    @foreach($beberapa_penjualan_detail as $penjualan_detail)
        <tr>
            <td style="border: 1px solid black;">{{ $penjualan_detail->nama_produk }}</td>
            <td style="border: 1px solid black;">{{ $penjualan_detail->harga_jual }}</td>
            <td style="border: 1px solid black;">{{ $penjualan_detail->jumlah }}</td>
            <td style="border: 1px solid black;">{{ $penjualan_detail->subtotal }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
