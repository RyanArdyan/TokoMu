<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penyuplai;
use Illuminate\Support\Facades\Validator;
// package laravel datatables
use DataTables;

class PenyuplaiController extends Controller
{
    /**
     * K tampilakn penyuplai.index
     *
     * @return \Illuminate\Http\Response
     */
    // $reqeust berfunsi menangkap ajax
    public function index()
    {            
        // kembalikkan ke tampilan penyuplai/index
        return view('penyuplai.index');
    }

    // mengambil daftar data penyuplai
    public function read()
    {
        // ambil semua value column penyuplai_id, nama_penyuplai, telepon_penyuplai, alamat_penyuplai
        $semua_penyuplai = Penyuplai::select('penyuplai_id', 'nama_penyuplai', 'telepon_penyuplai', 'alamat_penyuplai')->latest()->get();
        // syntax punya yajra
        return DataTables::of($semua_penyuplai)
        ->addIndexColumn()
        // ulang detail penyuplai
        ->addColumn('select', function(Penyuplai $penyuplai) {
            return '
                    <input name="penyuplai_id[]" value="' . $penyuplai->penyuplai_id . '" class="pilih form-check-input mx-auto" type="checkbox">
            ';
        })
        // buat tombol  edit
        ->addColumn('action', function(Penyuplai $penyuplai) {
            // $btn berisi tombol edit
            $btn = '
                <button data-id="' . $penyuplai->penyuplai_id . '" class="tombol_edit btn btn-warning btn-sm">
                    <i class="fas fa-pencil-alt"></i> Edit
                </button>
            ';
            return $btn;
        })
        // jika column, membuat elemnt html maka harus dimasukkan ke rawColumns
        ->rawColumns(['select', 'action'])
        // buat nyata
        ->make(true);
    }

    /**
     * Simpan penyuplai
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // $request berisi semua value attribute name
    public function store(Request $request)
    {
        // validasi semua inout yang punya attribute name
        // berisi validator dibuat untuk semua permintaan
        $validator = Validator::make($request->all(), [
            // input name nama_penyuplai berisi aturan berikut
            'nama_penyuplai' => 'required|unique:penyuplai|max:50|min:3',
            // nomor telepon di di indoensia, minimalnya adalah 10 dan maksimalnya adalah 13
            'telepon_penyuplai' => 'required|unique:penyuplai|min:10|max:13',
            'alamat_penyuplai' => 'required|max:255|min:3'
        ], [
            'nama_penyuplai.unique' => 'Nama_penyuplai ini sudah digunakan orang lain',
            'telepon.unique' => 'Nomor telepon ini sudah digunakan orang lain.'
        ]);

        // buat validasi
        // jika validator gagal
        if ($validator->fails()) {
            // kembalikkan tanggapan berupa json
            return response()->json([
                // key status berisi 0
                'status' => 0,
                // key pesan berisi pesan berikut
                'pesan' => 'Validasi Menemukan Error',
                // key errors berisi semua value input dan pesan yang error
                'errors' => $validator->errors()
            ]);
        }
        // jika validasi berhasil
        else {            
            // jika validasi biasa menggunakan laravel sudah berhasil maka lakukan validasi terhadap input name="telepon_member" agar user memasukkan nomor handphone indonesia yang benar
            // nomor telepon indonesia diawali oleh 08, minimal 10 digit dan maksimal 13 digit
            $regex = '/^08[0-9]{8,11}$/';

            // Fungsi ini preg_match()akan memberi tahu Anda apakah suatu string berisi kecocokan suatu pola.
            // jika value input name="telepon_member" tidak sama dengan regex maka
            if(!preg_match($regex, $request->telepon_penyuplai)) {
                // nomor handphone tidak valid
                // kembalikkan tanggapan berupa json
                return response()->json([
                    // key status berisi value 422
                    'status' => 422,
                    // key message berisi value berikut
                    'message' => 'Tolong masukkan nomor handphone indonesia yang benar.'
                ]);
            };

            // Simpan penyuplai ke table penyuplai
            // penyuplai buat
            penyuplai::create([
                // column nama_penyuplai di table penyuplai diisi dengan value input name="nama_penyuplai"
                'nama_penyuplai' => $request->nama_penyuplai,
                'telepon_penyuplai' => $request->telepon_penyuplai,
                'alamat_penyuplai' => $request->alamat_penyuplai
            ]);
            // kembalikkan tanggapan berupa json
            return response()->json([
                // key status berisi 200
                'status' => 200,
                // key pesan berisi "Penyuplai PT Bisa berhasil disimpan."
                'pesan' => "Penyuplai $request->nama_penyuplai berhasil disimpan.",
            ]);
        };
    }

    /**
     * kembalikkan detail penyuplai ketika modal edit penyuplai dibuka
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($penyuplai_id)
    {
        // ambil detail penyuplai berdasarkan penyuplai_id yang dikirimkan
        $detail_penyuplai = penyuplai::where('penyuplai_id', $penyuplai_id)->first();
        // kembalikkan tanggapan berupa json
        return response()->json([
            // kirim 
            // panggil fungsi angka_bentuk milik helpers
            'detail_penyuplai' => $detail_penyuplai,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $penyuplai_id)
    {
        // detail penyuplai
        // ambil detail penyuplai berdasarkan penyuplai_id
        $detail_penyuplai = penyuplai::where('penyuplai_id', $penyuplai_id)->first();

        // jika nilai input name="nama_penyuplai" sama dengan nilai column nama_penyuplai pada table penyuplai
        if ($request->nama_penyuplai === $detail_penyuplai->nama_penyuplai) {
            // input name="nama_penyuplai" berisi aturan berikut
            $validasi_nama = 'required|string|min:3|max:50';
        // lain jika input name="nama_penyuplai" tidak sama dengan nilai column nama_penyuplai pada detail penyuplai
        } else if ($request->nama_penyuplai !== $detail_penyuplai->nama_penyuplai) {
            // input name="nama_penyuplai" berisi aturan berikut
            $validasi_nama = 'required|string|min:3|max:50|unique:penyuplai';
        };

        // jika nilai input telepon_penyuplai sama dengan nilai column telepon dari $detail_penyuplai
        if ($request->telepon_penyuplai === $detail_penyuplai->telepon_penyuplai) {
            // input name="telepon_penyuplai" berisi aturan berikut
            $validasi_telepon_penyuplai = 'required|min:10|max:13';
        // jika nilai input name="telepon_penyuplai" tidak sama dengan nilai column telepon_penyuplai dari detail_penyuplai
        } else if ($request->telepon_penyuplai !== $detail_penyuplai->telepon_penyuplai) {
            // input name="telepon_penyuplai" berisi aturan berikut
            $validasi_telepon_penyuplai = 'required|min:10|max:13|unique:penyuplai';
        };

        // buat validasi semua input yang memiliki  attribute name
        $validator = Validator::make($request->all(), [
            'nama_penyuplai' => $validasi_nama,
            'telepon_penyuplai' => $validasi_telepon_penyuplai,
            'alamat_penyuplai' => 'required|min:3|max:255'
        ], [
            'nama_penyuplai.unique' => 'Nama penyuplai ini sudah digunakan orang lain',
            'telepon_penyuplai.unique' => "Nomor telepon penyuplai ini sudah digunakan orang lain"
        ]);

        // buat validasi untuk semua input yang memiliki attribute name
        // jika validator gagal
        if ($validator->fails()) {
            // kembalikkan tanggapan berupa json
            return response()->json([
                // key status berisi value 0
                'status' => 0,
                // key errors berisi semua value attribute name dan pesan errornya
                'errors' => $validator->errors()->toArray()
            ]);
        // jika validasi berhasil
        } else {
            // jika validasi biasa menggunakan laravel sudah berhasil maka lakukan validasi terhadap input name="telepon_member" agar user memasukkan nomor handphone indonesia yang benar
            // nomor telepon indonesia diawali oleh 08, minimal 10 digit dan maksimal 13 digit
            $regex = '/^08[0-9]{8,11}$/';

            // Fungsi ini preg_match()akan memberi tahu Anda apakah suatu string berisi kecocokan suatu pola.
            // jika value input name="telepon_member" tidak sama dengan regex maka
            if(!preg_match($regex, $request->telepon_penyuplai)) {
                // nomor handphone tidak valid
                // kembalikkan tanggapan berupa json
                return response()->json([
                    // key status berisi value 422
                    'status' => 422,
                    // key message berisi value berikut
                    'message' => 'Tolong masukkan nomor handphone indonesia yang benar.'
                ]);
            };

            // Perbarui detail penyuplai
            // panggil detail penyuplai, column nama_penyuplai lalu diisi dengan value input name="nama_penyuplai"
            $detail_penyuplai->nama_penyuplai = $request->nama_penyuplai;
            $detail_penyuplai->telepon_penyuplai = $request->telepon_penyuplai;
            $detail_penyuplai->alamat_penyuplai = $request->alamat_penyuplai;
            // detail penyuplai diperbarui
            $detail_penyuplai->update();

            // kembalikkan tanggapan berupa json
            return response()->json([
                // key status berisi value 200
                'status' => 200,
                // key pesan berisi value berikut
                'pesan' => "Penyuplai $request->nama_penyuplai berhasil diperbarui.",
            ]);
        };
    }

    /**
     * Hapus penyuplai yang di centang
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // return $request->all();
        // lakukan pengulangan terhadap array name="penyuplai_id[]"
        foreach($request->penyuplai_id as $penyuplai_id) {
            $penyuplai = penyuplai::where('penyuplai_id', $penyuplai_id)->first();
            $penyuplai->delete();
        };

        // hapus beberapa penyuplai berdasarkan beberapa penyuplai_id yang dikirimkan
        // penyuplai di mana dalam column penyuplai_id, berisi array name="penyuplai_id" lalu hapus
        Penyuplai::whereIn('penyuplai_id', $request->penyuplai_id)->delete();

        // kembalikkan tangapan berupa json
        return response()->json([
            // key status berisi value 200
            'status' => 200,
            // key pesan berisi pesan berikut
            'pesan' => 'Berhasil menghapus penyuplai yang dipilih'
        ]);
    }
}
