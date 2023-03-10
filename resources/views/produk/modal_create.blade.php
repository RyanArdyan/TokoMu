<div class="modal fade" id="modal_tambah" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="container">
                {{-- aku butuh attribute enctype agar aku bisa mengirim data input type file --}}
                <form id="form_tambah" class="form-horizontal">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title" id="staticBackdropLabel">Tambah Data</h4>
                        <button type="button" class="tutup close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- is-invalid --}}
                        {{-- nama_produk --}}
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="nama_produk">Nama Produk</label>
                            <div class="col-sm-10">
                                <input id="nama_produk" name="nama_produk" class="nama_produk_input input form-control" type="text"
                                placeholder="Masukkan nama produk" autocomplete="off">
                                {{-- pesan error --}}
                                <span class="nama_produk_error pesan_error text-danger"></span>
                            </div>
                        </div>
                        {{-- Kategori --}}
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="kategori_id">Kategori</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="kategori_id" id="kategori_id"></select>
                            </div>
                        </div>
                        {{-- penyuplai --}}
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="penyuplai_id">Penyuplai</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="penyuplai_id" id="penyuplai_id"></select>
                            </div>
                        </div>
                        {{-- merk --}}
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="merk">Merk</label>
                            <div class="col-sm-10">
                                <input id="merk" name="merk" class="merk_input input form-control" type="text"
                                placeholder="Masukkan merk" autocomplete="off">
                                {{-- pesan error --}}
                                <span class="merk_error pesan_error text-danger"></span>
                            </div>
                        </div>
    
                        {{-- harga_beli --}}
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="harga_beli">Harga Beli</label>
                            <div class="col-sm-10">
                                <input id="harga_beli" name="harga_beli" class="harga_beli_input input form-control input_angka" type="text"
                                placeholder="Masukkan harga beli" onkeypress="return number(event)" data-inputmask="'alias': 'decimal', 'prefix': 'Rp ', 'groupSeparator':  '.',  'removeMaskOnSubmit': true, 'autoUnMask': true, 'rightAlign': false, 'radixPoint': ','" autocomplete="off">
                                {{-- pesan error --}}
                                <span class="harga_beli_error pesan_error text-danger"></span>
                            </div>
                        </div>
    
                        {{-- diskon --}}
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="diskon">Diskon</label>
                            <div class="col-sm-10">
                                <input id="diskon" name="diskon" class="diskon_input input form-control input_angka" type="text"
                                placeholder="Masukkan diskon (Maksimal 100%)"    data-inputmask="'alias': 'decimal', 'suffix': '%', 'groupSeparator':  ',',  'removeMaskOnSubmit': true, 'autoUnMask': true, 'rightAlign': false, 'radixPoint': ','" autocomplete="off" value="0" onkeypress="return number(event)">
                                {{-- pesan error --}}
                                <span class="diskon_error pesan_error text-danger"></span>
                            </div>
                        </div>
    
                        {{-- harga_jual --}}
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="harga_jual">Harga Jual</label>
                            <div class="col-sm-10">
                                <input id="harga_jual" name="harga_jual" class="harga_jual_input input form-control input_angka" type="text"
                                placeholder="Masukkan harga jual"
                                data-inputmask="'alias': 'decimal', 'prefix': 'Rp ', 'groupSeparator':  '.',  'removeMaskOnSubmit': true, 'autoUnMask': true, 'rightAlign': false, 'radixPoint': ','" autocomplete="off" autocomplete="off" onkeypress="return number(event)">
                                {{-- pesan error --}}
                                <span class="harga_jual_error pesan_error text-danger"></span>
                            </div>
                        </div>
    
                        {{-- stok --}}
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="stok">Stok</label>
                            <div class="col-sm-10">
                                <input type="text" name="stok"
                                    class="stok_input input form-control input_angka" id="stok"
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
                        <button id="tombolSimpan" type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>