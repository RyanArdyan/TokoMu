// panggil ajax tipe dapatkan, panggil url /pembelian-detail/reload-form/ lalu kirimkan value variable total_harga
$.get(`{{ url('/pembelian-detail/reload-form') }}/${total_harga}`)
// jika berhasil maka jalankan fungsi berikut lalu ambil tanggapan
.done(response => {
    // #total_rp di form_pembelian.blade lalu diisi dengan response.total_rp
    $("#total_harga").val(response.total_harga);
    // panggil #total_pembayaran di pembelian_detail.index lalu text nya diisi dengan response.bayar_rp
    $("#total_pembayaran").text(response.bayar_rp);
    $("#total_pembayaran_format_terbilang").text(response.terbilang);
})
// jika gagal maka jalankan fungsi berikut lalu ambil errorsnya
.fail(errors => {
    // tampilkan alert yang berisi pesan berikut
    alert('Tidak Dapat Menampilkan Data karena kode error.');
    // selesai
    return;
});