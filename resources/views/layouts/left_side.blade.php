<div class="left-side-menu">
    <div class="slimscroll-menu">
        <!-- User box -->
        <div class="user-box text-center">
            <div class="dropdown">
                <a href="#" class="nama_user text-dark dropdown-toggle h5 mt-2 mb-1 d-block"
                {{-- cetak detail_user yang autentikasi atau login, column name --}}
                    data-toggle="dropdown">{{ auth()->user()->name }}</a>
            </div>
            {{-- cetak detail_user yang autentikasi atau login, column email --}}
            <p class="text-muted">{{ auth()->user()->email }}</p>
            <ul class="list-inline">
                <li class="list-inline-item">
                    <a href="#" class="text-muted">
                        <i class="mdi mdi-settings"></i>
                    </a>
                </li>

                <li class="list-inline-item">
                    <a href="#" class="text-custom">
                        <i class="mdi mdi-power"></i>
                    </a>
                </li>
            </ul>
        </div>

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">
                <li class="menu-title">Navigasi</li>
                {{-- jika kasir yang login --}}
                {{-- jika user yang login, value column is_admin nya adalah 0 maka --}}
                @if (auth()->user()->is_admin === 0)
                    {{-- Dashboard --}}
                    <li>
                        {{-- jika permintaan adalah dashboard maka aktifkan, kalau bukan kasi string kosong --}}
                        <a href="{{ route('dashboard.index') }}"
                            class="{{ Request()->is('dashboard*') ? 'active' : '' }}">
                            <i class="mdi mdi-view-dashboard"></i>
                            <span> Dashboard </span>
                        </a>
                    </li>
                    {{-- penjualan --}}
                    <li>
                        {{-- ke route penjualan.index --}}
                        {{-- jika permintaan adalah penjualan lalu apapun setelah itu maka aktifkan, kalau bukan maka kasi string kosong --}}
                        <a href="{{ route('penjualan.index') }}"
                            class="{{ Request()->is('penjualan*') ? 'active' : '' }}">
                            <i class="mdi mdi-credit-card-minus-outline"></i>
                            <span> Penjualan </span>
                        </a>
                    </li>
                @endif

                {{-- jika admin yang login --}}
                {{-- jika yang login, value column is_admin nya adalah 1 maka --}}
                @if (auth()->user()->is_admin === 1)
                    {{-- Dashboard --}}
                    <li>
                        {{-- jika permintaan adalah dashboard maka aktifkan, kalau bukan kasi string kosong --}}
                        <a href="{{ route('dashboard.index') }}"
                            class="{{ Request()->is('dashboard*') ? 'active' : '' }}">
                            <i class="mdi mdi-view-dashboard"></i>
                            <span> Dashboard </span>
                        </a>
                    </li>
                    {{-- kategori --}}
                    <li>
                        {{-- jika permintaan adalah kategori maka aktifkan, kalau bukan kasi string kosong --}}
                        <a href="{{ route('kategori.index') }}"
                            class="{{ Request()->is('kategori*') ? 'active' : '' }}">
                            <i class="mdi mdi-cube"></i>
                            <span> Kategori </span>
                        </a>
                    </li>
                    {{-- penyuplai --}}
                    <li>
                        <a href="{{ route('penyuplai.index') }}" class="{{ request()->is('penyuplai*') ? 'active' : '' }}">
                            <i class="mdi mdi-truck"></i>
                            <span> Penyuplai </span>
                        </a>
                    </li>
                    {{-- Produk --}}
                    <li>
                        {{-- ke route produk.index --}}
                        {{-- jika permintaan adalah url produk/ lalu apapun setelah itu maka aktifkan, kalau bukan maka kasi string kosong --}}
                        <a href="{{ route('produk.index') }}"
                            class="{{ Request()->is('produk/*') ? 'active' : '' }}">
                            <i class="mdi mdi-bag-personal"></i>
                            <span> Produk </span>
                        </a>
                    </li>
                    {{-- member --}}
                    <li>
                        {{-- ke route member.index --}}
                        {{-- jika permintaan adalah member maka aktifkan, kalau bukan maka kasi string kosong --}}
                        <a href="{{ route('member.index') }}"
                            class="{{ Request()->is('member*') ? 'active' : '' }}">
                            <i class="mdi mdi-star"></i>
                            <span> Member </span>
                        </a>
                    </li>
                    {{-- pengeluaran --}}
                    <li>
                        {{-- ke route pengeluaran.index --}}
                        {{-- jika permintaan adalah pengeluaran maka aktifkan, kalau bukan maka kasi string kosong --}}
                        <a href="{{ route('pengeluaran.index') }}"
                            class="{{ Request()->is('pengeluaran*') ? 'active' : '' }}">
                            <i class="mdi mdi-credit-card-minus"></i>
                            <span> pengeluaran </span>
                        </a>
                    </li>
                    {{-- pembelian --}}
                    <li>
                        {{-- ke route pembelian.index --}}
                        {{-- jika permintaan adalah pembelian lalu apapun setelah itu maka aktifkan, kalau bukan maka kasi string kosong --}}
                        <a href="{{ route('pembelian.index') }}"
                            class="{{ Request()->is('pembelian*') ? 'active' : '' }}">
                            <i class="mdi mdi-credit-card-minus-outline"></i>
                            <span> pembelian </span>
                        </a>
                    </li>
                    {{-- penjualan --}}
                    <li>
                        {{-- ke route penjualan.index --}}
                        {{-- jika permintaan adalah penjualan lalu apapun setelah itu maka aktifkan, kalau bukan maka kasi string kosong --}}
                        <a href="{{ route('penjualan.index') }}"
                            class="{{ Request()->is('penjualan*') ? 'active' : '' }}">
                            <i class="mdi mdi-credit-card-plus"></i>
                            <span> Penjualan </span>
                        </a>
                    </li>
                    {{-- Manajement kasir --}}
                    <li>
                        {{-- jika permintaan adalah manajemen_kasir maka aktifkan, kalau bukan kasi string kosong --}}
                        <a href="{{ route('manajemen_kasir.index') }}"
                            class="{{ Request()->is('manajemen_kasir*') ? 'active' : '' }}">
                            <i class="mdi mdi-account-group"></i>
                            <span> Manajemen Kasir </span>
                        </a>
                    </li>
                    {{-- Laporan --}}
                    <li>
                        {{-- ke route laporan.index --}}
                        {{-- jika permintaan adalah laporan dan apapun stelah itu maka aktifkan, kalau bukan kasi string kosong --}}
                        <a href="{{ route('laporan.index') }}" class="{{ Request()->is('laporan*') ? 'active' : '' }}">
                            <i class="mdi mdi-file-pdf-box"></i>
                            <span>Laporan</span>
                        </a>
                    </li>
                @endif

                {{-- jika pembeli yang login --}}
                {{-- jika yang login, value column is_admin nya adalah 2 maka  --}}
                @if (auth()->user()->is_admin === 2)
                    {{-- Produk --}}
                    <li>
                        {{-- ke rute produk.index --}}
                        {{-- Jika permintaan adalah produk dan apapun setelah itu maka aktifkan, kalau bukan kasi string kosong --}}
                        <a href="{{ route('produk.index') }}" class="{{ Request()->is('produk*') ? 'active' : '' }}">
                            <i class="mdi mdi-bag-personal"></i>
                            <span>Produk</span>
                        </a>
                    </li>
                    {{-- Keranjang --}}
                    <li>
                        {{-- ke rute keranjang.index --}}
                        {{-- Jika permintaan adalah keranjang dan apapun setelah itu maka aktifkan, kalau bukan kasi string kosong --}}
                        <a href="{{ route('keranjang.index') }}" class="{{ Request()->is('keranjang*') ? 'active' : '' }}">
                            <i class="mdi mdi-cart"></i>
                            <span>Keranjang</span>
                        </a>
                    </li>
                @endif
            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
