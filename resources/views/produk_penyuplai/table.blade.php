{{-- agar tablenya responsive --}}
<div class="table-responsive">
    {{-- aku menyimpan table ke dalam form agar aku bisa mengambil value input name="produk_penyuplai_ids[]" yang berada di dalam table lalu menghapus beberapa produk penyuplai --}}
    <form id="form_produk_penyuplai">
        {{-- untuk keamanan --}}
        @csrf
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    {{-- kotak centang --}}
                    <th scope="col" width="5%">
                        <input type="checkbox" name="select_all" id="select_all">
                    </th>
                    <th scope="col" width="5%">No</th>
                    <th scope="col">Nama Produk</th>
                    <th scope="col">Kategori</th>
                    <th scope="col">Penyuplai</th>
                    <th scope="col">Merk</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
        </table>
    </form>
</div>
