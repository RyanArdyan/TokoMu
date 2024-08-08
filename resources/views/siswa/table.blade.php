<div class="table-responsive">
    {{-- aku membungkus table ke dalam form agar aku bisa mengambil value column pengeluaran_id yang disimpan ke dalam input type="checkbox" --}}
    <form id="form_siswa">
        <!-- laravel mewajibkan keamanan dari serangan csrf -->
        @csrf
        <table class="table table-striped table-sm">
            <thead class="bg-primary">
                <tr>
                    <!-- Pilih -->
                    <th scope="col" width="5%">
                        {{-- buat kotak centang untuk memilih semua pengeluaran --}}
                        <input type="checkbox" name="select_all" id="select_all">
                    </th>
                    <th scope="col" width="5%">No</th>
                    <th scope="col" width="22%">Nama</th>
                    <th scope="col">Jurusan</th>
                    <th scope="col">Gender</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
        </table>
    </form>
</div>

{{--



--}}
