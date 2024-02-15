{{-- <table border="0" cellspacing="5" cellpadding="5">
    <tbody><tr>
        <td>Tanggal awal:</td>
        <td><input type="date" id="tanggal_awal" name="tanggal_awal"></td>
    </tr>
    <tr>
        <td>Tanggal akhir:</td>
        <td><input type="date" id="tanggal_akhir" name="tanggal_akhir"></td>
    </tr>
</tbody></table> --}}


<div style="margin: 20px 0px;">
    <strong>Filter Tanggal:</strong>
    {{-- name="jangkauan_tanggal" --}}
    <input type="text" name="daterange" value="" />
    <button class="btn btn-success filter">Filter</button>
</div>

<div class="table-responsive">
    <table id="table_penjualan" class="table table-striped table-sm">
        <thead class="bg-primary">
            <tr>
                <th scope="col" width="5%">No</th>
                <th scope="col">Tanggal</th>
                <th scope="col">Kode Member</th>
                <th scope="col">Total Barang</th>
                <th scope="col">Total Harga</th>
                <th scope="col">Diskon</th>
                <th scope="col">Harus Bayar</th>
                <th scope="col">Kasir</th>
                <th scope="col" width="10%">Action</th>
            </tr>
        </thead>
    </table>
</div>
