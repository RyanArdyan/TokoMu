<div class="modal fade" id="modal_edit" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
       <div class="modal-content">
            <div class="container">
                <form id="form_edit">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h4 id="staticBackdropLabel" class="modal-title">Edit Data</h4>
                        <button type="button" class="tutup close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- is-invalid --}}
                        {{-- penyuplai_id --}}
                        <input id="edit_penyuplai_id" name="penyuplai_id" class="form-control" type="hidden"
                        placeholder="ID" autocomplete="off">

                        {{-- is-invalid --}}
                        {{-- nama_penyuplai --}}
                        <div class="form-group">
                        <label for="edit_nama_penyuplai">Nama Penyuplai<span class="text-danger"> *</span></label>
                        <input id="edit_nama_penyuplai" name="nama_penyuplai" class="nama_penyuplai_input input form-control"
                            type="text" placeholder="Edit Nama Penyuplai" autocomplete="off">
                        {{-- pesan error --}}
                        <span class="nama_penyuplai_error pesan_error text-danger"></span>
                        </div>
                        {{-- telepon_penyuplai --}}
                        <div class="form-group">
                        <label for="edit_telepon_penyuplai">Nomor Telepon<span class="text-danger"> *</span></label>
                        <input id="edit_telepon_penyuplai" name="telepon_penyuplai" class="telepon_penyuplai_input input form-control" type="number"
                            placeholder="Edit Nomor Telepon" autocomplete="off">
                        {{-- pesan error --}}
                        <span class="telepon_penyuplai_error pesan_error text-danger"></span>
                        </div>
                        {{-- alamat_penyuplai --}}
                        <div class="form-group">
                        <label for="edit_alamat_penyuplai">Alamat<span class="text-danger"> *</span></label>
                        <input id="edit_alamat_penyuplai" name="alamat_penyuplai" class="alamat_penyuplai_input input form-control" type="text"
                            placeholder="Edit Alamat Penyuplai" autocomplete="off">
                        {{-- pesan error --}}
                        <span class="alamat_penyuplai_error pesan_error text-danger"></span>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="tutup btn btn-default" data-dismiss="modal">
                             <i class="mdi mdi-close"></i>
                            Tutup
                        </button>
                        <button id="tombol_perbarui" type="submit" class="btn btn-primary">
                            <i class="mdi mdi-update"></i>
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
 