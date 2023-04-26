// jika #tombol_tutup_retur_pembelian di click maka jalankan fungsi berikut
$(".tombol_tutup_retur_pembelian").on("click", function() {
    // panggil #tbody_return_pembelian lalu kosongkan semua anaknya
    $("#tbody_retur_pembelian").empty();
});


// fungsi retur_pembelian agar aku bisa retur_pembelian atau mengembalikkan pembelian
// function retur_pembelian berisi parameter pembelian_id yang menangkap pembelian_id dari controller
function retur_pembelian(pembelian_id) {
    // jquery lakukan ajax
    $.ajax({
        // url panggil /pembelian/data-retur/ kirimkan pembelian_id
        url: `/pembelian/data-retur/${pembelian_id}`,
        // panggil route tipe dapatkan
        type: "GET",
    })
        // jika selesai dan berhasil maka jalankan fungsi berikut lalu ambil tanggapan
        // parameter response berisi semua pembelian detail terkait
        // misalnya column pembelian_id berisi 1 maka ambil semua pembelian detail yang column pembelian_id berisi 1
        .done(function(response) {
            // aku butuh variable hasil untuk menyimpan banyak tr, jadi pada awal nya variabel hasil berisi string kosong, setelah di looping maka variable hasil akan digabung dengan element tr
            let hasil = ``;

            // lakukan pengulangan terhadap response yang berisi semua pembelian detail terkait
            // tanggapan.untukSetiap(fungsi(barang, index))
            // parameter item berisi data table pembelian detail maksudnya semua detail pembelian_detail terkait
            // parameter index berisi index nya misalnya index 0, index 1
            response.forEach(function(item, index) {
                // lakukan pengulangan terhadap tr atau table rows atau table baris
                // panggil variable hasil lalu tambahkan element tr berulang kali ke dalam variable hasil
                hasil += `
                    <tr>
                        <td>${index + 1}</td>    
                        <td>${item.nama_produk}</td>    
                        <td>
                            <input name="jumlah_retur" type="number" class="form-control" value="${item.jumlah}" max="${item.jumlah}" data-produk-penyuplai-id="${item.produk_penyuplai_id}">
                        </td> 
                        <td>
                            <input name="keterangan" type="text" class="form-control" autocomplete="off">
                        </td>
                    </tr>
                `;
            });
            
            // panggil #tbody_retur_pembelian lalu tambahkan value variable data_retur_pembelian_detail sebagai anak terakhir
            $("#tbody_retur_pembelian").append(hasil);

            // panggil #modal_retur lalu modal nya di tampilkan
            $("#modal_retur").modal('show');

            hasil_pembelian_id = pembelian_id;
        });
};

// jika #form_retur_pembelian dikirim maka jalankan fungsi berikut dan ambil event atau acara nya
$("#form_retur_pembelian").on("submit", function(e) {
    // event cegah bawaan nya
    e.preventDefault();

    // console.log(hasil_pembel ian_id);
    // lakukan ajax
    $.ajax({
        // url memanggil /pembelian/retur/ lalu kirimkan pembelian_id
        url: `/pembelian/retur/${hasil_pembelian_id}`,
        // panggil route tipe kirim
        type: "POST",
        // kirimkan data formulir dari #form_retur_pembelian
        // data: baru FormulirData("#form_retur_pembelian")
        data: new FormData(this),
        processData: false,
        contentType: false,
        cache: false
    })
        // jika selesai dan berhasil maka ambil tanggapannya
        .done(function(response) {
            $("#modal_retur").modal('hide');
            // variabel table.ajax.muatuLANG
            table.ajax.reload();
        })
});