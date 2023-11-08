<!-- form penjualan untuk memperbarui ke table penjualan -->
{{-- ketika form dikirim maka panggil route tipe kirim, panggil route yang bernama penjualan.update --}}
<form id="form_penjualan">
    {{-- untuk keamanan dari serangan csrf --}}
    @csrf
    {{-- AWAL INPUT HIDDEN --}}
    {{-- total barang --}}
    <div hidden>
        <label for="total_barang">Total Barang: </label>
        <input type="number" name="total_barang" id="total_barang"
            placeholder="total Barang"><br>
    </div>

    {{-- total harga --}}
    <div hidden>
        <label for="total_harga">Total Harga: </label>
        <input type="number" name="total_harga" id="total_harga"
        placeholder="Total Harga"><br>
    </div>
    
    {{-- bayar --}}
    <div hidden>
        <label for="harus_bayar">Harus Bayar</label>
        <input type="number" name="harus_bayar" id="harus_bayar"
        placeholder="Harus Bayar"><br>
    </div>
    {{-- AKHIR INPUT YG HIDDEN --}}
    
    {{-- member_id --}}
    <div hidden>
        <label for="member_id">member_id</label>
        <input type="number" name="member_id" id="member_id">
        {{-- akhir input yang hidden --}}
    </div>

    {{-- input yang show --}}
    <div class="card-body">
        {{-- Keterangan --}}
        <div class="form-group">
            <label for="keterangan_penjualan">Keterangan</label>
            <input id="keterangan_penjualan" name="keterangan_penjualan" class="form-control" type="text" autocomplete="off">
        </div>

        {{-- Tanggal Penjualan --}}
        <div class="form-group">
            <label for="tanggal_dan_waktu">Tanggal & Waktu</label>
            <input id="tanggal_dan_waktu" name="tanggal_dan_waktu" class="form-control" type="datetime-local">
        </div>


        {{-- total harga --}}
        <div class="form-group">
            <label for="total_harga">Total Harga</label>
            <input id="total_rp" name="total_rp" type="text"
                class="form-control" readonly>
        </div>

        {{-- member --}}
        <div class="form-group">
            <label for="member">Pilih Member</label>
            <div class="input-group">
                <input id="kode_member" class="form-control form-control-navbar" type="search"
                    placeholder="Cari" readonly>
                <div class="input-group-append">
                    <button id="button_tampilkan_member" class="btn  btn-navbar"
                        type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
        

        {{-- diskon --}}
        <div class="form-group">
            <label for="diskon">Diskon</label>
            {{-- jika yang membeli bukan member maka tidak ada diskon --}}
            {{-- jika table penjualan yang berelasi dengan table member, column id_membernya kosong maka diisi dengan 0, jika ada maka kasi diskon --}}
            <input id="diskon" name="diskon" type="number" class="form-control" readonly>
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
            {{-- onkeypress="return number(event)" berarti ketika tekan tombol keybard maka panggil fungsi number lalu kirim acaranya agar huruf tidak bisa masuk ke input --}}
            {{-- aku pake package input mask agar mengubah 1000 menjadi Rp 1.000 --}}
            <input id="uang_diterima" name="uang_diterima" type="text"
                class="form-control input_angka" onkeypress="return number(event)" data-inputmask="'alias': 'decimal', 'prefix': 'Rp ', 'groupSeparator': '.',  'removeMaskOnSubmit': true, 'autoUnMask': true, 'rightAlign': false, 'radixPoint': ','" autocomplete="off">
        </div>

        {{-- Uang Kembalian Pelanggan --}}
        <div class="form-group">
            <label for="uang_kembalian_pelanggan">Uang Kembalian</label>
            <input id="uang_kembalian_pelanggan"
                name="uang_kembalian_pelanggan" type="text"
                class="form-control" value="0" readonly data-inputmask="'alias': 'decimal', 'prefix': 'Rp ', 'groupSeparator':  '.',  'removeMaskOnSubmit': true, 'autoUnMask': true, 'rightAlign': false, 'radixPoint': ','">
        </div>
    </div>
    <!-- /.card-body --> 
</form>
{{-- akhir form penjualan untuk memperbarui ke table penjualan --}}

{{-- jika tombol Simpan penjualan di click maka Update detail penjualan --}}
<button id="tombol_simpan_penjualan" type="button" class="btn btn-sm btn-primary"><i
    class="fa fa-save"></i> Simpan penjualan</button>
{{-- panggil route penjualan.index --}}
<a href="{{ route('penjualan.index') }}" id="tombol_simpan_penjualan" class="mt-2 btn btn-sm btn-danger"><i
    class="fa fa-arrow-left"></i> Kembali</a>

