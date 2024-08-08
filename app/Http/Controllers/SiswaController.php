<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Carbon\Carbon;
use App\Models\Siswa;

class SiswaController extends Controller
{
    public function index()
    {
        return view('siswa.index');
    }

    // menampilkan daftar siswa
    public function read(Request $request)
    {
        $semua_siswa = Siswa::select('siswa_id', 'nama', 'usia', 'alamat', 'jurusan', 'gender')->get();
        return DataTables::of($semua_siswa)
            ->addIndexColumn()
            ->addColumn('select', function(Siswa $siswa) {
                return '
                        <input name="siswa_ids[]" value="' . $siswa->siswa_id . '" class="pilih select form-check-input mx-auto" type="checkbox">
                ';
            })
            ->addColumn('action', function(Siswa $siswa) {
                // attribute data-id digunakan untuk menyimpan value detail siswa, column siswa_id
                $btn = '
                    <button data-id="' . $siswa->siswa_id . '" class="tombol_edit btn btn-warning btn-sm">
                        <i class="fas fa-pencil-alt"></i> Edit
                    </button>
                ';
                return $btn;
            })
        ->rawColumns(['select', 'action'])
        ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|unique:siswa|max:20|min:2',
            'usia' => 'required',
            'alamat' => 'required',
            'jurusan' => 'required',
            'gender' => 'required',
        ], [
            'nama.unique' => 'Nama ini sudah digunakan orang lain',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'pesan' => 'Error',
                'errors' => $validator->errors()
            ]);
        // lain jika validasi berhasil
        } else {
            siswa::create([
                // call the siswa_name column of the siswa table filled with the attribute value name="siswa_name"
                'nama' => $request->nama,
                'usia' => $request->usia,
                'alamat' => $request->alamat,
                'jurusan' => $request->jurusan,
                'gender' => $request->gender,
            ]);
            return response()->json([
                'status' => 200,
                'pesan' => "siswa $request->nama berhasil disimpan.",
            ]);
        };
    }

    public function show($siswa_id)
    {
        $detail_siswa = siswa::where('siswa_id', $siswa_id)->first();
        return response()->json([
            'siswa_id' => $siswa_id,
            'detail_siswa' => $detail_siswa,
        ]);
    }

    public function update(Request $request, $siswa_id)
    {
        // detail siswa
        // ambil detail siswa berdasarkan siswa_id yang dikirimkna lewat url
        $detail_siswa = Siswa::where('siswa_id', $siswa_id)->first();

        // jika nilai input name="nama" sama dengan nilai column nama di table siswa
        if ($request->nama === $detail_siswa->nama) {
            // input name="nama" harus mengikuti aturan berikut
            $validasi_nama = 'required|string|min:2|max:20';
        }
        // lain jika value name="nama" tidak sama dengan value detail siswa, column nama milik table siswa
        else if($request->nama !== $detail_siswa->nama) {
            // input name="nama" harus mengikuti aturan berikut
            $validasi_nama = 'required|string|min:2|max:20|unique:siswa';
        };

        // validasi semua input yang punya attribute name
        // validator buat semua permintaan
        $validator = Validator::make($request->all(), [
            // input name="nama berisi atraun dari $validasi_nama
            'nama' => $validasi_nama,
            'usia' => ['required'],
            'alamat' => ['required'],
            'jurusan' => ['required'],
            'gender' => ['required']
        ],
        // timpa terjemahan unique milik lang/id
        [
            'nama.unique' => 'Nama siswa ini sudah digunakan orang lain',
        ]);

        // jika validasi gagal
        if ($validator->fails()) {
            // kembalikkan tanggapan berupa json lalu krimkan data
            return response()->json([
                // key status berisi value 0
                'status' => 0,
                // key errors berisi semua semua value atribute name yang error dan pesan errornya
                'errors' => $validator->errors()->toArray()
            ]);
        // jika validasi berhasil
        } else {

            // Perbarui siswa
            // panggil value column nama milik detai_siswa lalu diisi dengan value input name="nama"
            $detail_siswa->nama = $request->nama;
            $detail_siswa->usia = $request->usia;
            $detail_siswa->alamat = $request->alamat;
            $detail_siswa->jurusan = $request->jurusan;
            $detail_siswa->gender = $request->gender;
            // detail siswa, diperbarui
            $detail_siswa->update();

            // kembalikkan tanggapan berupa json lalu kirimkan data
            return response()->json([
                // key status berisi value 200
                'status' => 200,
                // key pesan berisi pesan berikut, misalnya siswa budi berhasil diperbarui
                'pesan' => "siswa $request->nama berhasil diperbarui.",
            ]);
        };
    }

    public function destroy(Request $request)
    {

        // hapus beberapa siswa berdasarkan beberapa siswa_id yang di kirimkan
        // siswa dimana dalam column siswa_id berisi value yang sama dengan siswa_ids maka hapus
        siswa::whereIn('siswa_id', $request->siswa_ids)->delete();


        return response()->json([
            'status' => 200,
            'pesan' => 'Berhasil menghapus siswa yang dipilih'
        ]);
    }
}
