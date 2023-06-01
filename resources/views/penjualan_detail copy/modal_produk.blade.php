<div class="modal fade" id="modal_produk">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="container">
                <form id="form_produk">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Pilih Produk</h4>
                        <button type="button" class="tutup close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table id="table_produk" class="table table-striped table" width="100%">
                                <thead width="100%">
                                    <tr>
                                        <th scope="col" width="5%">No</th>
                                        <th scope="col" width="10%">Kode Produk</th>
                                        <th scope="col" width="10%">Nama Produk</th>
                                        <th scope="col" width="5%">Harga Beli</th>
                                        <th scope="col" width="5%">Stok</th>
                                        <th scope="col" width="5%">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    {{-- lakukan pengulangan terhadap semua_produk yang dikirim PenjualanController, method index agar mendapat semua detail_member --}}
                                    @foreach($semua_produk as $produk)
                                    <tr width="5%">
                                        {{-- cetak, panggil fungsi angka_bentuk lalu kirimkan $loop->iteasi yang akan melakukan pengulangan nomor --}}
                                        {{-- $loop->iteration akan looping nomor, dimulai dari 1 --}}
                                        <td width="5%">{{ angka_bentuk($loop->iteration) }}</td>

                                        <td>
                                            {{-- cetak setiap value detail_produk, column kode_produk --}}
                                            <span class="badge badge-success">{{ $produk->kode_produk }}</span>
                                        </td>

                                        <td>{{ $produk->nama_produk }}</td>
                                        {{-- cetak panggil fungsi rupiah_bentuk milik helpers lalu kirimkna value detail_produk, column harga_beli --}}
                                        <td width="10%">{{ rupiah_bentuk($produk->harga_beli) }}</td>

                                        <td width="5%">{{ angka_bentuk($produk->stok) }}</td>

                                        <td width="5%">
                                            {{-- attribute data-produk-id menyimpan value atau nilai detail_produk, column produk_id --}}
                                            <button data-produk-id="{{ $produk->produk_id }}" data-kode-produk="{{ $produk->kode_produk }}" class="tombol_pilih_produk btn btn-sm btn-success" type="button">
                                                <i class="fa fa-hand-point-right">Pilih</i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
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
