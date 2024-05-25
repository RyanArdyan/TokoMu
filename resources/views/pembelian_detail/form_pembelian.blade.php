<!-- form pembelian untuk memperbarui ke table pembelian -->
<form id="form_pembelian" action="{{ route('pembelian.store') }}" method="post">
    {{-- laravel requires security from csrf attacks --}}
    {{-- laravel mewajibkan keamanan dari serangan csrf --}}
    @csrf

    {{-- input yang show --}}
    <div class="card-body">
        {{-- input hidden --}}
        {{-- produk id --}}
        {{-- <div class="form-group">
            <label for="produk_id">Produk ID</label>
            <input name="produk_id" id="produk_id" type="number" placeholer="Akan diisi jika aku sudah click tombol pilih di modal pilih produk" class="form-control" readonly>
        </div> --}}

        {{-- Keterangan --}}
        <div class="form-group">
            <label for="keterangan_pembelian">Keterangan</label>
            <input id="keterangan_pembelian" name="keterangan_pembelian" class="form-control" type="text" autocomplete="off">
        </div>

        {{-- Tanggal pembelian --}}
        <div class="form-group mb-3">
            <label for="tanggal_dan_waktu">Tanggal & Waktu</label>
            <input id="tanggal_dan_waktu" name="tanggal_dan_waktu" class="form-control" type="datetime-local">
        </div>

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

