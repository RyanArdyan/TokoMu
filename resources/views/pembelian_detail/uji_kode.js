// fitur pilih produk penyuplai
    // jika document di click yang class nya adalah .pilih_produk_penyuplai, jalankan fungsi berikut
    $(document).on('click', '.pilih_produk_penyuplai', function() {
        // berisi panggil value dari .pilih_produk_penyuplai, attribute data-produk-penyuplai-id
        let produk_penyuplai_id = $(this).data('produk-penyuplai-id');
        // berisi panggil value dari .pilih_produk_penyuplai, attribute data-harga
        let harga = $(this).data('harga');
        // cetak value produk_penyuplai_id di menu console
        // console.log(produk_penyuplai_id, harga);

        // berisi ambil nilai input #pembelian_id yang disimpan form_produk_penyuplai.blade
        let pembelian_id = $("#pembelian_id").val();
        // panggil #produk_penyuplai_id yang disimpan di form_produk_penyuplai.blade diisi dengan variable produk_penyuplai_id
        $('#produk_penyuplai_id').val(produk_penyuplai_id);

        // sembunyikan modal produk_penyuplai
        // panggil #modal_produk_penyuplai lalu modal nya di tutup
        $(`#modal_produk_penyuplai`).modal('hide');
        // lakukan ajax tipe kirim, panggil route pembelian_detail.store, kirimkan semua value input milik #form_produk_penyuplai, membuat cerita bersambung
        $.post("{{ route('pembelian_detail.store') }}", $('#form_produk_penyuplai').serialize())
            // jika selesai, maka jalankan fungsi berikut dan ambil tanggapanya
            .done(function(response) {
                alert('Sudah benar');
                // reload ajax pada table
                // table.ajax.reload();
            })
            .fail(function(errors) {
                Swal.fire('Tidak Dapat Menyimpan Data Karena Code Error!');
                return;
            });  
    });