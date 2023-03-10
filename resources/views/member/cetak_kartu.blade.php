<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Kartu Member</title>
    <style>
        .card {
            width: 340px;
            height: 150px;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        }

        .red {
            background-color: red;
            color: white;
        }

        .card img {
            width: 50px;
            border-radius: 5px;
        }

        .card h3 {
            font-size: 24px;
            margin: 10px 0;
        }

        .card p {
            font-size: 16px;
            margin: 10px 0;
        }

        .card button {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            margin-top: 10px;
            cursor: pointer;
            font-size: 16px;
        }

        .card button:hover {
            background-color: #3e8e41;
        }
    </style>
</head>

<body>
    <h1>Cetak Kartu Member</h1>

    {{-- looping beberapa_data_memebr sebagai member --}}
    @foreach ($beberapa_data_member as $member)
        <div class="card red">
            {{-- public_path() akan memanggil folder public --}}
            <img src="{{ public_path('storage/gambar_member/logo_perusahaan.png') }}" alt="Gambar Card">
            {{-- aku menggunakan package milon/barcode --}}
            <img src='data:image/png;base64, {{ DNS2D::getBarcodePNG("$member->kode_member", 'QRCODE') }}'
                alt="qrcode" height="45" width="45" style="float: right">
            <h3>{{ $member->nama_member }}</h3>
            <p>{{ $member->telepon_member }}.</p>
            <hr>
        </div>

        <br>

        <div class="card red">
            <p>Gunakan kartu ini jika berbelanja di modifikasi tokomu</p>
        </div>

        <br>
    @endforeach


</body>

</html>
