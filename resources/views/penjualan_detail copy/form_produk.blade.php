{{-- form untuk pilih produk --}}
<form id="form_produk" hidden>
    @csrf
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