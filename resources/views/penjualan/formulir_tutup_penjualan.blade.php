{{-- cetak panggil rute penjualan_detail.tutup_penjualan --}}
<form action="{{ route('penjualan_detail.tutup_penjualan') }}" method="POST" id="form_tutup_penjualan">
    {{-- untuk keamanan --}}
    @csrf
    {{-- is-invalid --}}
    {{-- waktu_jam_7 --}}
    <div class="form-group">
        <label for="waktu_jam_7">Jam 7<span class="text-danger"> *</span></label>
        <input id="waktu_jam_7" name="waktu_jam_7" class="form-control" type="text" autocomplete="off" placeholder="Masukkan jam 7">
    </div>
    {{-- waktu_tutup --}}
    <div class="form-group">
        <label for="waktu_tutup">Waktu Tutup<span class="text-danger"> *</span></label>
        <input id="waktu_tutup" name="waktu_tutup" class="form-control" type="text"
        placeholder="Masukkan waktu tutup"  autocomplete="off">
    </div>
    {{-- hasil_pilihan --}}
    <div class="form-group">
        <label for="hasil_pilihan">Hasil Pilihan<span class="text-danger"> *</span></label>
        <input id="hasil_pilihan" name="hasil_pilihan" class="form-control" type="text"
        placeholder="Masukkan Hasil Pilihan"  autocomplete="off">
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
