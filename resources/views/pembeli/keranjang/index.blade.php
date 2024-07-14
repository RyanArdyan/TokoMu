{{-- memperluas parentnya yaitu layouts.app --}}
@extends('layouts.app')

{{-- dorong css lalu tangkap menggunakan $stack('css') --}}
@push('css')
<style>
    .kurangi_lebar_input {
        width: 5%;
    }

    .tebal_hitam_hr {
        border: 2px solid black;
    }
</style>
@endpush

{{-- kirim value section title ke @Yield('title') --}}
@section('title', 'Keranjang')

{{-- kirim value section konten ke @yield('konten') --}}
@section('konten')
    {{-- lakukan pengulangan menggunakan foreach --}}
    {{-- @untuksetiap($semua_data_keranjang sebagai $keranjang) --}}
    @foreach ($semua_data_keranjang as $keranjang)
            <h4 class="card-title">{{ $keranjang->produk->nama_produk }}</h4>
            <p class="card-text">{{ rupiah_bentuk($keranjang->produk->harga_jual) }}</p>
            <button>-</button>
            {{-- bikin input --}}
            <input class="kurangi_lebar_input input_jumlah_{{ $keranjang->keranjang_id }}" width="10px" type="number" value="1">
            <button class="tombol_tambah_jumlah" data-keranjang-id="{{ $keranjang->keranjang_id }}">+</button>
            <br>
            <a href="#" class="btn btn-primary my-2">Beli</a>
            <hr class="tebal_hitam_hr">
    @endforeach

@endsection

@push('script')
    <script>
        // jika modal tambah dikirim
        // jika form tambah dikirim jalankan fungsi berikut dan ambil event atau acara nya
        $("#form_tambah").on("submit", function(e) {
            // cegah bawaan nya yaitu reload
            e.preventDefault();
            // lakukan ajax
            $.ajax({
                    // url ke route produk.store
                    url: "{{ route('produk.store') }}",
                    // panggil route type POST
                    type: "POST",
                    // kirimkan data berupa baru FormulirData dari #form_tambah
                    data: new FormData(this),
                    // aku butuh 3 baris kode dibawah ini
                    processData: false,
                    contentType: false,
                    cache: false,
                    // sebelum formnya dikirim, hapus validsai error dulu
                    beforeSend: function() {
                        // panggil .input hapus class is-invalid
                        $(".input").removeClass("is-invalid");
                        // panggil .pesan_error lalu kosongkan textnya
                        $(".pesan_error").text("");
                    }
                })
                // jika selesai dan berhasil maka jalankan fungsi berikut dan ambil tanggapnnya
                .done(function(resp) {
                    // jika validasi menemukan error
                    // jika tanggapan.status sama dengan 0
                    if (resp.status === 0) {
                        // lakukan pengulangan terhadap value attribute name dan pesan errornya
                        // key berisi semua value attribute name
                        // value berisi semua pesan error
                        $.each(resp.errors, function(key, value) {
                            // contohnya panggil .nama_produk_input lalu tambah .is-invalid
                            $(`.${key}_input`).addClass("is-invalid");
                            // anggaplah panggil .nama_produk_error lalu textnya diisi dengan pesan value index 0 atau pesan error
                            $(`.${key}_error`).text(value[0]);
                        });
                    }
                    // jika produk berhasil disimpan
                    // lain jika tanggapan.status sama dengan 200
                    else if (resp.status === 200) {
                        // reset formulir atau kosongkan semua value element input
                        // panggil #form_tambah, index 0, lalu atur ulang
                        $("#form_tambah")[0].reset();
                        // input nama produk di focuskan
                        // panggil #nama_produk lalu fokuskan
                        $("#nama_produk").focus();
                        // muat ulang table ajax
                        // panggil variabel table, ajaxnya kita reload
                        table.ajax.reload();
                        // notifikasi menggunakan toastr
                        toastr.success(`${resp.pesan}.`);
                    };
                });
        });


        // jika document di click yang class nya adalah tombol_tambah_jumlah
        $(document).on("click", ".tombol_tambah_jumlah", function() {
            // kayaknya endak butuh mengambil value input_jumlah lah aku pake let angka saja

            // panggil value attribute data-keranjang-id
            let keranjang_id = $(this).data("keranjang-id");
            // panggil class input_jumlah_ lalu digabung dengan value variable keranjang_id untuk memanggil value input jumlah yang sesuai
            let input_jumlah = Number($(`.input_jumlah_${keranjang_id}`).val());
            // panggil value variable input_jumlah lalu ditambah sama dengan 1
            input_jumlah = input_jumlah + 1;
            // panggil .input_jumlah_keranjang_id lalu ubah value nya menjadi input_jumlah
            $(`.input_jumlah_${keranjang_id}`).val(input_jumlah);
        });
    </script>
@endpush
