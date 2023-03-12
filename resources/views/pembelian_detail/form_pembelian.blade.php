<!-- form pembelian untuk memperbarui ke table pembelian -->
<form id="form_pembelian" action="{{ route('pembelian.store') }}" method="post">
    @csrf
    {{-- input yang hidden --}}
    <label for="">pembelian_id: </label>
    <input type="text" name="pembelian_id" value="{{ $pembelian_id }}" readonly><br>

    {{-- total harga --}}
    <label for="total_harga">Total Harga: </label>
    <input type="text" name="total_harga" id="total_harga" placeholder="Total Harga" readonly><br>

    {{-- total barang --}}
    <label for="total_barang">Total Barang</label>
    <input type="text" name="total_barang" id="total_barang" placeholder="total barang" readonly><br>

    <label for="bayar">Bayar</label>
    {{-- bayar --}}
    <input type="text" name="bayar" id="bayar" placeholder="bayar" readonly>
    {{-- akhir input yang hidden --}}

    {{-- input yang show --}}
    <div class="card-body">
        <div class="form-group">
            {{-- total harga --}}
            <label for="total_harga">Total Harga</label>
            <input id="total_rp" name="total_rp" type="text" class="form-control"
                readonly>
        </div>
        {{-- bayar --}}
        <div class="form-group">
            <label for="bayar">Bayar</label>
            <input id="bayar_rp" name="bayar_rp" type="text" class="form-control"
                readonly>
        </div>
    </div>
    <!-- /.card-body -->

    <div class="card-footer">
        <button id="simpan_transaksi" type="submit" class="btn btn-primary"><i
                class="fa fa-save"></i> Simpan Transaksi</button>
    </div>
</form>
{{-- akhir form pembelian untuk memperbarui ke table pembelian --}}