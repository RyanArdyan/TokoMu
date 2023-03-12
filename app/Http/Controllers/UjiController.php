<?php
class kambing {
/**
	 * Simpan detail pembelian_detail
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	// $request berisi semua value input dari #form_produk_penyuplai di file form_produk_penyuplai.blade
	public function store(Request $request)
	{
        // copas
        // return response()->json($request->all());

		// ambil detail_produk_penyuplai berdasarkan produk_penyuplai_id
		// berisi ProdukPenyuplai dimana value column produk_penyuplai_id sama dengan value input name="produk_penyuplai_id", pertama
        $detail_produk_penyuplai = ProdukPenyuplai::where('produk_penyuplai_id', $request->produk_penyuplai_id)->first();

        // jika tidak ada detail_produk_penyuplai
        if (!$detail_produk_penyuplai) {
			// kembalikkan tanggapan berupa json, kirimkan 2 data
            return response()->json([
				'pesan' => 'Data Gagal Disimpan'
			], 400);
        };

        // Simpan Data Ke table pembelian_detail
		// PembelianDetail buat
		PembelianDetail::create([
			// column pembelian_id diisi input name="pembelian_id"
			'pembelian_id' => $request->pembelian_id,
			'produk_penyuplai_id' => $detail_produk_penyuplai->produk_penyuplai_id,
			'harga' => $detail_produk_penyuplai->harga,
			// bawaan jumlah pasti nya adalah 1
			'jumlah' => 1,
			// bawaan subtotal adalah sama dengan column harga
			'subtotal' => $detail_produk_penyuplai->harga
		]);

		// kembalikkan tanggapan berupa json lalu kirimkan 2 data
        return response()->json('Data berhasil disimpan', 200);
	}
}
?>