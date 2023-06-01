<div class="modal fade" id="modal_ubah_periode" data-backdrop="static" data-keyboard="false" tabindex="-1"
aria-labelledby="label_latar_belakang_statis" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {{-- ketika form dikirim maka panggil route penjualan.export_excel method post --}}
            <form action="{{ route('penjualan.export_excel') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="label_latar_belakang_statis">Pilih Periode</h4>
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
                        <input name="tanggal_awal" class="form-control" type="date" required>
                    </div>
                    {{-- is-invalid --}}
                    {{-- tanggal akhir --}}
                    <div class="form-group">
                        <label for="tanggal_akhir">Tanggal akhir<span class="text-danger"> *</span></label>
                        {{-- kirimkan value input lewat name --}}
                        <input name="tanggal_akhir" class="form-control" type="date" required>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="mdi mdi-close"></i> Tutup</button>
                    <button type="submit" class="btn btn-primary"><i class="mdi mdi-clock-check"></i>Cetak Excel</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
