// jika #form_retur_pembelian dikirim maka jalankan fungsi berikut dan ambil event atau acara nya
$("#form_retur_pembelian").on("submit", function(e) {
    // event cegah bawaan nya
    e.preventDefault();
    // lakukan ajax
    $.ajax({
        // url memanggil /pembelian/retur/ lalu kirimkan pembelian_id
        url: `/pembelian/retur/${pembelian_id}`,
        // panggil route tipe kirim
        type: "POST",
        // kirimkan data formulir dari #form_retur_pembelian
        // data: baru FormulirData("#form_retur_pembelian")
        data: new FormData(this),
        processData: false,
        contentType: false,
        cache: false
    })
});