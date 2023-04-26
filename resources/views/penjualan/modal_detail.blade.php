<div class="modal fade" id="modal_detail">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            {{-- modal header --}}
            <div class="modal-header">
              <h4 class="modal-title">Detail Penjualan</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            {{-- modal body --}}
            <div class="modal-body">
                <table id="table_penjualan_detail" class="table table-striped table-bordered table-sm">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
