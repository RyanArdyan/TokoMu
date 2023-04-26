<div class="modal fade" id="modal_tambah" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {{-- aku kasi container agar ada jarak kiri, atas, kanan, bawah --}}
            <div class="container">
                <form id="form_tambah">
                    {{-- untuk keamanan --}}
                    @csrf
                    {{-- method POST --}}
                    @method('POST')
                    <div class="modal-header">
                        <h4 class="modal-title" id="staticBackdropLabel">Tambah Kasir</h4>
                        <button type="button" class="tutup close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- is-invalid --}}
                        {{-- NAME --}}
                        <div class="form-group">
                            <label for="name">Name<span class="text-danger"> *</span></label>
                            {{-- input perlu .is-invalid agar bisa menampilkan pesan error --}}
                            {{-- .name input akan aku panggil menggunakan pengulangan ketika validasi error menenmukan error --}}
                            <input id="name" name="name" class="name_input input form-control" type="text"
                            placeholder="Name"  autocomplete="off">
                            {{-- pesan error --}}
                            <span class="name_error pesan_error text-danger"></span>
                        </div>
                        {{-- email --}}
                        <div class="form-group">
                            <label for="email">Email<span class="text-danger"> *</span></label>
                            <input id="email" name="email" class="email_input input form-control" type="text"
                            placeholder="Email" autocomplete="off">
                            {{-- pesan error --}}
                            <span class="email_error pesan_error text-danger"></span>
                        </div>
                        {{-- passsword --}}
                        <div class="form-group">
                            <label for="password">Password<span class="text-danger"> *</span></label>
                            <input id="password" name="password" class="password_input input ubah_type_password form-control" type="password"
                            placeholder="Password"  autocomplete="off">
                            {{-- pesan error --}}
                            <span class="password_error pesan_error text-danger"></span>
                        </div>
                        {{-- logikanya jika value input password tidak sama engan input password maka tampilkan validasi error yang menyatakan "Konfirmasi password tidak cocok" --}}
                        {{-- konfirmasi passsword --}}
                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password<span class="text-danger"> *</span></label>
                            <input id="password_confirmation" name="password_confirmation" class="password_confirmation_input input ubah_type_password form-control" type="password"
                            placeholder="Konfirmasi Password" autocomplete="off">
                            {{-- pesan error --}}
                            <span class="password_confirmation_error pesan_error text-danger"></span>
                        </div>
    
                        <small>
                            <div class="lihat_password jadikan_pointer fa fa-eye">Lihat Password</div>
                        </small>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="tutup btn btn-default" data-dismiss="modal">
                            <i class="mdi mdi-close"></i>
                            Tutup
                        </button>
                        <button type="submit" class="btn btn-primary">
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
