<div class="modal fade" id="modal_edit" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form_edit">
                {{-- untuk keamanan --}}
                @csrf
                {{-- paksa html untuk memanggil route tipe PUT --}}
                @method('PUT')
                <div class="modal-header">
                    <h4 id="staticBackdropLabel" class="modal-title">Edit Data</h4>
                    <button type="button" class="e_tutup close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- is-invalid --}}
                    {{-- ID --}}
                    <input id="e_member_id" name="member_id" class="form-control" type="hidden"
                        placeholder="Member ID" autocomplete="off">
                    {{-- nama_member --}}
                    <div class="form-group">
                        <label for="e_nama_member">Nama Member<span class="text-danger"> *</span></label>
                        <input id="e_nama_member" name="nama_member" class="e_nama_member_input e_input form-control" type="text"
                        placeholder="Edit Nama Member"  autocomplete="off">
                        {{-- pesan error --}}
                        <span class="e_nama_member_error e_pesan_error text-danger"></span>
                    </div>
                    {{-- telepon --}}
                    <div class="form-group">
                        <label for="e_telepon_member">Nomor Telepon<span class="text-danger"> *</span></label>
                        {{-- ketika key keboard di tekan maka panggil fungsi number lalu kirimkan eventnya untuk melarang user memasukkan huruf dan hanya izinkan angka --}}
                        <input id="e_telepon_member" name="telepon_member" class="e_telepon_member_input e_input form-control" type="text"
                        placeholder="Edit Nomor Telepon"  autocomplete="off" onkeypress="return number(event)">
                        {{-- pesan error --}}
                        <span class="e_telepon_member_error e_pesan_error text-danger"></span>
                    </div>
                    {{-- alamat_member --}}
                    <div class="form-group">
                        <label for="e_alamat_member">Alamat<span class="text-danger"> *</span></label>
                        <input id="e_alamat_member" name="alamat_member" class="e_alamat_member_input e_input form-control" type="text"
                        placeholder="Edit Alamat"  autocomplete="off">
                        {{-- pesan error --}}
                        <span class="e_alamat_member_error e_pesan_error text-danger"></span>
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
