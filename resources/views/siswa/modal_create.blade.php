<div class="modal fade" id="modal_tambah" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="container">
                <form id="form_tambah">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title" id="staticBackdropLabel">Tambah siswa</h4>
                        <button type="button" class="tutup close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama">Nama<span class="text-danger"> *</span></label>
                            <input id="nama" name="nama" class="nama_input input form-control" type="text"
                            placeholder="Masukkan Nama"  autocomplete="off">
                            <span class="nama_error pesan_error text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="usia">Usia<span class="text-danger"> *</span></label>
                            <input id="usia" name="usia" class="usia_input input form-control" type="text"
                            placeholder="Masukkan Nama"  autocomplete="off">
                            <span class="usia_error pesan_error text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat<span class="text-danger"> *</span></label>
                            <input id="alamat" name="alamat" class="alamat_input input form-control" type="text"
                            placeholder="Masukkan Nama"  autocomplete="off">
                            <span class="alamat_error pesan_error text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="jurusan">Jurusan<span class="text-danger"> *</span></label>
                            <input id="jurusan" name="jurusan" class="jurusan_input input form-control" type="text"
                            placeholder="Masukkan Nama"  autocomplete="off">
                            <span class="jurusan_error pesan_error text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender<span class="text-danger"> *</span></label>
                            <input id="gender" name="gender" class="gender_input input form-control" type="text"
                            placeholder="Masukkan Nama"  autocomplete="off">
                            <span class="gender_error pesan_error text-danger"></span>
                        </div>
                    </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="tutup btn btn-default" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
