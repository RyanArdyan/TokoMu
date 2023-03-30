<!-- form pembelian untuk memperbarui ke table pembelian -->
<form id="form_pembelian" action="{{ route('pembelian.store') }}" method="post">
    @csrf
    <label for="produk_penyuplai_id" hidden>produk_penyuplai_id</label>
    <input name="produk_penyuplai_id" id="produk_penyuplai_id" type="hidden" placeholder="akan diisi jika aku sudah click tombol pilih di modal pilih produk penyuplai" readonly size="65px">

    {{-- input yang hidden --}}
    <label for="pembelian_id" hidden>pembelian_id: </label>
    <input id="pembelian_id" type="hidden" name="pembelian_id" value="{{ $pembelian_id }}" readonly><br>
    {{-- akhir input yang hidden --}}

    {{-- input yang show --}}
    <div class="card-body">
        {{-- total barang --}}
        <div class="form-group">
            <label for="total_barang">Total Barang</label>
            <input id="total_barang" name="total_barang" type="text" class="form-control"
                readonly>
        </div>
        {{-- total harga --}}
        <div class="form-group">
            <label for="total_harga">Total Harga</label>
            {{-- data-inputmask adalah attribute milik package input mask --}}
            {{-- aku butuh .input_angka agar ketika aku click tombol simpan transaksi maka input mask akan dihapus --}}
            <input id="total_harga" name="total_harga" data-inputmask="'alias': 'decimal', 'prefix': 'Rp ', 'groupSeparator':  '.',  'removeMaskOnSubmit': true, 'autoUnMask': true, 'rightAlign': false, 'radixPoint': ','" type="text" class="input_angka form-control"
                readonly>
        </div>
        </div>
    </div>
    <!-- /.card-body -->   
</form>
{{-- akhir form pembelian untuk memperbarui ke table pembelian --}}