<?php

// misalnya ada angka 1000 maka akan menjadi 1.000
function angka_bentuk($angka)
{
    return number_format($angka, 0, ",", ".");
};

// misalnya ada 1000 maka akan menjadi Rp 1.000
function rupiah_bentuk($angka)
{
    return "Rp " . number_format($angka, 0, ",", ".");
};

// menggunakan package angka terbilang milik rizkihajar
function terbilang($angka)
{
    return Terbilang::make($angka, " rupiah", "Senilai");
};

// membuat kode otomatis yang berurutan, misalnya jika kode produk P-00001 dihapus lalu saya menambahkan produk baru maka kode produknya adalah P-00001 bukan P-00002
function kode_berurutan($nilai, $jumlah_nol_diawal = 5)
{
    // saya akan mengisi $jumlah_nol_diawal dengan 5 ketika memanggil fungsinya
    return sprintf("%0" . $jumlah_nol_diawal . "s", $nilai);
};

function tambah_nol_didepan($value, $threshold = null)
{
    return sprintf("%0" . $threshold . "s", $value);
}

function tanggal_indonesia($tgl, $tampil_hari = true)
{
    $nama_hari  = array(
        'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu'
    );
    $nama_bulan = array(
        1 =>
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );

    $tahun   = substr($tgl, 0, 4);
    $bulan   = $nama_bulan[(int) substr($tgl, 5, 2)];
    $tanggal = substr($tgl, 8, 2);
    $text    = '';

    if ($tampil_hari) {
        $urutan_hari = date('w', mktime(0, 0, 0, substr($tgl, 5, 2), $tanggal, $tahun));
        $hari        = $nama_hari[$urutan_hari];
        $text       .= "$hari, $tanggal $bulan $tahun";
    } else {
        $text       .= "$tanggal $bulan $tahun";
    }

    return $text;
}
