<div class="table-responsive">
    {{-- berfungsi untuk mencetak PDF --}}
    <form class="form_laporan">
        @csrf
        @method('post')
        <table id="table_laporan_pendapatan" class="table table-striped table-hover table-bordered">
            <thead class="bg-primary">
                <tr>
                    <th scope="col" width="22%">Tanggal</th>
                    <th scope="col">Penjualan</th>
                    <th scope="col" width="18%">Pembelian</th>
                    <th scope="col" width="18%">Pengeluaran</th>
                    <th scope="col" width="18%">Pendapatan</th>
                </tr>
            </thead>
        </table>
    </form>
</div>