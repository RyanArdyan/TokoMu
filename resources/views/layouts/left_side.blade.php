<div class="left-side-menu">

    <div class="slimscroll-menu">

        <!-- User box -->
        <div class="user-box text-center">
            <div class="dropdown">
                <a href="#" class="nama_user text-dark dropdown-toggle h5 mt-2 mb-1 d-block"
                    data-toggle="dropdown">{{ auth()->user()->name }}</a>
            </div>
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

                <li class="menu-title">Navigation</li>
                {{-- jika kasir yang login --}}
                {{-- jika user yang login, value column is_admin nya adalah 0 maka --}}
                @if (auth()->user()->is_admin === 0)
                    
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
                        <a href="javascript: void(0);">
                            <i class="mdi mdi-truck"></i>
                            <span> Penyuplai </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-second-level" aria-expanded="false">
                            {{-- Data Penyuplai --}}
                            {{-- jika permintaan url nya adalah penyuplai dan apapun setelah itu maka kasi class active kalau bukan maka kasi string kosong  --}}
                            {{-- ke route penyuplai.index --}}
                            <li class="{{ Request()->is('penyuplai*') ? 'active' : '' }}"><a href="{{ route('penyuplai.index') }}">Data Penyuplai</a></li>
                            
                            {{-- Produk Penyuplai --}}
                            <li class="{{ Request()->is('produk-penyuplai*') ? 'active' : '' }}"><a href="{{ route('produk_penyuplai.index') }}">Produk Penyuplai</a></li>
                        </ul>
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
                    {{-- Manajement kasir --}}
                    <li>
                        {{-- jika permintaan adalah manajemen_kasir maka aktifkan, kalau bukan kasi string kosong --}}
                        <a href="{{ route('manajemen_kasir.index') }}"
                            class="{{ Request()->is('manajemen_kasir*') ? 'active' : '' }}">
                            <i class="mdi mdi-account-group"></i>
                            <span> Manajemen Kasir </span>
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
