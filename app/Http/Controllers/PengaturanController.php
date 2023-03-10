<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaturan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PengaturanController extends Controller
{
    // tampilkan halaman edit profile perusahaan
    public function index()
    {
        // ambil baris pertama dari Detail Pengaturan 
        $detail_pengaturan = Pengaturan::first();
        // kembalikan ke tampilan Pengaturan.index lalu kirimkan data
        return view('pengaturan.index', ['detail_pengaturan' => $detail_pengaturan]);
    }

    // $request berisi data-data formulir atau lebih tepatnya data-data input dari attribute name
    public function update(Request $request)
    {
        // detail Pengaturan
        // ambil detail Pengaturan baris pertama
        $detail_pengaturan = Pengaturan::first();

        // validasi
        // buat validassi untuk semua input yg punya attribute name
        $validator = Validator::make($request->all(), [
            // input name_perusahaan itu wajib, string, min:3, max:20
            'nama_perusahaan' => ['required', 'string', 'min:3', 'max:20'],
            'alamat_perusahaan' => ['required', 'string', 'max:255'],
            // minimal nomor indo adalah 10 dan max adalah 13
            'telepon_perusahaan' => ['required', 'string', 'min:10', 'max:13'],
            'diskon_perusahaan' => ['required', 'integer', 'min:0', 'max:100'],
            // max 600 kb
            'logo_perusahaan' => 'image|max:600',
            'kartu_member' => 'image|max:600'
        ]);

        // jika validasi gagal
        if ($validator->fails()) {
            // kembalikkan response berupa json
            return response()->json([
                // key status berisi 0
                'status' => 0,
                // key errors berisi semua value attribute yang error dan pesan errornya
                'errors' => $validator->errors()->toArray()
            ]);
        // jika validasi berhasil
        } else {
            // jika Srequest memiliki logo_perusahaan atqu jika user mengubah gambarnya lalu click submit maka hapus gambar lama lalu upload gambar baru
            if ($request->hasFile('logo_perusahaan')) {
                // hapus logo_perusahaan lama
                // panggil storage/app/public/gambar_pengaturan
                Storage::delete('public/gambar_pengaturan/' . $detail_pengaturan->logo_perusahaan);
                // nama logo_perusahaan, contonhya logo_perusahaan.png
                $nama_logo_perusahaan = "logo_perusahaan" . '.' . $request->file('logo_perusahaan')->extension();
                // upload logo_perusahaan
                // penyimpanan letakkan file di storage/app/publi/gambar_pengaturan
                // argumen kedua adalah input logo_perusahaan
                // argumen ketiga adalah namanaya
                Storage::putFileAs('public/gambar_pengaturan/', $request->file('logo_perusahaan'), $nama_logo_perusahaan);
            // jika user tidak mengupload mengubah gambar logo perusahaan
            } else {
                // panggil detail Pengaturan lalu column logo_perusahaan
                $nama_logo_perusahaan = $detail_pengaturan->logo_perusahaan;
            };

            // jika Srequest memiliki kartu_member atqu jika user mengubah kartu membernya lalu click submit maka hapus gambar lama lalu upload gambar baru
            if ($request->hasFile('kartu_member')) {
                // hapus kartu_member lama
                // panggil storage/public/gambar_pengaturan/
                Storage::delete('public/gambar_pengaturan/' . $detail_pengaturan->kartu_member);
                // nama kartu_member, contohnya kartu_member.png
                $nama_kartu_member = "kartu_member" . '.' . $request->file('kartu_member')->extension();
                // upload kartu_member
                // penyimpanan letakkan filenya di storage/app/public/gambar_pengaturan
                // argumen kedua adalah file gambarnya
                // argumen ketiga adalah nama gambar baru nya
                Storage::putFileAs('public/gambar_pengaturan/', $request->file('kartu_member'), $nama_kartu_member);
            // jika user tidak mengupload mengubah gambarnya
            } else {
                // panggil detail Pengaturan lalu column kartu_member
                $nama_kartu_member = $detail_pengaturan->kartu_member;
            };

            // perbarui table Pengaturan
            // column nama_perusahaan di table pengaturan diisi dengan input nama_perusahaan
            $detail_pengaturan->nama_perusahaan = $request->nama_perusahaan;
            $detail_pengaturan->alamat_perusahaan = $request->alamat_perusahaan;
            $detail_pengaturan->telepon_perusahaan = $request->telepon_perusahaan;
            $detail_pengaturan->tipe_nota_perusahaan = $request->tipe_nota_perusahaan;
            $detail_pengaturan->diskon_perusahaan = $request->diskon_perusahaan;
            $detail_pengaturan->logo_perusahaan = $nama_logo_perusahaan;
            $detail_pengaturan->kartu_member = $nama_kartu_member;
            $detail_pengaturan->save();

            // kembalikkan tanggapan berupa json
            return response()->json([
                // key status berisi value 200
                'status' => 200
            ]);
        };
    }

}
