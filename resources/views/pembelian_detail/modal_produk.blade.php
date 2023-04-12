<div class="modal fade" id="modal_produk" data-backdrop="static" data-keyboard="false" tabindex="-1"
aria-labelledby="label_judul" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="container">
                <form id="form_produk">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title" id="label_judul">Pilih Produk</h4>
                        <button type="button" class="tutup close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table id="table_produk" class="table table-striped table" width="100%">
                                <thead width="100%">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Produk</th>
                                        <th>Harga</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </form>
            </div>            
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
