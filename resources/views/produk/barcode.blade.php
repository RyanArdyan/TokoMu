<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Cetak Barcode</title>
</head>

<style>
	.text-center {
		text-align: center;
	};
</style>
<body>
	<table width="100%">
		<tr>
            {{-- lakukan pengulangan terhadap beberapa_produk --}}
            {{-- untuk setiap beberapa_produk sebagai produk --}}
			@foreach($beberapa_produk as $produk)
				<td class="text-center" style="border: 1px solid #333">
                    {{-- cetak setiap nama_produk --}}
                    {{-- panggil helpers rupiah_bentuk lalu kirim harga_jual --}}
					<p>{{ $produk->nama_produk }} - {{ rupiah_bentuk($produk->harga_jual) }}</p>
                    {{-- aku menggunakan package milon/barcode --}}
					<img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($produk->kode_produk, 'C39') }}" width="150" height="60">
					<br>
					<div style="margin-top: 5px; margin-bottom: 3px" class="kode_produk">{{ $produk->kode_produk }}</div>
				</td>
                {{-- ++ berarti angka terus bertambah --}}
                {{-- 3 dibagi 3, sisanya 0? true, maka tutup tr dan buka tr --}}
                {{-- 4 dibagi 3, sisanya 0? false maka jangan tutup tr --}}
				@if ($no++ % 3 == 0)
                    </tr>

                    <tr>
				@endif
			@endforeach
	</table>
</body>
</html>