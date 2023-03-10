<div class="modal fade" id="modal_penyuplai" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
       <div class="modal-content">
          <form id="form_penyuplai">
             @csrf
             <div class="modal-header">
                <h4 class="modal-title" id="staticBackdropLabel">Pilih Penyuplai</h4>
                <button type="button" class="tutup close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
                </button>
             </div>
             <div class="modal-body">
                <div class="table-responsive">
                   <table id="table_penyuplai" class="table table-striped table">
                      <thead>
                         <tr>
                            <th scope="col" width="5%">No</th>
                            <th scope="col">Nama</th>
                            <th scope="col" width="10%">Telepon</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">Action</th>
                         </tr>
                      </thead>
                      <tbody>
                        {{-- lakukan pengulangan kepada $semua_penyuplai agar bisa menampilkan semua penyuplai --}}
                        {{-- untuk setiap $semua_penyuplai sebagai $penyuplai --}}
                         @foreach ($semua_penyuplai as $penyuplai)
                            <tr>
                                {{-- loop->iteration akan melakukan pengulangan nomor --}}
                               <td width="5%">{{ $loop->iteration }}</td>
                               {{-- cetak setiap value column nama_penyuplai --}}
                               {{-- cetak setiap value penyuplai, column nama_penyuplai --}}
                               <td>{{ $penyuplai->nama_penyuplai }}</td>
                               <td width="12%">{{ $penyuplai->telepon_penyuplai }}</td>
                               <td>{{ $penyuplai->alamat_penyuplai }}</td>
                               <td width="10%">
                                    {{-- ke route pembelian.create lalu kirimkan value column penyuplai --}}
                                    <a href="{{ route('pembelian.create', $penyuplai->penyuplai_id) }}"
                                     class="btn btn-primary btn-sm"><i class="fa fa-truck"></i> Pilih</a>
                               </td>
                            </tr>
                         @endforeach
                      </tbody>
                   </table>
                </div>
             </div>
          </form>
       </div>
       <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
 </div>
 