<!-- general form elements -->
{{-- form yang hidden / disembunyikan --}}
<form id="form_produk_penyuplai">
    @csrf
    <div class="form-group">
        <label for="pembelian_id">pembelian_id</label>
        <input name="pembelian_id" value="{{ $pembelian_id }}" id="pembelian_id" type="text" readonly><br>
    </div>

    <div class="form-group">
        <label for="produk_penyuplai_id">produk_penyuplai_id</label>
        <input name="produk_penyuplai_id" id="produk_penyuplai_id" type="text" placeholder="Input ini akan diisi jika aku sudah click tombol pilih di modal pilih produk penyuplai" readonly size="100%">
    </div>
</form>
{{-- akhir form yang hidden / disembunyikan --}}
