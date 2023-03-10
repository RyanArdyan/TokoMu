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
                        {{-- produk_penyuplai_id --}}
                        <input id="edit_produk_penyuplai_id" name="produk_penyuplai_id" class="form-control" type="hidden" autocomplete="off" readonly>


                        {{-- is-invalid --}}
                        {{-- edit_nama_produk --}}
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="edit_nama_produk">Nama Produk</label>
                            <div class="col-sm-10">
                                <input id="edit_nama_produk" name="nama_produk" class="nama_produk_input input form-control" type="text"
                                placeholder="Edit Nama Produk" autocomplete="off">
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
                                placeholder="Edit Merk" autocomplete="off">
                                {{-- pesan error --}}
                                <span class="merk_error pesan_error text-danger"></span>
                            </div>
                        </div>
    
                        {{-- harga --}}
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="edit_harga">Harga</label>
                            <div class="col-sm-10">
                                {{-- pada saat key keyboard di tekan maka panggil fungsi number sambil mengirim acara nya agar user tidak memasukkan huruf ke dalam input dan hanya memperbolehkan angka --}}
                                {{-- attribute data-inputmask berfungsi mengubah 1000 menjadi Rp 1.000 --}}
                                <input id="edit_harga" name="harga" class="harga_input input form-control input_angka" type="text"
                                placeholder="Edit Harga" onkeypress="return number(event)" data-inputmask="'alias': 'decimal', 'prefix': 'Rp ', 'groupSeparator':  '.',  'removeMaskOnSubmit': true, 'autoUnMask': true, 'rightAlign': false, 'radixPoint': ','" autocomplete="off">
                                {{-- pesan error --}}
                                <span class="harga_error pesan_error text-danger"></span>
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
