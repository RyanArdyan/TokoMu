<div class="modal fade" id="modal_edit" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form_edit">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h4 id="staticBackdropLabel" class="modal-title">Edit Data</h4>
                    <button type="button" class="e_tutup close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input id="e_siswa_id" name="siswa_id" class="form-control" type="string" placeholder="siswa ID" autocomplete="off">

                    <div class="form-group">
                        <label for="e_nama">Nama<span class="text-danger"> *</span></label>
                        <input id="e_nama" name="nama" class="e_nama_input e_input form-control" type="text"
                        placeholder="Edit Nama"  autocomplete="off">
                        {{-- pesan error --}}
                        <span class="e_nama_error e_pesan_error text-danger"></span>
                    </div>

                    <div class="form-group">
                        <label for="e_usia">usia<span class="text-danger"> *</span></label>
                        <input id="e_usia" name="usia" class="e_usia_input e_input form-control" type="text"
                        placeholder="Edit usia"  autocomplete="off">
                        {{-- pesan error --}}
                        <span class="e_usia_error e_pesan_error text-danger"></span>
                    </div>

                    <div class="form-group">
                        <label for="e_alamat">alamat<span class="text-danger"> *</span></label>
                        <input id="e_alamat" name="alamat" class="e_alamat_input e_input form-control" type="text"
                        placeholder="Edit alamat"  autocomplete="off">
                        {{-- pesan error --}}
                        <span class="e_alamat_error e_pesan_error text-danger"></span>
                    </div>

                    <div class="form-group">
                        <label for="e_jurusan">Jurusan<span class="text-danger"> *</span></label>
                        <input id="e_jurusan" name="jurusan" class="e_jurusan_input e_input form-control" type="text"
                        placeholder="Edit jurusan"  autocomplete="off">
                        {{-- pesan error --}}
                        <span class="e_jurusan_error e_pesan_error text-danger"></span>
                    </div>

                    <div class="form-group">
                        <label for="e_gender">gender<span class="text-danger"> *</span></label>
                        <input id="e_gender" name="gender" class="e_gender_input e_input form-control" type="text"
                        placeholder="Edit gender"  autocomplete="off">
                        {{-- pesan error --}}
                        <span class="e_gender_error e_pesan_error text-danger"></span>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="e_tutup btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
