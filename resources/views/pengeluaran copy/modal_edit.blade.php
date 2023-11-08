<div class="modal fade" id="modal_edit" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form_edit">
                {{-- untuk keamanan --}}
                @csrf
                {{-- paksa route mengubah tipe nya menjadi PUT --}}
                @method('PUT')
                <div class="modal-header">
                    <h4 class="modal-title" id="staticBackdropLabel">Edit Data</h4>
                    <button type="button" class="e_tutup close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- is-invalid digunakan jika aku ingin menampilkan validasi error di input --}}
                    {{-- pengeluarna_id --}}
                    {{-- name digunakan untuk dikirim ke controller --}}
                    <input id="e_pengeluaran_id" name="pengeluaran_id" class="form-control" type="hidden"
                        placeholder="pengeluaran_id" autocomplete="off">
                    {{-- nama_pengeluaran --}}
                    <div class="form-group">
                        <label for="e_nama_pengeluaran">Nama Pengeluaran<span class="text-danger"> *</span></label>
                        <input id="e_nama_pengeluaran" name="nama_pengeluaran" class="e_nama_pengeluaran_input e_input form-control" type="text"
                        placeholder="Edit Nama Pengeluaran" autocomplete="off" >
                        {{-- pesan error --}}
                        <span class="e_nama_pengeluaran_error e_pesan_error text-danger"></span>
                    </div>

                    <div class="form-group">
                        <label for="e_total_pengeluaran">Total Pengeluaran<span class="text-danger"> *</span></label>
                        {{-- pada saat kunci keyboard di tekan maka panggil fungsi number lalu kirimkan event agar hanya angka yang diperbolehkan, dan huruf di larang --}}
                        {{-- attribute data-inputmask aku gunakan agar 1000 menjadi Rp 1.000 --}}
                        <input id="e_total_pengeluaran" name="total_pengeluaran" class="e_total_pengeluaran_input e_input input_angka  form-control" type="text"
                        placeholder="Edit Total Pengeluaran"  autocomplete="off" onkeypress="return number(event)" data-inputmask="'alias': 'decimal', 'prefix': 'Rp ', 'groupSeparator':  '.',  'removeMaskOnSubmit': true, 'autoUnMask': true, 'rightAlign': false, 'radixPoint': ','">
                        {{-- pesan error --}}
                        <span class="e_total_pengeluaran_error e_pesan_error text-danger"></span>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="e_tutup btn btn-default" data-dismiss="modal">Tutup</button>
                    <button id="tombol_perbarui" type="submit" class="btn btn-primary">Perbarui</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
