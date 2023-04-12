<div class="modal fade" id="modal_retur" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="container">
                <form id="form_retur_pembelian" class="form-horizontal" role="form">
                    {{-- laravel mewajibkan keamanan dari serangan csrf --}}
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title" id="staticBackdropLabel">Retur Pembelian</h4>
                        <button type="button" class="tombol_tutup_retur_pembelian close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        {{-- <div class="form-group row">
                            <label for="keterangan" class="col-sm-3 col-form-label" >Keterangan</label>
                            <div class="col-sm-9">
                                <input type="text" name="keterangan" class="form-control" id="keterangan"
                                    placeholder="Keterangan" rqeuired>
                            </div>
                        </div> --}}

                        <table id="table_retur_pembelian_detail" class="table table-striped table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th width="5%">No</th>
                                    <th>Nama Produk</th>
                                    <th width="15%">Jumlah Retur</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_retur_pembelian">

                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="button" class="tombol_tutup_retur_pembelian btn btn-default" data-dismiss="modal">Tutup</button>
                        <button id="submit_retur_pembelian" type="submit" class="btn btn-info waves-effect waves-light">Retur</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="pembungkus_modal_retur"></div>
