{{-- memperluas parent nya yaitu layouts.app --}}
@extends('layouts.app')

{{-- kirimkan value section title ke parent nya yaitu layouts.app --}}
@section('title', 'Dashboard')

@section('konten')
{{-- baris --}}
<div class="row">
    {{-- column 3 --}}
    <div class="col-xl-3 col-md-6">
        {{-- kartu-kotak --}}
        <div class="card-box">

            <h4 class="header-title mt-0 mb-4">Kategori</h4>

            <div class="widget-chart-1">
                <div class="widget-chart-box-1 float-left" dir="ltr">
                    <input data-plugin="knob" data-width="80" data-height="80" data-fgColor="#f05050 "
                           data-bgColor="#F9B9B9" value="{{ $jumlah_kategori }}"
                           data-skin="tron" data-angleOffset="180" data-readOnly=true
                           data-thickness=".15"/>
                </div>

                <div class="widget-detail-1 text-right">
                    <h2 class="font-weight-normal pt-2 mb-1">{{ $jumlah_kategori }} </h2>
                    <p class="text-muted mb-1">Jumlah Kategori</p>
                </div>
            </div>
        </div>

    </div><!-- end col -->
    {{-- column 3 --}}
    <div class="col-xl-3 col-md-6">
        {{-- kartu-kotak --}}
        <div class="card-box">

            <h4 class="header-title mt-0 mb-4">Produk</h4>

            <div class="widget-chart-1">
                <div class="widget-chart-box-1 float-left" dir="ltr">
                    {{-- attribute value mencetak value $jumlah_produk yang dikirim DashboardController, method index --}}
                    <input data-plugin="knob" data-width="80" data-height="80" data-fgColor="#18ff03"
                           data-bgColor="#b3e3af" value="{{ $jumlah_produk }}"
                           data-skin="tron" data-angleOffset="180" data-readOnly=true
                           data-thickness=".15"/>
                </div>

                <div class="widget-detail-1 text-right">
                    <h2 class="font-weight-normal pt-2 mb-1">{{ $jumlah_produk }} </h2>
                    <p class="text-muted mb-1">Jumlah Produk</p>
                </div>
            </div>
        </div>

    </div><!-- end col -->
    {{-- column 3 --}}
    <div class="col-xl-3 col-md-6">
        {{-- kartu-kotak --}}
        <div class="card-box">

            <h4 class="header-title mt-0 mb-4">Penyuplai</h4>

            <div class="widget-chart-1">
                <div class="widget-chart-box-1 float-left" dir="ltr">
                    <input data-plugin="knob" data-width="80" data-height="80" data-fgColor="#ffbd4a"
                           data-bgColor="#FFE6BA" value="{{ $jumlah_penyuplai }}"
                           data-skin="tron" data-angleOffset="180" data-readOnly=true
                           data-thickness=".15"/>
                </div>

                <div class="widget-detail-1 text-right">
                    <h2 class="font-weight-normal pt-2 mb-1">{{ $jumlah_penyuplai }} </h2>
                    <p class="text-muted mb-1">Jumlah Penyuplai</p>
                </div>
            </div>
        </div>

    </div><!-- end col -->
    {{-- column 3 --}}
    <div class="col-xl-3 col-md-6">
        {{-- kartu-kotak --}}
        <div class="card-box">

            <h4 class="header-title mt-0 mb-4">Member</h4>

            <div class="widget-chart-1">
                <div class="widget-chart-box-1 float-left" dir="ltr">
                    <input data-plugin="knob" data-width="80" data-height="80" data-fgColor="#050df5"
                           data-bgColor="#b0b2f7" value="{{ $jumlah_member }}"
                           data-skin="tron" data-angleOffset="180" data-readOnly=true
                           data-thickness=".15"/>
                </div>

                <div class="widget-detail-1 text-right">
                    <h2 class="font-weight-normal pt-2 mb-1">{{ $jumlah_member }} </h2>
                    <p class="text-muted mb-1">Jumlah Member</p>
                </div>
            </div>
        </div>

    </div><!-- end col -->
    
    
</div>

<div class="row">
    <div class="col-sm-12">
        {{-- cetak value $tanggal_awal dan value $tanggal_hari_ini --}}
        Grafik Pendapatan {{ $tanggal_awal }} Sampai Dengan {{ $tanggal_hari_ini }}
        <div>
            <canvas id="chart"></canvas>
        </div>
    </div>
</div>
@endsection

@push('script')
{{-- panggil chart.js dan harus ada koneksi internet karena itu cdn --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // document dapatkan element dengan #chart
  const ctx = document.getElementById('chart');

  new Chart(ctx, {
    type: 'line',
    data: {
      labels: {{ json_encode($data_tanggal) }},
      datasets: [{
        label: 'Rupiah',
        data: {{ json_encode($data_pendapatan) }},
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>
@endpush