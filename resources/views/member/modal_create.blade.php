<div class="modal fade" id="modal_tambah" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {{-- aku buat .container agar formulir ada jarak atas, kanan, bawah dan kiri --}}
            <div class="container">
                <form id="form_tambah">
                    {{-- untuk keamanan --}}
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title" id="staticBackdropLabel">Tambah Member</h4>
                        <button type="button" class="tutup close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- is-invalid --}}
                        {{-- nama_member --}}
                        <div class="form-group">
                            <label for="nama_member">Nama<span class="text-danger"> *</span></label>
                            {{-- untuk menampilkan effect validasi error pada input, aku butuh .is-invalid --}}
                            <input id="nama_member" name="nama_member" class="nama_member_input input form-control" type="text"
                            placeholder="Masukkan Nama"  autocomplete="off">
                            {{-- pesan error --}}
                            <span class="nama_member_error pesan_error text-danger"></span>
                        </div>
                        {{-- telepon_member --}}
                        <div class="form-group">
                            {{-- pada saat aku menekan key keyboard, aku memanggil fungsi number yang mengirim event untuk hanya mengizikan user memasukkan angka dan mematikkan huruf --}}
                            <label for="telepon_member">Nomor Telepon<span class="text-danger"> *</span></label>
                            <input id="telepon_member" name="telepon_member" class="telepon_member_input input form-control" type="text"
                            placeholder="Masukkan nomor telepon"  autocomplete="off" 
                            require onkeypress="return number(event)">
                            {{-- pesan error --}}
                            <span class="telepon_member_error pesan_error text-danger"></span>
                        </div>
                        {{-- alamat_member --}}
                        <div class="form-group">
                            <label for="alamat_member">Alamat<span class="text-danger"> *</span></label>
                            <input id="alamat_member" name="alamat_member" class="alamat_member_input input form-control" type="text"
                            placeholder="Masukkan alamat"  autocomplete="off">
                            {{-- pesan error --}}
                            <span class="alamat_member_error pesan_error text-danger"></span>
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
