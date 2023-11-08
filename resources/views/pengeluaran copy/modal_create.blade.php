<div class="modal fade" id="modal_tambah" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="container">
                <form id="form_tambah">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title" id="staticBackdropLabel">Pengeluaran Baru</h4>
                        <button type="button" class="tutup close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- is-invalid --}}
                        {{-- nama_pengeluaran --}}
                        <div class="form-group">
                            <label for="nama_pengeluaran">Nama Pengeluaran<span class="text-danger"> *</span></label>
                            {{-- attribute name digunakan untuk mengirim value attribute value --}}
                            <input id="nama_pengeluaran" name="nama_pengeluaran" class="nama_pengeluaran_input input form-control" type="text"
                            placeholder="Masukkan Nama Pengeluaran"  autocomplete="off">
                            {{-- pesan error --}}
                            <span class="nama_pengeluaran_error pesan_error text-danger"></span>
                        </div>
                        {{-- total_pengeluaran --}}
                        <div class="form-group">
                            <label for="total_pengeluaran">Total Pengeluaran<span class="text-danger"> *</span></label>
                            {{-- pada saat kunci ditekan maka panggil fungsi number lalu kirimkan acaraya agar aku bisa menonaktifkan value huruf dan hanya memperbolehkan value angka --}}
                            {{-- attribute data-inputmask agar 1000 menjadi Rp 1.000 --}}
                            <input id="total_pengeluaran" name="total_pengeluaran" class="input_angka total_pengeluaran_input input form-control" type="text"
                            placeholder="Masukkan Total Pengeluaran"  autocomplete="off" onkeypress="return number(event)" data-inputmask="'alias': 'decimal', 'prefix': 'Rp ', 'groupSeparator':  '.',  'removeMaskOnSubmit': true, 'autoUnMask': true, 'rightAlign': false, 'radixPoint': ','">
                            {{-- pesan error --}}
                            <span class="total_pengeluaran_error pesan_error text-danger"></span>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="tutup btn btn-default" data-dismiss="modal">Tutup</button>
                        <button id="tombol_simpan" type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
