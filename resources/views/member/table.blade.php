{{-- agar tablenya responsive --}}
<div class="table-responsive">
    {{-- aku menyimpan table ke dalam form agar aku bisa mengambil beberapa value column member_id di kotak centang atau input type="checkbox" lalu menghapus beberapa member dan mencetak kartu member --}}
    <form id="form_member">
        {{-- untuk keamanan --}}
        @csrf
        <table class="table table-striped table-sm">
            <thead class="bg-primary">
                <tr>
                    <!-- Pilih -->
                    <th scope="col" width="5%">
                        <input type="checkbox" name="select_all" id="select_all">
                    </th>
                    <th scope="col" width="5%">No</th>
                    <th scope="col">Kode</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Telepon</th>
                    <th scope="col">Alamat</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
        </table>
    </form>
</div>