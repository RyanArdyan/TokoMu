<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Pendapatan</title>

    <style>
        /* semua element */
        * {
            margin: 0;
            padding: 0;
        }

        /* semua .my-5 */
        .my-5 {
            margin-top: 30px;
            margin-bottom: 5px;
        }

        .mb-30 {
            margin-bottom: 30px;
        }

        .container {
            margin-left: 30px;
            margin-right: 30px;
        }

        .text-center {
            text-align: center;
        }

        table {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        /* table td dan table th */
        table td, table th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .tebal-normal {
            font-weight: normal;
        }
    </style>

</head>
<body>
    <h1 class="text-center my-5">Laporan Pendapatan</h1>
    <h3 class="text-center tebal-normal mb-30">
        {{-- anggaplah tanggal 1 sampai tanggal 10 --}}
        {{-- panggil fungsi tanggal_indonesia milik helpers lalu kirimkan value $tanggal-awal, false berarti aku tidak mencetak nama hari --}}
        Tanggal {{ tanggal_indonesia($tanggal_awal, false) }}
        s/d 
        Tanggal {{ tanggal_indonesia($tanggal_hari_ini, false) }}
    </h3>

    <div class="container">
        <table>
            <thead>
                <th>Tanggal</th>
                <th>Penjualan</th>
                <th>Pembelian</th>
                <th>Pengeluaran</th>
                <th>Pendapatan</th>
            </thead>
    
            <tbody>
                {{-- looping baris --}}
                @foreach($data as $row)
                    <tr>
                        @foreach($row as $col)
                            <td>{{ $col }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>