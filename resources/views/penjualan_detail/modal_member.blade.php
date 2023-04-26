<div class="modal fade" id="modal_member" data-backdrop="static" data-keyboard="false" tabindex="-1"
aria-labelledby="label_judul" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="container">
                <form id="form_member">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title" id="label_judul">Pilih Member</h4>
                        <button type="button" class="tutup close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table id="table_member" class="table table-striped table" width="100%">
                                <thead width="100%">
                                    <tr>
                                        <th scope="col" width="5%">No</th>
                                        <th scope="col" width="10%">Kode Member</th>
                                        <th scope="col">Nama Member</th>
                                        <th scope="col" width="10%">Telepon</th>
                                        <th scope="col">Alamat</th>
                                        <th width="5%" scope="col">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($semua_member as $member)
                                        <tr>
                                            {{-- panggil fungsi helpers angka bentuk --}}
                                            {{-- pengulanagan nomor --}}
                                            <td width="5%">{{ angka_bentuk($loop->iteration) }}</td>
                                            <td width="10%">
                                                <span class="badge badge-success">
                                                {{ $member->kode_member }}
                                                </span>
                                            </td>
                                            <td>{{ $member->nama_member }}</td>
                                            <td width="10%">{{ $member->telepon_member }}</td>
                                            <td>{{ $member->alamat_member }}</td>
                                            <td width="5%">
                                                <button onclick="pilih_member('{{ $member->member_id }}', '{{ $member->kode_member }}')" type="button" class="btn btn-success btn-sm"><i class="fa fa-hand-point-right"></i>Pilih</button>
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
