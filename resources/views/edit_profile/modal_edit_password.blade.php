<!--  Modal content for the above example -->
<div class="modal fade bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true" id="modal_edit_password">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Edit Password</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form id="form_edit_password">
                    {{-- untuk keamanan dari serangan csrf --}}
                    @csrf
    
                    <div class="modal-body">
                        {{-- passsword Lama--}}
                        {{-- logikanya jika input password lama tidak sama dengan $detaiL_user->password maka maka tampilkan "Password lama salah" --}}
                        <div class="form-group">
                            <label for="e_password_lama">Password Lama<span class="text-danger"> *</span></label>
                            {{-- input butuh .is-invalid untuk menampilkan edek validasi error yaitu border berwarna merah --}}
                            {{-- .e_input berfungsi sebagai efek refresh validasi error --}}
                            {{-- .e_password_lama_input berfungsi untuk mencetak efek input error yaitu border berwarna merah --}}
                            {{-- .ubah_type_password berfungsi untuk fitur lihat password --}}
                            <input id="e_password_lama" name="password_lama" class="e_password_lama_input e_input ubah_type_password form-control" type="password"
                            placeholder="Password Lama" autocomplete="off">
                            {{-- pesan error --}}
                            {{-- .e_password_lama_error berfungsi untuk menampilkan pesan error --}}
                            {{-- .e_pesan_error berfungsi sebagai efek refresh validasi error --}}
                            <span class="e_password_lama_error e_pesan_error text-danger"></span>
                        </div>
                        
                        {{--  Password baru tidak boleh sama dengan password lama --}}
                        <div class="form-group">
                            <label for="e_password_baru">Password Baru<span class="text-danger"> *</span></label>
                            <input id="e_password_baru" name="password_baru" class="e_password_baru_input e_input ubah_type_password form-control" type="password"
                            placeholder="Password Baru" autocomplete="off">
                            {{-- pesan error --}}
                            <span class="e_password_baru_error e_pesan_error text-danger"></span>
                        </div>
    
                        {{-- fitur lihat password dan sembunyikan password --}}
                        <small>
                            <a href="javascript:void(0)" class="lihat_password fa fa-eye">Lihat Password</a>
                        </small>

    
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="e_tutup btn btn-default" data-dismiss="modal">
                            <i class="mdi mdi-close"></i>
                            Tutup
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-update"></i>
                            Perbarui
                        </button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->