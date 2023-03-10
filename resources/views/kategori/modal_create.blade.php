<div class="modal fade" id="modal_tambah" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="container">
                <form id="form_tambah">
                    {{-- untuk kemanan --}}
                    @csrf
                   {{-- modal-header --}}
                    <div class="modal-header">
                        <h4 id="staticBackdropLabel" class="modal-title">Tambah Kategori</h4>
                        <button type="button" class="tutup close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    {{-- modal-body --}}
                    <div class="modal-body">
                        {{-- nama_kategori --}}
                        <div class="form-group">
                            <label for="nama_kategori">Kategori<span class="text-danger"> *</span></label>
                            {{-- input perlu .is-invalid agar bisa menampilkan pesan error --}}
                            {{-- #nama_kategori akan aku panggil menggunakan pengulangan ketika validasi error menenmukan error --}}
                            <input id="nama_kategori" name="nama_kategori" class="nama_kategori_input input form-control" type="text"
                            placeholder="Kategori" autocomplete="off">
                            {{-- pesan error --}}
                            <span class="nama_kategori_error pesan_error text-danger"></span>
                        </div>

                        {{-- deskripsi_kategori --}}
                        <div class="form-group">
                            <label for="deskripsi_kategori">Deskripsi<span class="text-danger"> *</span></label>
                            {{-- input perlu .is-invalid agar bisa menampilkan pesan error --}}
                            {{-- #deskripsi_kategori akan aku panggil menggunakan pengulangan ketika validasi error menenmukan error --}}
                            <input id="deskripsi_kategori" name="deskripsi_kategori" class="deskripsi_kategori_input input form-control" type="text"
                            placeholder="Deskripsi" autocomplete="off">
                            {{-- pesan error --}}
                            <span class="deskripsi_kategori_error pesan_error text-danger"></span>
                        </div>
                    </div>
                    {{-- modal footer --}}
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="tutup btn btn-default" data-dismiss="modal">
                            <i class="mdi mdi-close"></i>
                            Tutup
                        </button>
                        <button id="tombol_submit" type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
