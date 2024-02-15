<div class="modal fade" id="modal_edit" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="container">
                <form id="form_edit" class="form-horizontal">
                    {{-- untuk keamanan --}}
                    @csrf
                    {{-- memaksa memangil route tipe PUT --}}
                    @method('PUT')
                    <div class="modal-header">
                        <h4 class="modal-title" id="staticBackdropLabel">Edit Data</h4>
                        <button type="button" class="tutup close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- produk_id --}}
                        <input id="edit_produk_id" name="produk_id" class="form-control" type="hidden"
                            placeholder="ID Produk" required autocomplete="off" readonly>


                        {{-- is-invalid --}}
                        {{-- edit_nama_produk --}}
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="edit_nama_produk">Nama Produk</label>
                            <div class="col-sm-10">
                                <input id="edit_nama_produk" name="nama_produk" class="nama_produk_input input form-control" type="text"
                                placeholder="Masukkan nama produk" autocomplete="off">
                                {{-- pesan error --}}
                                <span class="nama_produk_error pesan_error text-danger"></span>
                            </div>
                        </div>
                        {{-- Kategori --}}
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="edit_kategori_id">Kategori</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="kategori_id" id="edit_kategori_id"></select>
                            </div>
                        </div>
                        {{-- penyuplai --}}
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="edit_penyuplai_id">Penyuplai</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="penyuplai_id" id="edit_penyuplai_id"></select>
                            </div>
                        </div>
                        {{-- merk --}}
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="edit_merk">Merk</label>
                            <div class="col-sm-10">
                                <input id="edit_merk" name="merk" class="merk_input input form-control" type="text"
                                placeholder="Masukkan merk" autocomplete="off">
                                {{-- pesan error --}}
                                <span class="merk_error pesan_error text-danger"></span>
                            </div>
                        </div>
    
                        {{-- harga_beli --}}
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="edit_harga_beli">Harga Beli</label>
                            <div class="col-sm-10">
                                <input id="edit_harga_beli" name="harga_beli" class="harga_beli_input input form-control input_angka" type="text"
                                placeholder="Masukkan harga beli" onkeypress="return number(event)" data-inputmask="'alias': 'decimal', 'prefix': 'Rp ', 'groupSeparator':  '.',  'removeMaskOnSubmit': true, 'autoUnMask': true, 'rightAlign': false, 'radixPoint': ','" autocomplete="off">
                                {{-- pesan error --}}
                                <span class="harga_beli_error pesan_error text-danger"></span>
                            </div>
                        </div>
    
                        {{-- diskon --}}
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="edit_diskon">Diskon</label>
                            <div class="col-sm-10">
                                <input id="edit_diskon" name="diskon" class="diskon_input input form-control input_angka" type="text"
                                placeholder="Masukkan diskon (Maksimal 100%)"    data-inputmask="'alias': 'decimal', 'suffix': '%', 'groupSeparator':  ',',  'removeMaskOnSubmit': true, 'autoUnMask': true, 'rightAlign': false, 'radixPoint': ','" autocomplete="off" onkeypress="return number(event)">
                                {{-- pesan error --}}
                                <span class="diskon_error pesan_error text-danger"></span>
                            </div>
                        </div>
    
                        {{-- harga_jual --}}
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="edit_harga_jual">Harga Jual</label>
                            <div class="col-sm-10">
                                <input id="edit_harga_jual" name="harga_jual" class="harga_jual_input input form-control input_angka" type="text"
                                placeholder="Masukkan harga jual"
                                data-inputmask="'alias': 'decimal', 'prefix': 'Rp ', 'groupSeparator':  '.',  'removeMaskOnSubmit': true, 'autoUnMask': true, 'rightAlign': false, 'radixPoint': ','" autocomplete="off" autocomplete="off" onkeypress="return number(event)">
                                {{-- pesan error --}}
                                <span class="harga_jual_error pesan_error text-danger"></span>
                            </div>
                        </div>
    
                        {{-- stok --}}
                        <div class="form-group row">
                            <label class="edit_stok col-sm-2 col-form-label" for="stok">Stok</label>
                            <div class="col-sm-10">
                                <input type="text" name="stok"
                                    class="stok_input input form-control input_angka" id="edit_stok"
                                    placeholder="Masukkan stok"
                                    data-inputmask="'alias': 'decimal', 'prefix': '', 'groupSeparator':  ',',  'removeMaskOnSubmit': true, 'autoUnMask': true, 'rightAlign': false, 'radixPoint': ','" autocomplete="off" onkeypress="return number(event)">
                                <span class="stok_error pesan_error text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="tutup btn btn-default" data-dismiss="modal">
                            <i class="mdi mdi-close"></i>
                            Tutup
                        </button>
                        <button id="tombol_perbarui" type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i>
                            Perbarui
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
