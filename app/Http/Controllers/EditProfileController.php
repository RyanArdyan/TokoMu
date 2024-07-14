<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class EditProfileController extends Controller
{
    // halaman detail user
    public function edit()
    {
        // detail user yang login
        $detail_user_yang_login =  auth()->user();
        // kembalikan ke view edit_profile.index, sambil kirimkna detail_user_yang_login
        return view('edit_profile.index', ['detail_user_yang_login' => $detail_user_yang_login]);
    }

    // logic update detail user
    // $request akan menangkap semua value input
    public function update(Request $request)
    {
        // ambil detail user yang login
        $detail_user_yang_login = auth()->user();

        // jika nilai input name sama dengan nilai column name dari $detail_user_yang_login
        if ($request->name === $detail_user_yang_login->name) {
            // name harus wajib, string, min 3 dan max 20
            $validasi_name = 'required|string|min:3|max:20';
        // lain jika input name tidak sama dengan detail_user column name
        } else if ($request->name !== $detail_user_yang_login->name) {
            // validasi name wajib, string, min 3, max 20  dan harus unique dari detail users
            $validasi_name = 'required|string|min:3|max:20|unique:users';
        };

        // jika nilai input email sama dengan nilai column email dari $detail_user_yang_login
        if ($request->email === $detail_user_yang_login->email) {
            // validasi email wajib, email dan max adalah 255
            $validasi_email = 'required|email|max:255';
        // lain jika input email tidak sama dengan detail_user column email
        } else if($request->email !== $detail_user_yang_login->email) {
            // validasi meail harus unique, wajib, email dan max nya adalah 255
            $validasi_email = 'unique:users|required|email|max:255';
        };

        // validasi semua input
        $validator = Validator::make($request->all(), [
            // input attribute name yang berisi name harus menggunakan aturan dari $validasi_name
            'name' => $validasi_name,
            'email' => $validasi_email,
            'gambar' => 'image|max:600'
        ],
        // Terjamahan validasi
        [
            'name.unique' => 'Orang lain sudah menggunakan nama itu.',
            'email.unique' => 'Orang lain sudah menggunakan email itu.'
        ]);

        // jika validasi gagal
        if ($validator->fails()) {
            // kembalikan tanggapan berupa json
            return response()->json([
                // key status berisi 0
                'status' => 0,
                // key error berisi semua value attribute name dan semua pesan errornya
                'errors' => $validator->errors()->toArray()
            ]);
        // jika validasi berhasil
        } else {
            // jika user memiliki file gambar atau jika user mengganti gambar
            if ($request->hasFile('gambar')) {
                // jika value detail user yang login, column gambarnya sama dengan 'gambar_default.png maka
                if ($detail_user_yang_login->gambar === 'gambar_default.png') {
                    // jangan hapus file gambar_default.png
                    // lakukan upload gambar
                    // nama gambar baru
                    $nama_gambar_baru = time() . '_' . $request->id . '.' . $request->file('gambar')->extension();
                    // The first argument in putFileAs is the place or folder where the image will be saved.
                    // the second argument is input name="image"
                    // the third argument is the name of the image file
                    Storage::putFileAs('public/foto_profil/', $request->file('gambar'), $nama_gambar_baru);
                // lain jika value pada detail)user_yang_login, column gambar tidak sma dengan 'gambar_default.png' maka
                } else if ($detail_user_yang_login->gambar !== 'gambar_default.png') {
                    // hapus gambar lama
                    Storage::delete('public/foto_profil/' . $detail_user_yang_login->gambar);
                    // nama gambar baru
                    $nama_gambar_baru = time() . '_' . $request->id . '.' . $request->file('gambar')->extension();
                    // upload gambar dan ganti nama gambar
                    // argument pertama pada putFileAs adalah tempat atau folder gambar akan disimpan
                    // argumen kedua adalah input name="gambar"
                    // argument ketiga adalah nama file gambar nya
                    Storage::putFileAs('public/foto_profil/', $request->file('gambar'), $nama_gambar_baru);
                };
            // jika user tidak mengupload gambar
            } else if (!$request->hasFile('gambar')) {
                // berisi memanggil value detail user, column gambar
                $nama_gambar_baru = $detail_user_yang_login->gambar;
            };

            // perbarui user
            // panggil value detail_user column name di table lalu isi dengan input name
            $detail_user_yang_login->gambar = $nama_gambar_baru;
            $detail_user_yang_login->name = $request->name;
            $detail_user_yang_login->email = $request->email;
            // simpan perubahan
            $detail_user_yang_login->update();

            return response()->json([
                'status' => 200,
                'pesan' => 'Profile berhasil diperbarui.',
                // aku mengirimkan detail user agar aku bisa mengupdate gambar profile di layouts/top-navbar
                'detail_user' => $detail_user_yang_login
            ]);
        };
    }

    // Update Password
    // $request akan menangkap semua value attribute name
    public function update_password(Request $request)
    {
        // ambil detail user berdasarkan value user yang login
        $detail_user = auth()->user();

        // validasi
        $validator = Validator::make($request->all(), [
            // input name password lama berisi aturan
            'password_lama' => ['required', 'min:6', 'max:20'],
            'password_baru' => ['required', 'min:6', 'max:20']
        ]);

        // jika validasi gagal
        if ($validator->fails()) {
            // kembalikkan response berupa json
            return response()->json([
                // key status berisi value 0
                'status' => 0,
                // key errors berisi semua value attribute name yang error dan pesan errornya
                'errors' => $validator->errors()->toArray()
            ]);
        // jika validasi berhasil
        } else {
            // jika input password lama sama dengan detail user column password maka
            if (Hash::check($request->password_lama, $detail_user->password)) {
                // jika value input password baru sama dengan password lama maka tidak boleh
                if (Hash::check($request->password_baru, $detail_user->password)) {
                    // kembalikan tangapan berupa json
                    return response()->json([
                        // key pesan berisi value
                        'pesan' => 'Password baru tidak boleh sama dengan password lama'
                    ]);
                }
                // jika value input password_baru tidak sama dengan password lama, maka update password berdasarkan password baru
                else if (!Hash::check($request->password_baru, $detail_user->password)) {
                    // Perbarui password
                    // panggil detail_user, column password diisi dengan value input password_baru yang di hash
                    $detail_user->password = Hash::make($request->password_baru);
                    $detail_user->save();

                    return response()->json([
                        'status' => 200,
                        'pesan' => "Password berhasil diperbarui.",
                    ]);
                }
            // jika value input password lama tidak sama dengan null berarti ada isinya namun value input tidak sama dengan detail_user, column password
            } else {
                // kembalikan response berupa json 'Password lama salah'
                return response()->json([
                    // key pesan berisi value "Password lama salah"
                    'pesan' => 'Password lama salah'
                ]);
            };
        };
    }
}
