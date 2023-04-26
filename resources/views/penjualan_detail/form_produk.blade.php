{{-- form untuk pilih produk --}}
<form id="form_produk">
    @csrf
    <div class="form-group">
        <label for="penjualan_id">penjualan_id</label>
        <input name="penjualan_id" value="{{ $penjualan_id }}" id="penjualan_id"
            type="text">
    </div>
    {{-- Kode produk --}}
    <div class="form-group">
        <label for="kode_produk">Kode Produk</label>
        <input name="kode_produk" id="kode_produk"
            type="text">
    </div>

    <div class="form-group">
        <label for="produk_id">produk_id</label>
        <input name="produk_id" id="produk_id" type="text">
    </div>
</form>
{{-- akhir form untuk pilih produk --}}