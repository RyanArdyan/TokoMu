{{-- enctype digunakan jika ada input type file --}}
<form id="perbarui_profile" enctype="multipart/form-data">
    @csrf
    {{-- ID --}}
     <div class="form-group" hidden>
        <label for="id" hidden>ID</label>
        {{-- cetak id user yang login ke dalam attribute value --}}
        {{-- aku butuh ID, agar bisa update detail user --}}
        {{-- aku butuh name untuk emngirim value input --}}
        <input name="id_user" type="hidden" class="form-control" id="id" placeholder="Edit id"
            value="{{ $detail_user_yang_login->user_id }}">
    </div>

    {{-- name --}}
    <div class="form-group">
        <label for="nama">Nama</label>
        <input name="name" type="text" class="input input_name form-control" id="nama"
            placeholder="Edit Nama" value="{{ $detail_user_yang_login->name }}" autocomplete="off">
        {{-- pesan error --}}
        <span class="error error_name text-danger"></span>
    </div>


    {{-- email --}}
    <div class="form-group">
        <label for="email">Email</label>
        <input name="email" type="text" class="input input_email form-control" id="email"
            placeholder="Edit Email" value="{{ $detail_user_yang_login->email }}" autocomplete="off" readonly>
        {{-- pesan error --}}
        <span class="error error_email text-danger"></span>
    </div>

     {{-- Foto Profile --}}
    <div class="form-group">
        <label for="pilih_gambar">Foto Profil</label>
        <br>
        {{-- asset akan memanggil folder public --}}
        <img id="pratinjau_gambar" src="{{ asset('storage/foto_profil') }}/{{ $detail_user_yang_login->gambar }}"
            alt="gambar User" width="150px" height="150px" class="mb-3 rounded">
        <div class="input-group">
            <div class="custom-file">
                <input name="gambar" type="file" class="input input_gambar custom-file-input" id="pilih_gambar">
                {{-- pesan error --}}
                <label class="custom-file-label" for="gambar">Pilih file</label>
            </div>
        </div>
        <span class="error error_gambar text-danger"></span>
    </div>

    {{-- Update Passowrd --}}
    {{-- javascript:void(0) sama seperti # hanya saja dia tidak akan kembali ke halaman atas --}}
    <i class="fas danger fa-key"><a id="edit_password" data-id="{{ $detail_user_yang_login->id }}" href="javascript:void(0)" class=" waves-effect waves-light text-primary ml-1" data-toggle="modal" data-target=".bs-example-modal-lg"> Edit Password?</a></i>
    <br>

    <button type="submit" class="btn btn-primary mt-2">
        <i class="mdi mdi-update"></i>
        Perbarui
    </button>
</form>