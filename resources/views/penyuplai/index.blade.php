{{-- memperluas parentnya yaitu layouts.app --}}
@extends('layouts.app')

{{-- dorong css lalu tangkap menggunakan $stack('css') --}}
@push('css')
@endpush

{{-- kirim value section title ke @Yield('title') --}}
@section('title', 'Penyuplai')

{{-- kirim value section konten ke @yield('konten') --}}
@section('konten')
    <div class="row mb-2">
    <div class="col-sm-12 mt-2">
            {{-- termasuk ada jika modal dipanggil --}}
            {{-- panggil modal tambah --}}
            @includeIf('penyuplai.modal_create')
            {{-- panggil modal edit --}}
            @includeIf('penyuplai.modal_edit')

            {{-- agar tablenya responsive --}}
            <div class="table-responsive">
                {{-- aku menyimpan table di dalam formulir agar aku bisa mengirim value element <td> --}}
                <form class="form_penyuplai">
                    {{-- untuk keamanan --}}
                    @csrf
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <!-- Pilih -->
                                <th scope="col" width="5%">
                                    <input type="checkbox" name="select_all" id="select_all">
                                </th>
                                <th scope="col" width="5%">No</th>
                                <th scope="col">Nama penyuplai</th>
                                <th scope="col">Telepon</th>
                                <th scope="col">Alamat</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                    </table>
                </form>
            </div>

            {{-- jika aku click tombol Tambah penyuplai maka panggil modal penyuplai --}}
            <button id="tombol_tambah" class="btn btn-purple btn-sm">
                <i class="mdi mdi-plus"></i>
                Tambah Penyuplai
            </button>

            {{-- Fitur hapus beberapa penyuplai berdasarkan kotak centang yang di checklist --}}
            <button id="tombol_hapus" type="button" class="btn btn-danger btn-flat btn-sm"><i class="fa fa-trash"></i>
                Hapus</button>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

{{-- dorong script dan tangkap penyuplai --}}
@push('script')
<script>
    // read data penyuplai
    // panggil table lalu gunakan datatable
    let table = $("table").DataTable({
        // Jika penyuplai sedang dimaut maka tampilkan processing nya dulu
        processing: true,
        // server side akan menangani data yang lebih besar dari 10.000
        serverSide: true,
        // lakukan ajax, dan panggil route pnyuplai.read
        ajax: "{{ route('penyuplai.read') }}",
        // buat tbody, tr dan td lalu isi datanya
        columns: [{
                // buat pengulangan kotak centang
                data: "select",
                sortable: false
            },
            {
                // pengulangan nomor
                // DT_RowIindex didapatkan ari laravel datatable
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                sortable: false
            },
            {
                data: 'nama_penyuplai',
                name: 'nama_penyuplai'
            },
            {
                data: 'telepon_penyuplai',
                name: 'telepon_penyuplai'
            },
            {
                data: 'alamat_penyuplai',
                name: 'alamat_penyuplai'
            },
            {
                data: 'action',
                name: 'action',
                sortable: false,
                searchable: false
            }
        ],
        language: {
            url: "/terjemahan_datatable/indonesia.json"
        }
    });

    // tampilkan modal tambah
    // jika #tombol_tambah di click maka jalankan fungsi berikut
    $("#tombol_tambah").on("click", function() {
        // show modal tambah
        $("#modal_tambah").modal("show");
    });

    // ketika modal tambah ditutup maka reset formulir, dan hapus error validasi
    $(".tutup").on("click", function() {
        $("form")[0].reset();
        $(".input").removeClass("is-invalid");
        $(".pesan_error").text("");
    });

    // jika modal tambah dikirim
    // jika #form_tambah dikirim maka jalankan fungsi berikut
    $("#form_tambah").on("submit", function(e) {
        // cegah bawaannya yaitu reload
        e.preventDefault();
        // lakukan ajax
        $.ajax({
            // url ke route penyuplai.store
            url: "{{ route('penyuplai.store') }}",
            // panggil route kirim
            type: "POST",
            // kirimkan data dari #form_data
            data: new FormData(this),
            // aku butuh 2 baris kode berikut
            processData: false,
            contentType: false,
            // sebelum kirim, hapus validasi error dulu
            // sebelum kirim, jalankan fungsi berikut
            beforeSend: function() {
                // panggil .input lalu hapus .is-invalid
                $(".input").removeClass("is-invalid");
                // panggil .pesan_error lalu kosongkan textnya
                $(".pesan_error").text("");
            }
        })
        // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil 
        .done(function(resp) {
            // jika validasi menemukan error
            // jika resp.status sama dengan 0
            if (resp.status === 0) {
                // lakukan pengulangan
                // key berisi semua nilai name.
                // value berisi array yang menyimpan semua pesan error
                $.each(resp.errors, function(key, value) {
                    // contohnya panggil .nama_penyuplai_input lalu tambah class is-invalid
                    $(`.${key}_input`).addClass("is-invalid");
                    // contohnya panggil .nama_penyuplai_error lalu isi textnya dengan pesan error
                    $(`.${key}_error`).text(value[0]);
                });
            }
            // lain jika user memasukkan nomor telepon indonesia yang salah
            // lain jika resp.message sama dengan 'Tolong masukkan nomor handphone indonesia yang benar.'
            else if (resp.message === 'Tolong masukkan nomor handphone indonesia yang benar.') {
                // pangil #telepon_member lalu tambah class is-invalid
                $("#telepon_penyuplai").addClass('is-invalid');
                // panggil .telepon_penyuplai_error lalu text nya di isi pesan berikut
                $('.telepon_penyuplai_error').text('Tolong masukkan nomor handphone indonesia yang benar.');
            }
            // jika berhasil menyimpan penyuplai
            // lain jika resp.status sama dengan 200
            else if (resp.status === 200) {
                // // reset formulir
                // panggil #form_tambah index ke 0 lalu atur ulang passwordnya
                $("#form_tambah")[0].reset();
                // nama penyuplai di focuskan
                // panggil #nama_penyuplai lalu focuskan
                $("#nama_penyuplai").focus();
                // muat ulang table ajax
                // panggil variabel table lalu muat ulang ajaxnya
                table.ajax.reload();
                // notifikasi
                // panggil toastr tipe sukses dan tampilkan pesannya
                toastr.success(`${resp.pesan}.`);
            };
        });
    });

    // Edit penyuplai
    // jika document di clik yang .nya adalah tombol_edit maka jalankan fungsi berikut
    $(document).on("click", ".tombol_edit", function(e) {
        // cegah bawaan nya yaitu reload
        e.preventDefault();
        // panggil .tombol_edit lalu ambil value attribute data-id
        let penyuplai_id = $(this).attr('data-id');
        // lakukan ajax
        $.ajax({
                // ke method show
                // panggil url penyuplai, lalu kirim penyuplai_id
                url: "/penyuplai/" + penyuplai_id,
                // panggil route tipe dapatkan
                type: "GET"
            })
            // jika selesai dan berhasil maka jalankan fungsi berikut
            .done(function(resp) {
                // tampilkan modal edit penyuplai
                // panggil #modal_edit lalu tampilkan modal
                $("#modal_edit").modal("show");
                // isi data di formulir edit penyuplai
                $("#edit_penyuplai_id").val(resp.detail_penyuplai.penyuplai_id);
                $("#edit_nama_penyuplai").val(resp.detail_penyuplai.nama_penyuplai);
                $("#edit_telepon_penyuplai").val(resp.detail_penyuplai.telepon_penyuplai);
                $("#edit_alamat_penyuplai").val(resp.detail_penyuplai.alamat_penyuplai);
            });

    });

    // Update penyuplai
    // jika #form_edit di kirim maka jalankan fungsi berikut dam ambil acaranya
    $("#form_edit").on("submit", function(e) {
        // cegah bawaannya yaitu reload
        e.preventDefault();

        // panggil #e_penyuplai_id lalu ambil valuenya
        let penyuplai_id = $("#edit_penyuplai_id").val();
        // lakukan ajax
        $.ajax({
            // ke method update
            // ke url penyuplai lalu kirimkan penyuplai_id
            url: `/penyuplai/${penyuplai_id}`,
            // tipe route sudah aku ubah menjadi PUT di formulir
            type: "POST",
            // kirimkan data berupa formulir data dari #form_edit
            data: new FormData(this),
            // aku butuh ketiga baris kode dibawah ini
            processData: false,
            contentType: false,
            cache: false,
            // sebelum kirim, hapus vaidasi error
            // sebelum kirim, jalankan fungsi berikut
            beforeSend: function() {
                // panggil .e_input lalu hapus class is-invalid
                $(".input").removeClass("is-invalid");
                // panggil .pesan_error lalu kosongkan textnya
                $(".pesan_error").text("");
            }
        })
        // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapannya
        .done(function(resp) {
            // jika validasi error
            // jika tanggapan.statsu sama dengan 0
            if (resp.status === 0) {
                // lakukan pengulangan
                // key berisi semua value attribute name yang error
                // value berisi semua pesan error
                $.each(resp.errors, function(key, value) {
                    // contohnya pangil .e_nama_penyuplai_input
                    $(`.${key}_input`).addClass('is-invalid');
                    // contohnya panggil .e_nama_penyuplai_error lalu textnya diisi dengan pesan error
                    $(`.${key}_error`).text(value);
                });
            }
            // lain jika user memasukkan nomor telepon indonesia yang salah
            // lain jika resp.message sama dengan 'Tolong masukkan nomor handphone indonesia yang benar.'
            else if (resp.message === 'Tolong masukkan nomor handphone indonesia yang benar.') {
                // pangil #telepon_member lalu tambah class is-invalid
                $("#edit_telepon_penyuplai").addClass('is-invalid');
                // panggil .telepon_penyuplai_error lalu text nya di isi pesan berikut
                $('.telepon_penyuplai_error').text('Tolong masukkan nomor handphone indonesia yang benar.');
            } 
            // jika berhasil memperbarui penyuplai
            // lain jika resp.status sama dengan 200
            else if (resp.status === 200) {
                // tutup modal
                // panggil #modal_edit lalu tutup modal
                $("#modal_edit").modal("hide");
                // notifikasi menggunakan toastr AdminLTE
                toastr.success(`${resp.pesan}`);
                // muat ulang ajax pada table
                // panggil vairable table lalu muat ulang ajax
                table.ajax.reload();
            };
        });
    });

    // pilih semua
    // jika #select_all di click maka jalankan fungsi berikut
    $("#select_all").on("click", function() {
        // jika #select_all di centang maka
        if ($("#select_all").prop("checked")) {
            // panggil .pilih lalu centang nya benar
            $(".pilih").prop("checked", true);
        // lain jika #select_alll tidak dicentang
        } else {
            // panggil .pilih lalu centang nya false
            $(".pilih").prop("checked", false);
        };
    });



    // Delete
    // Jika #tombol_hapus di click maka jalankan fungsi berikut dan ambil tanggapan
    $("#tombol_hapus").on("click", function(e) {
        // jika input.pilih yang dicentang lebih besar dari 0 berarti ada yang dicentang
        if ($("input.pilih:checked").length > 0) {
            // tampilkan konfirmasi menggunakan sweetalert
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Anda akan menghapus penyuplai dan produk penyuplai yang terkait!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                // jika hasilnya di konfrimasi
                if (result.isConfirmed) {
                    // alasan menggunakan syntax ini adalah karena input name berisi penyuplai_id[]
                    // .serialize akan mengirimkan semua data pada table karena table disimpan di dalam form
                    // lakukan ajax tipe post, ke url /penyuplai/hapus-terpilih, kirimkan data dari .form_penyuplai yang berupa table
                    $.post('/penyuplai/destroy', $('.form_penyuplai').serialize())
                        // jika selesai dan berhasil maka jalankan fungsi dan ambil tanggapannya
                        .done(function(resp) {
                            // notifkasi menggunakan sweetalert
                            Swal.fire(
                                'Dihapus!',
                                'Berhasil menghapus penyuplai yang dipilih.',
                                'success'
                            );
                            // reload ajax table
                            // panggil variable table lalu ajax nya di muat ulang
                            table.ajax.reload();
                        });
                };
            });
        } else {
            Swal.fire('Anda belum memilih baris data');
        };
    });
</script>
@endpush
