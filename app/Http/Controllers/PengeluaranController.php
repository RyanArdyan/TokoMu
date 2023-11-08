<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// untuk validasi formulir
use Illuminate\Support\Facades\Validator;
use App\Models\Pengeluaran;
use App\Models\PengeluaranDetail;
// fitur yang berhubungan dengan excel
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PengeluaranImport;
use App\Exports\PengeluaranExport;
// package laravel datatables
use DataTables;

class PengeluaranController extends Controller
{
    /**
     * Ke tampilan pengeluaran.index
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // kembalikkan ke tampilan pengeluaran.index
        return view('pengeluaran.index');
    }

    // menampilkan daftar pengeluaran
    public function read()
    {
        // ambil semua value dari column pengeluaran_id, nama_pengeluaran, total_pengeluaran dan updated_at
        $semua_pengeluaran = Pengeluaran::select('pengeluaran_id', 'nama_pengeluaran', 'total_pengeluaran', 'updated_at')->get();
        // syntax punya yajra
        // kembalikkan datatables dari semua_pengeluaran
        return DataTables::of($semua_pengeluaran)
            // pengeluaran nomor
            // tambah index column
            ->addIndexColumn()
            // ulang detail pengeluaran menggunakan $pengeluaran
            // tambah column select, jalankan fungsi, pengeluaran $pengeluran
            ->addColumn('select', function(Pengeluaran $pengeluaran) {
                // return element html
                // name="pengeluaran_ids[]" karena name akan menyimpan array yang berisi beberapa pengeluaran_id, contohnya ["1", "2"]
                // attribute value digunakan untuk memanggil value column pengeluaran_id
                return '
                        <input name="pengeluaran_ids[]" value="' . $pengeluaran->pengeluaran_id . '" class="pilih select form-check-input mx-auto" type="checkbox">
                ';
            })
            ->addColumn('tanggal_pengeluaran', function(Pengeluaran $pengeluaran) {
                // isoFormat() akan membuat tanggal mudah dibaca
                return $pengeluaran->updated_at->isoFormat('dddd, D MMMM Y');
            })
            ->addColumn('total_pengeluaran', function(Pengeluaran $pengeluaran) {
                // panggil fungsi rupiah_bentuk milik helpers dan dikirimkan $pengeluaran->total_pengeluaran sebagai argumnen
                return rupiah_bentuk($pengeluaran->total_pengeluaran);
            })
            // buat tombol edit
            ->addColumn('action', function(Pengeluaran $pengeluaran) {
                return  '
                    <button data-id="' . $pengeluaran->pengeluaran_id . '" class="tombol_edit btn btn-warning btn-sm">
                        <i class="fas fa-pencil-alt"></i> Edit
                    </button>
                ';
            })
        // jika sebuah column berisi relasi antar table dan membuat elemnt html maka harus dimasukkan ke dalam rawColumns
        // column-column mentah dari select dan lain-lain
        ->rawColumns(['select', 'tanggal_pengeluaran', 'total_pengeluaran', 'action'])
        // buat nyata
        ->make(true);
    }

    // $request bisa mengambil value detail_user yang login
    public function create(Request $request)
    {
        // kembalikkan ke tampilan pengeluaran.create
        return view('pengeluaran.create');
    }

    /**
     * Simpan pengeluaran baru
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // $request containts data created by javascript
    // $request berisi data yang dibuat oleh javascript
    public function store(Request $request)
    {
        // berisi value input "waktu pengeluaran", "diterima oleh", "Nama Pengeluaran" dan "Total Jumlah", "Total Pengeluaran"
        $data_formulir_pengeluaran = $request->data_formulir_pengeluaran[0];

        // simpan data ke table pengeluaran
        $detail_pengeluaran = Pengeluaran::create([
            // column waktu_pengeluaran pada table pengeluaran diisi array $data_formulir_pengeluaran, indeks waktu_pengeluaran
            'waktu_pengeluaran' => $data_formulir_pengeluaran['waktu_pengeluaran'],
            'diterima_oleh' => $data_formulir_pengeluaran['diterima_oleh'],
            'nama_pengeluaran' => $data_formulir_pengeluaran['nama_pengeluaran'],
            'jumlah_pengeluaran' => $data_formulir_pengeluaran['total_jumlah'],
            'total_pengeluaran' => $data_formulir_pengeluaran['total_pengeluaran'] 
        ]);

        // menangkap data_formulir_pengeluaran_detail yg dikirimkan oleh javascript, berisi input nama_pengeluaran, jumlah, harga satuan, subtotal=
        // Ubah format data
        $data_formulir_pengeluaran_detail = json_decode($request->input('data_formulir_pengeluaran_detail'), true);

        // aku looping pakai foreach
        foreach ($data_formulir_pengeluaran_detail as $pengeluaran_detail) {
            PengeluaranDetail::create([
                    'pengeluaran_id' => $detail_pengeluaran->pengeluaran_id,
                    'nama_pengeluaran' => $pengeluaran_detail['nama_pengeluaran'],
                    'jumlah' => $pengeluaran_detail['jumlah'],
                    'harga_satuan' => $pengeluaran_detail['harga_satuan'],
                    'subtotal' => $pengeluaran_detail['subtotal'],
            ]);
        };

        // return the response in json
        // kembalikkan tanggapan berupa json
        return response()->json([
            // the status key contains the value 200
            // key status berisi value 200
            'status' => 200,
            // the data key contains the value of variable $detail_expense_form_data
            'data' =>  $data_formulir_pengeluaran_detail
            // // key pesan berisi pesan berikut contohnya "Pengeluaran gaji karyawan berhasil di simpan"
            // 'pesan' => "Pengeluaran $request->nama_pengeluaran berhasil di simpan.",
        ]);
    }

    /**
     * Tampilkan detail pengeluaran
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // aku mengirim pengeluaran_id lewat url dan di tangkap oleh $pengeluaran_id
    public function show($pengeluaran_id)
    {
        // ambil detail pengeluaran berdasarkan pengeluaran_id yang di kirimkan
        $detail_pengeluaran = Pengeluaran::where('pengeluaran_id', $pengeluaran_id)->first();
        // kembalikkan tanggapan berupa json lalu kirimkan data
        return response()->json([
            // key detail_pengeluaran berisi detail_pengeluaran
            'detail_pengeluaran' => $detail_pengeluaran,
        ]);
    }

    /**
     * Update the specified resource in storage. Perbarui detail pengeluaran milik table pengeluaran
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $pengeluaran_id)
    {
        // ambil detail pengeluaran berdasarkan pengeluaran_id
        // berisi pengeluaran dimana value column pengeluarn_id sama dengan pengeluaran_id, yang pertama saja
        $detail_pengeluaran = Pengeluaran::where('pengeluaran_id', $pengeluaran_id)->first();

        // jika nilai input nama_pengeluaran sama dengan nilai column nama_pengeluaran milik $detail_pengeluaran
        if ($request->nama_pengeluaran === $detail_pengeluaran->nama_pengeluaran) {
            // input name="nama_pengeluaran" berisi aturan berikut
            $validasi_nama_pengeluaran = 'required|string|min:3';
        } 
        // lain jika value input name="nama_pengeluararn tidak samaa dengan value detail pengeluaran, column nama_pengeluaran
        else if ($request->nama_pengeluaran !== $detail_pengeluaran->nama_pengeluaran) {
            // input name="nama_pengeluaran" berisi aturan berikut
            $validasi_nama_pengeluaran = 'required|string|min:3|unique:pengeluaran';
        };

        // validasi input yang punya attribute name
        // berisi validator buat semua permintaan
        $validator = Validator::make($request->all(), [
            // input name="nama_pengeluaran" harus mengikuti peraturan berikut
            'nama_pengeluaran' => $validasi_nama_pengeluaran,
            'total_pengeluaran' => 'required|integer|min:0'
        ],
        // terjemahkan aturan .unique
        [
            'nama_pengeluaran.unique' => 'Nama pengeluaran ini sudah ada'
        ]);

        // jika validasi gagal
        if ($validator->fails()) {
            // kembalikkaan tangapan berupa json
            return response()->json([
                // key status berisi 0
                'status' => 0,
                // key errors berisi semua value attribute name yang error dan pesan error nya
                'errors' => $validator->errors()->toArray()
            ]);
        // jika validasi berhasil
        } else {
            // Perbarui pengeluaran
            // panggil detail_pengeluran, column nama_pengeluaran lalu diisi dengan input name="nama_pengeluran"
            $detail_pengeluaran->nama_pengeluaran = $request->nama_pengeluaran;
            $detail_pengeluaran->total_pengeluaran = $request->total_pengeluaran;
            // detail_pengeluaran di perbarui
            $detail_pengeluaran->update();

            // kembalikkan tanggapan berupa json lalu kirimkan data-data
            return response()->json([
                // key status berisi value 200
                'status' => 200,
                // key pesan berisi pesna berikut, contohnya "Pengelueran gaji karyawan berhasil di perbarui" 
                'pesan' => "Pengeluaran $request->nama_pengeluaran berhasil diperbarui.",
            ]);
        };
    }

    /**
     * Hapus beberapa pengeluaran yang di centang
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // $request berisi beberapa value input name="pengeluaran_ids[]", anggaplah berisi ["1", "2"]
    public function destroy(Request $request)
    {
        // hapus beberapa pengeluaran berdasarkan beberapa pengeluaran_id yang di kirimkan
        // pengeluaran dimana dalam column pengeluaran_id berisi value yang sama dengan pengeluaran_ids maka hapus
        Pengeluaran::whereIn('pengeluaran_id', $request->pengeluaran_ids)->delete();

        // kembalikkan tanggapan berupa json
        return response()->json([
            // key status berisi value 200
            'status' => 200,
            'pesan' => 'Berhasil menghapus pengeluaran yang dipilih.'
        ]);
    }

    // import
    public function import_excel(Request $request)
    {
        // excel import(new PengeluranImport, $permintaan->file('file))
        Excel::import(new PengeluaranImport, $request->file('file'));

        // kembalikkan kembali()
        return back();
    }

    // export
    public function export_excel(Request $request)
    {
        // kembalikkan excel::unduh(new PengeluranExport, 'pengeluran.xlsx')
        return Excel::download(new PengeluaranExport, 'pengeluaran.xlsx');
    }
}
