<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// package laravel datatables
use DataTables;
use App\Models\Member;
// package DomPDF
use Barryvdh\DomPDF\Facade\Pdf;

class MemberController extends Controller
{
    /**
     * Menampilkan daftar member
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // jika $request tidak memilki ajax
        // kembalikkan ke tampilan member.index
        return view('member.index');
    }

    // $request berisi ajax
    public function read(Request $request)
    {
        // jika script meminta data member
        // syntax punya laravel
        // member pilih semua value dari column member_id, kode_member dan lain-lain lalu dapatkan.
        // urutan datanya dari Z ke A
        $semua_member = Member::select('member_id', 'kode_member', 'nama_member', 'telepon_member', 'alamat_member')->latest()->get();
        // syntax punya yajra
        // kembalikkan datatables dari semua_member
        return DataTables::of($semua_member)
            // buat pengulangan nomor
            // tambah index column
            ->addIndexColumn()
            // $member berisi mengulang detail member
            ->addColumn('select', function(Member $member) {
                // return 'element html'
                // alasan name="member_ids[]" adalah karena attribute name akan banyak value column member_id milik table member yang disimpan ke dalam array. contohnya ["1". "2"]
                // attribute value akan menyimpan value detail member, column member_id
                return '
                        <input name="member_ids[]" value="' . $member->member_id . '" class="pilih form-check-input mx-auto" type="checkbox">
                ';
            })
            ->addColumn('kode_member', function(Member $member) {
                return '<span class="badge badge-success">' . $member->kode_member . '<span>';
            })
            // buat tombol edit
            ->addColumn('action', function(Member $member) {
                // attribute data-id digunakan untuk menyimpan value detail member, column member_id
                $btn = '
                    <button data-id="' . $member->member_id . '" class="tombol_edit btn btn-warning btn-sm">
                        <i class="fas fa-pencil-alt"></i> Edit
                    </button>
                ';
                return $btn;
            })
        // jika sebuah column berisi membuat element html dan memanggil relasi table maka harus dimasukkan ke rawColumns
        ->rawColumns(['select', 'kode_member', 'action'])
        // buat nyata
        ->make(true);
    }


    /**
     * Simpan data member yang baru ke table member
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // $reqeust berisi semua value element input
    public function store(Request $request)
    {
        // buat validasi untuk semua input yang punya attribute name
        // berisi validator buat semua permintaan
        $validator = Validator::make($request->all(), [
            // input name="nama_member" harus mengikuti aturan berikut
            // unique berarti valuenya tidak boleh sama dengan value column nama yang sudah ada
            'nama_member' => 'required|unique:member|max:20|min:2',
            // minimal digit nomor telepon indonesia adalah 10
            // maximal digit nomor teleoon indonesia adalah 13
            'telepon_member' => 'required|unique:member',
            // maximalnya 255 karena maximal value column tipe varchar pada table adalah 255
            'alamat_member' => 'required|max:255|min:3'
        ], [
            // terjemahankan karena kalau menggunakan lang/id itu akan aneh
            'nama_member.unique' => 'Nama ini sudah digunakan orang lain',
            'telepon_member.unique' => 'Telepon ini sudah digunakan orang lain.'
        ]);

        // jika validasi gagal karena user tidak benar mengisi datanya
        if ($validator->fails()) {
            // kembalikkan tanggapan berupa json
            return response()->json([
                // key status berisi value 0
                'status' => 0,
                'pesan' => 'Error',
                // key errors berisi semua value attribute name yang error dan semua pesan errornya
                'errors' => $validator->errors()
            ]);
        // lain jika validasi berhasil
        } else {
            // jika validasi biasa menggunakan laravel sudah berhasil maka saatnya melakukan validasi terhadap input name="telepon_member" agar user memasukkan nomor handphone indonesia yang benar
            // nomor telepon indonesia diawali oleh 08, minimal 10 digit dan maksimal 13 digit
            $regex = '/^08[0-9]{8,11}$/';

            // Fungsi ini preg_match()akan memberi tahu Anda apakah suatu string berisi kecocokan suatu pola.
            // jika value input name="telepon_member" tidak sama dengan regex maka
            if(!preg_match($regex, $request->telepon_member)) {
                // nomor handphone tidak valid
                // kembalikkan tanggapan berupa json
                return response()->json([
                    // key status berisi value 422
                    'status' => 422,
                    // key message berisi value berikut
                    'message' => 'Tolong masukkan nomor handphone indonesia yang benar.'
                ]);
            };

            // ambil satu baris data member yang terakhir
            $baris_data_member_yg_terakhir = Member::latest()->first();
            // jika tidak ada baris data member yang terakhir karena belum ada member maka $kode_member_yg_terakhir diisi 00001
            if (!$baris_data_member_yg_terakhir) {
                $kode_member = '00001';
            }
            // lain jika ada baris data member
            else if ($baris_data_member_yg_terakhir) {
                // anggaplah berisi "M-00001"
                $kode_member_yg_terakhir = $baris_data_member_yg_terakhir->kode_member;
                // anggaplah data terakhir berisi "M-00001"
                // maka saya tidak akan bisa melakukan "M-00001" + 1 karena string + integer = string
                // aku butuh explode agar bisa memecah menggunakan -

                // anggaplah berisi ["M", "00001"]
                $explode_kode_member = explode("-", $kode_member_yg_terakhir);
                // "M-00001" akan menjadi M dan 00001 lalu di tambah 1 = 2
                // berisi ubah isi $explode_kode_member index 1 yang berisi "00001" menjadi 00001 lalu di tambah 1 maka akan menjadi 00002
                $ubah_string_kode_member_menjadi_integer = (int) $explode_kode_member[1] + 1;

                // panggil fungsi helper kode_berurutan
                // 5 berarti jumlah digit kode_membernya
                $kode_member = kode_berurutan($ubah_string_kode_member_menjadi_integer, 5);

            };

            // Simpan member
            // member buat
            Member::create([
                // panggil column kode_member milik table member diisi dengan "M-" digabung dengan misalnya 00001 maka akan menjadi "M-00001"
                'kode_member' => 'M-' . $kode_member,
                // panggil column nama_member milik table member diisi dengan value attribute name="nama_member"
                'nama_member' => $request->nama_member,
                'telepon_member' => $request->telepon_member,
                'alamat_member' => $request->alamat_member
            ]);
            // kembalikkan tanggapan berupa json
            return response()->json([
                // key status berisi 200
                'status' => 200,
                // key pesan berisi misalnya Member budi berhasil disimpan
                'pesan' => "Member $request->nama_member berhasil disimpan.",
            ]);
        };
    }

    /**
     * Menampilkan sumber daya spesifik yaitu member
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // anggaplah $member_id berisi 1, di dapatkan dari script, aku mengirim $member_id lewat url
    public function show($member_id)
    {
        // ambil detail member berdasarkan member_id
        // member dimana value column member_id sama dengan value $member_id, baris pertama
        $detail_member = Member::where('member_id', $member_id)->first();
        // kembalikkan tanggapan berupa json lalu data berupa array assosiatif
        return response()->json([
            // key member_id berisi $member_id anggaplah 1
            'member_id' => $member_id,
            // key detail_member berisi detail member, anggaplah detail member id 1
            'detail_member' => $detail_member,
        ]);
    }

    /**
     * Perbarui sumber daya spesifik di penyimpanan yaitu memebr
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // $request berisi data dari input input atau data dari semua value attribute name
    // $member_id berisi member_id, di dapatkan dari script yang mengirim member_id lewat url, anggaplah 1
    public function update(Request $request, $member_id)
    {
        // detail member
        // ambil detail member berdasarkan member_id yang dikirimkna lewat url
        $detail_member = Member::where('member_id', $member_id)->first();

        // jika nilai input name="nama_member" sama dengan nilai column nama_member di table member
        if ($request->nama_member === $detail_member->nama_member) {
            // input name="nama_member" harus mengikuti aturan berikut
            $validasi_nama_member = 'required|string|min:2|max:20';
        }
        // lain jika value name="nama_member" tidak sama dengan value detail member, column nama_member milik table member
        else if($request->nama_member !== $detail_member->nama_member) {
            // input name="nama_member" harus mengikuti aturan berikut
            $validasi_nama_member = 'required|string|min:2|max:20|unique:member';
        };

        // jika nilai input name="telepon_member" sama dengan nilai column telepon_member pada table member
        if ($request->telepon_member === $detail_member->telepon_member) {
            // input name="telepon_member" harus mengikuti aturan berikut
            $validasi_telepon_member = 'required|min:10|max:13';
        }
        // lain jika value name="telepon_member" tidak sama dengan value column telepon_member milik detail member di table member
        else if ($request->telepon_member !== $detail_member->telepon_member) {
            $validasi_telepon_member = 'required|unique:member|min:10|max:13';
        };

        // validasi semua input yang punya attribute name
        // validator buat semua permintaan
        $validator = Validator::make($request->all(), [
            // input name="nama_member berisi atraun dari $validasi_nama_member
            'nama_member' => $validasi_nama_member,
            'telepon_member' => $validasi_telepon_member,
            'alamat_member' => 'required|min:3|max:255'
        ],
        // timpa terjemahan unique milik lang/id
        [
            'nama_member.unique' => 'Nama member ini sudah digunakan orang lain',
            'telepon_member.unique' => "Nomor telepon ini sudah digunakan orang lain"
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
            // jika validasi biasa menggunakan laravel sudah berhasil maka saat nya melakukan validasi terhadap input name="telepon_member" agar user memasukkan nomor handphone indonesia yang benar
            // nomor telepon indonesia diawali oleh 08
            $regex = '/^08[0-9]{8,11}$/';

            // Fungsi ini preg_match()akan memberi tahu Anda apakah suatu string berisi kecocokan suatu pola.
            // jika value input name="telepon_member" tidak sama dengan regex maka
            if(!preg_match($regex, $request->telepon_member)) {
                // nomor handphone tidak valid
                // kembalikkan tanggapan berupa json
                return response()->json([
                    // key status berisi value 422
                    'status' => 422,
                    // key message berisi value berikut
                    'message' => 'Tolong masukkan nomor handphone indonesia yang benar.'
                ]);
            };

            // Perbarui member
            // panggil value column nama_member milik detai_member lalu diisi dengan value input name="nama_member"
            $detail_member->nama_member = $request->nama_member;
            $detail_member->telepon_member = $request->telepon_member;
            $detail_member->alamat_member = $request->alamat_member;
            // detail member, diperbarui
            $detail_member->update();

            // kembalikkan tanggapan berupa json lalu kirimkan data
            return response()->json([
                // key status berisi value 200
                'status' => 200,
                // key pesan berisi pesan berikut, misalnya member budi berhasil diperbarui
                'pesan' => "Member $request->nama_member berhasil diperbarui.",
            ]);
        };
    }

    /**
     * Menghapus data-data member yang dipilih
     */
    public function destroy(Request $request)
    {

        // hapus beberapa member berdasarkan beberapa member_id yang di kirimkan
        // member dimana dalam column member_id berisi value yang sama dengan member_ids maka hapus
        Member::whereIn('member_id', $request->member_ids)->delete();


        return response()->json([
            'status' => 200,
            'pesan' => 'Berhasil menghapus member yang dipilih'
        ]);
    }

    // untuk mencetak kartu beberapa member
    // $request akan berisi beberapa value column member_id yang di dapatkan dari MemberController, method read
    // anggapalah $request berisi ["1", "2"]
    public function cetak_kartu(Request $request)
    {
        // buat koleksi dari array kosong
        // jika Anda perlu memanipulasi data dengan metode seperti pengurutan, pengelompokkan, atau pemfilteran, maka koleksi mungkin lebih sesuai untuk kebutuhan Anda.
        $beberapa_data_member = collect(array());

        // lakukan pengulangan pada id, anggaplah ada 2 pengulangan, berisi 1 dan 2
        foreach($request->member_ids as $member_id) {
            // looping ambil detail member dengan cara mengambil detail_member berdasarkan member_id
            // Member dimana value column member_id sama dengan value $member_id, ambil yang pertama
            $detail_member = Member::where('member_id', $member_id)->first();
            // push data ke koleksi array $datamember, anggaplah berisi [
                // ['member_id' => '1', 'nama_member' => 'admin', dst],
                // [data index 1]
            // ]
            $beberapa_data_member[] = $detail_member;
        };

        // chunk artinya bongkahan
        // Metode chunk memecah koleksi menjadi beberapa koleksi yang lebih kecil dengan ukuran tertentu:
        // contoh dari dokumentasi
        // $collection = collect([1, 2, 3, 4, 5, 6, 7]);
        // $chunks = $collection->chunk(4);
        // $chunks->all();
        // // hasil :
        // // [[1, 2, 3, 4], [5, 6, 7]]

        // inisialisasi angka
        $no = 1;
        // aku menggunakan package barryvdh/laravel-dompdf
        // berisi pdf muat tampilan member.cetak_kartu lalu kirimkan data
        $pdf = Pdf::loadView('member.cetak_kartu', [
            // key some_data_member contains several member data for example containing [['member_id' => 1, etc], ['member_id' => 2]]
            // key beberapa_data_member berisi beberapa data member misalnya berisi [['member_id' => 1, dst], ['member_id' => 2]]
            'beberapa_data_member' => $beberapa_data_member,
            // key no berisi value $no yaitu 1
            'no' => $no,
        ]);
        // Anda dapat mengubah orientasi dan ukuran kertas
        $pdf->setPaper(array(0, 0, 566.93, 850.39), 'potrait');
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('kartu_member.pdf');
    }
}
