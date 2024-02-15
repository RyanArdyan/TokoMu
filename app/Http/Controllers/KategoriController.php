<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Kategori;

class KategoriController extends Controller
{
    /**
     * Menampilkan data kategori
     *
     * @return \Illuminate\Http\Response
     */
    // aku membutuhkan $reqeust untuk mengambil permintaan ajax
    public function index(Request $request)
    {
        // jika script memninta data kategori lewat permintaan ajax
        // jika $pemintaan memiliki ajax atau ini untuk read atau mengambil data kategori
        if ($request->ajax()) {
            // ambil semua kategori
            // Models/Kategori pilih value column kategori_id, nama_kategori dan di updated_at, ambil dari urutan Z ke A.
            $semua_kategori = Kategori::select('kategori_id', 'nama_kategori', 'updated_at')->latest()->get();

            // buat elemnt table di dalam $data
            // kita akan menjahit menggunakan tanda .
            $data = '
                <table class="table">
                <thead class="bg-primary">
                    <tr>
                        <th>
                            <input type="checkbox" name="select_all" id="pilih_semua">
                        </th>
                        <th scope="col" width="5%">No</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Diperbarui Pada</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>';

            // untuk pengulangan nomor
            $nomor = 1;
            // lakukan pengulangan
            // $detail_kategori berisi semua detail kategori
            foreach ($semua_kategori as $detail_kategori) {
                // .= berarti menyambung data
                // cetak value detail_kategori, column kategori_id, dan kawan-kawan.
                // aku menggunakan iso format agar mudah di baca manusia
                $data .=
                    '<tr>
                        <td width="5%">
                            <input name="kategori_ids[]" value="' . $detail_kategori->kategori_id . '" class="pilih form-check-input mx-auto" type="checkbox">
                        </td>
                        <th>' . $nomor++ . '</th>
                        <td>' . $detail_kategori->nama_kategori . '</td>
                        <td>' . $detail_kategori->updated_at->isoFormat('dddd, D MMMM Y') . '</td>
                        <td>
                            <button data-id="' . $detail_kategori->kategori_id . '" class="tombol_edit btn btn-sm btn-warning">
                                <i class="fas fa-pencil-alt"></i>
                                Edit
                            </button>
                        </td>
                    </tr>';
            };

            // panggil value variable data lalu digabung dengan element html
            $data .= '
                </tbody>
                </table>';

            // kembalikkan tanggapan berupa json dari variable $data
            return response()->json($data);
        };

        // kembalikan ke tampilan kategori.index
        return view('kategori.index');
    }

    /**
     * Simpan kategori
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // $request akan mengambil value dari attribute name
    public function store(Request $request)
    {
        // lakukan validasi terhadap semua input yang memiliki attribute name
        $validator = Validator::make($request->all(), [
            // input name nama_kategori berisi aturan berikut
            'nama_kategori' => 'required|string|unique:kategori|min:2|max:20',
            'deskripsi_kategori' => 'required|string|max:255'
        ]);

        // jika validasi gagal
        if ($validator->fails()) {
            // kembalikan tangapan berupa json
            return response()->json([
                // key status berisi value 0
                'status' => 0,
                // key pesan berisi value berikut
                'message' => 'Validasi Menemukan Errors',
                // key errors berisi semua value attribute name yang error dan pesannya
                'errors' => $validator->errors()
            ]);
        } else {
            // Simpan kategori
            // Kategori buat
            Kategori::create([
                // column nama_kategori milik table kategori diisi dengan input name nama_kategori
                'nama_kategori' => $request->nama_kategori,
                'deskripsi_kategori' => $request->deskripsi_kategori
            ]);

            // kembalikkan tanggapan berupa json
            return response()->json([
                // key status berisi value 200
                'status' => 200,
                // key message berisi pesan berikut
                'message' => "Kategori $request->nama_kategori berhasil disimpan",
                // aku butuh ini untuk menampilkan pesan "Kategori sembako berhasil disimpan"
                'nama_kategori' => $request->nama_kategori
            ]);
        };
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // $kategori_id menangkap kategori_id yang dikirim lewat url
    public function show($kategori_id)
    {
        // ambil detail kategori berdasarkan kategori_id
        $detail_kategori = Kategori::where('kategori_id', $kategori_id)->first();
        // kembalikkan tanggapan detail_kategori berupa json
        return response()->json($detail_kategori);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // $request berisi semua value attribute name
    public function update(Request $request)
    {
        // detail kategori
        // ambil detail kategori berdasarkan kategori_id yang dikirimkan
        $detail_kategori = Kategori::where('kategori_id', $request->kategori_id)->first();

        // jika nilai input nama_kategori sama dengan nilai column nama_kategori
        if ($request->nama_kategori === $detail_kategori->nama_kategori) {
            // validasi nama kategori berisi aturan berikut
            $validasi_nama_kategori = 'required|string|min:2|max:20';
        // lain jika input nama_kategori tidak sama dengan nilai column nama kategori milik table kategori
        } else if ($request->nama_kategori !== $detail_kategori->nama_kategori) {
            $validasi_nama_kategori = 'required|string|min:2|max:20|unique:kategori';
        };

        // validasi
        // validasi semua semua input yg punya attribute name
        $validator = Validator::make($request->all(), [
            // input name="nama_kategori" berisi aturan berikut
            'nama_kategori' => $validasi_nama_kategori,
            'deskripsi_kategori' => 'required|string|max:255'
        ], [
            'nama_kategori.unique' => 'Kategori sudah ada',
        ]);

        // jika validasi gagal
        if ($validator->fails()) {
            // kembalikkan tanggapan berupa json
            return response()->json([
                // key status berisi value 0
                'status' => 0,
                // key errros berisi semua value attribute name yang error dan pesan errornya
                'errors' => $validator->errors()
            ]);
        // jika validasi berhasil
        } else {
            // perbarui Kategori
            // panggil $detail_kategori lalu perbarui column nama_kategori menggunakan input nama_kategori
            $detail_kategori->nama_kategori = $request->nama_kategori;
            $detail_kategori->deskripsi_kategori = $request->deskripsi_kategori;
            $detail_kategori->save();


            return response()->json([
                'status' => 200,
                'pesan' => "Kategori $request->nama_kategori berhasil diperbarui.",
            ]);
        };
    }

    // hapus kategori yang dipilih
    // $request berisi semua_kategori_id, contoh ["1", "2"]
    public function destroy(Request $request)
    {
        // hapus beberapa kategori
        // table kategori dimana didalam column kategori_id berisi array yang di dalamnya berisi $kategori->kategori_id, hapus beberapa data
        Kategori::whereIn('kategori_id', $request->semua_kategori_id)->delete();

        // kembalikkan tanggapan berupa json
        return response()->json([
            // key status berisi 200
            'status' => 200,
            // kry succcess berisi value berikut
            'success' => "Berhasil menghapus kategori yang dipilih.",
        ]);
    }
}
