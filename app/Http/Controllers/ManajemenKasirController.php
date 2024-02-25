<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// package laravel datatables
use DataTables;
use Illuminate\Support\Facades\Hash;
use App\Rules\GmailRule;
use App\Models\User;
use App\Models\Karyawan;

class ManajemenKasirController extends Controller
{
    /**
     * Menampilkan data kasir
     *
     * @return \Illuminate\Http\Response
     */
    // $request digunakan untuk mengambil permintaan ajax untuk mengambil data kasir
    public function index(Request $request)
    {
        // jika $request memiliki ajax
        if ($request->ajax()) {
            // syntax punya laravel
            // User pilih value column id, name, email berdasarkan column is_admin yang berisi 0 karena aku hanya akan menampilknan data-data kasir
            $semua_kasir = User::select('user_id', 'name', 'email')->where('is_admin', 0)->get();
            // syntax punya yajra
            return DataTables::of($semua_kasir)
                ->addIndexColumn()
                // ulang detail kasir
                // fitur hapus beberapa berdasarkan kotak centang yang di checklist
                ->addColumn('select_all', function(User $user) {
                    // id2x  akan masuk ke array user_id[]
                    // inputnya berisi table user, column name
                    return '
                            <input name="user_id[]" value="' . $user->user_id . '" class="pilih form-check-input mx-auto" type="checkbox">
                    ';
                })
            // jika column memmbuat tag html maka aku harus memasukkannya ke dalam rawColumns()
            ->rawColumns(['select_all'])
            // buat benar
            ->make(true);
        // lain jika $request tidak memiliki ajax atau jika aku belum membuat script untuk memanggil data kasir
        } else if (!$request->ajax()) {
            return view('manajemen_kasir.index');
        };

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // buat validasi secara manual
        $validator = Validator::make($request->all(), [
            // validasi input name name, email, password, password_confirmation
            'name' => ['required', 'string', 'unique:users', 'min:3', 'max:20'],
            // email akan memanggil file GmailRule untuk mempeluas validasi nya
            'email' => [new GmailRule, 'required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:6', 'max:20'],
            // value input password harus sama dengan input password_confirmation
            'password_confirmation' => ['required']
        ], [
            // terjemahan untuk name.unique
            'name.unique' => 'Name Ini Sudah digunakan orang lain'
        ]);

        // jika validator gagal
        if ($validator->fails()) {
            // return respone berupa json
            return response()->json([
                // key status berisi 0
                'status' => 0,
                'pesan' => 'Error',
                // errors akan berisi semua value attribute name dan pesan yang error
                'errors' => $validator->errors()
            ]);
        // jika validator berhasil atau lolos dari validasi
        } else {
            // Simpan user dengan cara memanggil Models/User
            // Models/User buat
            $detail_user = User::create([
                // column table berisi value attribute name
                // column name berisi name="name"
                'name' => $request->name,
                // column email, berisi name="email"
                'email' => $request->email,
                // column password, berisi input name="password" yang di hash
                'password' => Hash::make($request->password)
            ]);

            // jika $request->shift berisi "pagi" maka
            if ($request->shift === "pagi") {
                // $jam_masuk berisi "08:00:00"
                $jam_masuk = "08:00:00";
                $jam_keluar = "15:59:00";
            }
            // lain jika $request->shift berisi "malam" maka
            else if ($request->shift === "malam") {
                // $jam_masuk berisi "16:00:00"
                $jam_masuk = "16:00:00";
                $jam_keluar = "23:59:00";
            };


            // Simpan baris data baru ke table karyawan
            Karyawan::create([
                // column user_id berisi value detail_user, column user_id
                'user_id' => $detail_user->user_id,
                // column jam_masuk berisi value variable $jam_masuk
                'jam_masuk' => $jam_masuk,
                'jam_keluar' => $jam_keluar
            ]);

            // return response berupa json
            return response()->json([
                // key status berisi 200
                'status' => 200,
                // ini untuk memberitahu backend kalau user berhasil disimpan
                'pesan' => "user $request->name berhasil disimpan.",
            ]);
        };
    }

    /**
     * Remove the some resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // return $request->all();
        // aku melakukan pengulangan terhadap user_id yang didapatkan dari name="user_id" milih checkbox
        foreach($request->user_id as $user_id) {
            // ulangi penngambil detail user berdasarkan value column user_id
            // anggaplah $user_id berisi 1 berarti ambil detail user berdasarkan column user_id 1, lalu ambil detail user berdasarkan column user_id 2
            $detail_user = User::where('user_id', $user_id)->first();
            // ulangi penghapusan
            $detail_user->delete();
        };

        // kembalikkan response berupa json
        return response()->json([
            // key status berisi value 200
            'status' => 200,
            // key pesan berisi value
            'pesan' => 'Berhasil menghapus user yang dipilih'
        ]);
    }
}
