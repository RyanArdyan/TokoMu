<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AutentikasiController extends Controller
{
    // tampilan
    public function index()
    {
        // kembalikan ke tampilan autentikasi.login
        return view('autentikasi.login');
    }

    // logic login
    public function store(Request $request)
	{
        // validasi untuk semua input yang punya attribute name
		$validator = Validator::make($request->all(), [
            // input name email harus mengikut aturan berikut
			'email' => ['required', 'email'],
			'password' => ['required', 'min:6', 'max:20']
		]);

		// jika validasi biasa gagal
		if ($validator->fails()) {
            // kembalikkan response berupa json ke javascript
			return response()->json([
				'status' => 0,
				'message' => 'Validasi Biasa Errors',
				// errors akan berisi semua value attribute name yang error dan pesan errornya
				'errors' => $validator->errors()
			]);
		};

		// jika validasi lolos maka cek apakah email dan password yang di input ada di database
        // argument kedua attempt berarti fitur ingat saya true secara default
		if (Auth::attempt(['email' => $request->email, 'password' => $request->password], true)) {
            // buat ulang sessi
			$request->session()->regenerate();

			$name = User::where('email', $request->email)->value('name');
			$is_admin = User::where('email', $request->email)->value('is_admin');

			return response()->json([
				'status' => 200,
				'message' => 'User Berhasil Login',
				// aku mengirim 1 atau 0 dalam bentuk type string awoakwaok
				'is_admin' => $is_admin,
				'name' => $name
			]);
		}; 

        // fitur password salah
        foreach(User::all() as $detail_user) {
            // jika input email yang dimasukkan sama dengan detail user, column email
            if ($detail_user->email === $request->email) {
                // jika password yang dimasukkan tidak sama dengan detail_user, column password
                if (!Hash::check($detail_user->password, $request->password)) {
                    // kembalikkan tanggapan berupa json
                    return response()->json([
                        'message' => 'Password salah'
                    ]);
                };
            };
        };

        // jika email dan password yang di input tidak ada di database
        // kirimkan pesan validasi error
        return response()->json([
            'status' => 0,
            'message' => 'Email belum terdaftar'
        ]);
	}

    // Logout akan mengarahkan user ke url login
    public function logout()
	{
		// keluarkan auth
		Auth::logout();
		// batalkan session
		session()->invalidate();
		// buat ulang token
		session()->regenerateToken();
        // kembali alihkan ke route login.index
		return redirect()->route('login.index');
	}
}
