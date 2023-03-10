{{-- jika didalam formulir ada input type file maka harus ada enctype="multipart/form-data" --}}
<form enctype="multipart/form-data">
    {{-- untuk keamanan --}}
    {{-- Cross-Site Request Forgery (CSRF) adalah serangan eksplotasi berbahaya bagi para pengguna aplikasi web yang berupa permintaan tidak sah. --}}
    @csrf
    <div class="card-body">
        {{-- Nama Perusahaan --}}
        <div class="form-group">
            <label for="nama_perusahaan">Nama Perusahaan</label>
            {{-- name berfungsi untuk mengirim data input --}}
            {{-- cetak nama perusahaan dari detail perusahan di input, attribute value --}}
            <input name="nama_perusahaan" type="text" class="input input_nama_perusahaan form-control"
                id="nama_perusahaan" placeholder="Nama Perusahaan" value="{{ $detail_pengaturan->nama_perusahaan }}" autocomplete="off">
            {{-- pesan error --}}
            <span class="error error_nama_perusahaan text-danger"></span>
        </div>

        <div class="form-group">
            <label for="alamat_perusahaan">Alamat</label>
            {{-- cetak alamat_perusahaan dari detail perusahan di input --}}
            <input name="alamat_perusahaan" type="text" class="input input_alamat_perusahaan form-control"
                id="alamat_perusahaan" placeholder="Alamat" value="{{ $detail_pengaturan->alamat_perusahaan }}" autocomplete="off">
            {{-- pesan error --}}
            <span class="error error_alamat_perusahaan text-danger"></span>
        </div>

        <div class="form-group">
            <label for="telepon_perusahaan">Telepon</label>
            {{-- cetak telepon_perusahaan dari detail perusahan di input --}}
            <input name="telepon_perusahaan" type="number" class="input input_telepon_perusahaan form-control"
                id="telepon_perusahaan" placeholder="Telepon" value="{{ $detail_pengaturan->telepon_perusahaan }}" autocomplete="off">
            {{-- pesan error --}}
            <span class="error error_telepon_perusahaan text-danger"></span>
        </div>

        {{-- tipe nota tidak memiliki validasi error karena dia select jadi user tidak mungkin salah --}}
        <div class="form-group">
            <label for="tipe_nota_perusahaan">Tipe Nota</label>
            <select name="tipe_nota_perusahaan" class="form-control" id="tipe_nota_perusahaan">
                {{-- Jika tipe nota dari detail perusahaan adalah 1 maka cetak selected atau pilih, jika bukan maka cetak string kosong--}}
                <option value="1" {{ ($detail_pengaturan->tipe_nota_perusahaan === 1) ? 'selected' : '' }}>Nota Kecil</option>
                {{-- Jika tipe nota dari detail perusahaan adalah 2 maka cetak selected atau pilih, jika bukan maka cetak string kosong --}}
                <option value="2" {{ ($detail_pengaturan->tipe_nota_perusahaan === 2) ? 'selected' : '' }}>Nota Besar</option>
            </select>
        </div>

        <div class="form-group">
            <label for="diskon_perusahaan">Diskon_perusahaan</label>
            {{-- cetak diskon_perusahaan dari detail perusahaan di input --}}
            <input name="diskon_perusahaan" type="number" class="input input_diskon_perusahaan form-control"
                {{-- attribute value akan mencetak value dari detail pengaturan, column diskon_perusahaan --}}
                id="diskon_perusahaan" placeholder="diskon_perusahaan" value="{{ $detail_pengaturan->diskon_perusahaan }}" autocomplete="off">
            {{-- pesan error --}}
            <span class="error error_diskon_perusahaan text-danger"></span>
        </div>

        {{-- logo perusahaan --}}
        <div class="form-group">
            <label for="logo_perusahaan">Logo Perusahaan</label>
            <br>
            {{-- pratinjau gambar logo perusahaan --}}
            {{-- src panggil folder public/gambar_pengaturan/ --}}
            <img id="pratinjau_logo_perusahaan"
                src='{{ asset("storage/gambar_pengaturan/$detail_pengaturan->logo_perusahaan") }}'' alt="Logo Perusahaan"
                width="150px" height="150px" class="mb-3 rounded">
            {{-- input ubah logo perusahaan --}}
            <div class="input-group">
                <div class="custom-file">
                    {{-- accept hanya akan mensetujui gambar --}}
                    <input name="logo_perusahaan" type="file"
                        class="input input_logo_perusahaan custom-file-input" id="logo_perusahaan" accept="image/*">
                    {{-- pesan error --}}
                    <label class="custom-file-label" for="logo_perusahaan">Pilih Gambar</label>
                </div>
            </div>
            <span class="error error_logo_perusahaan text-danger"></span>
        </div>

        {{-- kartu member --}}
        <div class="form-group">
            <label for="kartu_member">Kartu Member</label>
            <br>
            {{-- pratinjau gambar kartu member --}}
            {{-- src panggil folder public/gambar_pengaturan/ --}}
            <img id="pratinjau_kartu_member"
                src='{{ asset("storage/gambar_pengaturan/$detail_pengaturan->kartu_member") }}'' alt="Kartu Member"
                width="150px" height="150px" class="mb-3 rounded">
            {{-- input untuk mengubah gambar kartu member --}}
            <div class="input-group">
                <div class="custom-file">
                    <input name="kartu_member" type="file"
                        class="input input_kartu_member custom-file-input" id="kartu_member" accept="image/*">
                    {{-- pesan error --}}
                    <label class="custom-file-label" for="kartu_member">Pilih Gambar</label>
                </div>
            </div>
            <span class="error error_kartu_member text-danger"></span>
        </div>

        <button type="submit" class="btn btn-primary mt-2">
            <i class="mdi mdi-update"></i>
            Perbarui Pengaturan
        </button>

    </div>
    <!-- /.card-body -->

</form>