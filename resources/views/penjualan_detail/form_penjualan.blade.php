<!-- form penjualan untuk memperbarui ke table penjualan -->
<form id="form_penjualan" action="{{ route('penjualan.update') }}" method="POST">
    {{-- untuk keamanan dari serangan csrf --}}
    @csrf
    {{-- input yang hidden --}}
    <label for="penjualan_id">penjualan_id: </label>
    <input type="text" name="penjualan_id" value="{{ $penjualan_id }}"
        readonly><br>

    {{-- total barang --}}
    <label for="total_barang">Total Barang: </label>
    <input type="number" name="total_barang" id="total_barang"
        placeholder="total Barang"><br>

    {{-- total harga --}}
    <label for="total_harga" text>Total Harga: </label>
    <input type="number" name="total_harga" id="total_harga"
        placeholder="Total Harga"><br>
    {{-- akhir input yang hidden --}}

    {{-- bayar --}}
    <label for="harus_bayar">Harus Bayar</label>
    <input type="number" name="harus_bayar" id="harus_bayar"
        placeholder="Harus Bayar"><br>

    {{-- member_id --}}
    <label for="member_id">member_id</label>
    <input type="number" name="member_id" id="member_id"
        value="{{ $detail_member->member_id }}">
    {{-- akhir input yang hidden --}}

    {{-- input yang show --}}
    <div class="card-body">
        <div class="form-group">
            {{-- total harga --}}
            <label for="total_harga">Total Harga</label>
            <input id="total_rp" name="total_rp" type="text"
                class="form-control" readonly>
        </div>

        {{-- member --}}
        <label for="member">Pilih Member</label>
        <div class="input-group">
            <input id="kode_member"
                value="{{ $detail_member->kode_member }}"
                class="form-control form-control-navbar" type="search"
                placeholder="Cari" readonly>
            <div class="input-group-append">
                <button id="button_tampilkan_member" class="btn btn-navbar"
                    type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>

        {{-- diskon --}}
        <div class="form-group">
            <label for="diskon">Diskon</label>
            {{-- jika yang membeli bukan member maka tidak ada diskon --}}
            {{-- jika table penjualan yang berelasi dengan table member, column id_membernya kosong maka diisi dengan 0, jika ada maka kasi diskon --}}
            <input id="diskon" name="diskon" type="number"
                class="form-control"
                value="{{ !empty($detail_member->member_id) ? $diskon : 0 }}"
                readonly>
        </div>
        {{-- bayar --}}
        <div class="form-group">
            <label for="bayar">Harus Bayar</label>
            <input id="bayar_rp" name="bayar" type="text"
                class="form-control" readonly>
        </div>

        {{-- Uang Diterima --}}
        <div class="form-group">
            <label for="uang_diterima">Uang Diterima</label>
            {{-- value="" mencetak value detail_penjualan, column uang_diterima, jika tidak ada maka cetak 0 --}}
            <input id="uang_diterima" name="uang_diterima" type="text"
                class="form-control input_angka" onkeypress="return number(event)"
                value="{{ $detail_penjualan->uang_diterima ?? 0 }}" data-inputmask="'alias': 'decimal', 'prefix': 'Rp ', 'groupSeparator':  '.',  'removeMaskOnSubmit': true, 'autoUnMask': true, 'rightAlign': false, 'radixPoint': ','">
        </div>

        {{-- Uang Kembalian Pelanggan --}}
        <div class="form-group">
            <label for="uang_kembalian_pelanggan">Uang Kembalian</label>
            <input id="uang_kembalian_pelanggan"
                name="uang_kembalian_pelanggan" type="text"
                class="form-control" value="0" readonly>
        </div>
    </div>
    <!-- /.card-body --> 
</form>
{{-- akhir form penjualan untuk memperbarui ke table penjualan --}}