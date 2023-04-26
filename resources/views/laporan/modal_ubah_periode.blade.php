<div class="modal fade" id="modal_ubah_periode" data-backdrop="static" data-keyboard="false" tabindex="-1"
aria-labelledby="label_latar_belakang_statis" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {{-- panggil route laporan.ubah_periode method get --}}
            {{-- karena method="GET" maka value akan dikirim lewat url atau value nya akan muncul di url --}}
            <form action="{{ route('laporan.ubah_periode') }}" method="GET">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="label_latar_belakang_statis">Ubah Periode</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- aku butuh .is-invalid untuk menampilkan efek validasi error --}}
                    {{-- tanggal_awal --}}
                    <div class="form-group">
                        <label for="tanggal_awal">Tanggal awal<span class="text-danger"> *</span></label>
                        {{-- @dd($request->tanggal_awal) --}}
                        {{-- kirimkan value input lewat name --}}
                        {{-- attribute value akan mencetak tanggal awal jika sebelumnya sudah pernah memilih tanggal atau sebelumnya aku sudah click tombol Ubah Periode di modal Ubah Periode --}}
                        {{-- request('tanggal_awal') berarti ambil nilai variabel tanggal_awal dari url --}}
                        <input name="tanggal_awal" value="{{ request('tanggal_awal') }}" class="form-control" type="date" required>
                    </div>
                    {{-- is-invalid --}}
                    {{-- tanggal akhir --}}
                    <div class="form-group">
                        <label for="tanggal_akhir">Tanggal akhir<span class="text-danger"> *</span></label>
                        {{-- kirimkan value input lewat name --}}
                        {{-- attribute value akan mencetak tanggal akhir jika sebelumnya sudah pernah memilih tanggal --}}
                        <input name="tanggal_hari_ini" value="{{ request('tanggal_hari_ini') }}" class="form-control" type="date" required>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="mdi mdi-close"></i> Tutup</button>
                    <button type="submit" class="btn btn-primary"><i class="mdi mdi-clock-check"></i> Ubah Periode</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
