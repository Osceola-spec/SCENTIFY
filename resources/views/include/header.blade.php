<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm py-3">
    <div class="container d-flex justify-content-between align-items-center">

        <div class="navbar-left-zone" style="flex: 1; display: flex; align-items: center;">
            <a class="navbar-brand m-0" href="{{ url('/') }}">Scentify<span class="text-warning">.</span></a>
        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center flex-grow-0" id="navbarNav" style="flex: 2;">
            <ul class="navbar-nav gap-2">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                        href="{{ route('home') }}">Home</a>
                </li>

                <li class="nav-item dropdown shop-dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('shop') ? 'active' : '' }}"
                        href="{{ route('shop') }}" id="shopDropdown" role="button" aria-expanded="false">
                        Shop
                    </a>
                    <ul class="dropdown-menu border-0 shadow-sm rounded-3 py-2" aria-labelledby="shopDropdown">
                        <li>
                            <h6 class="dropdown-header text-uppercase tracking-wider text-muted fs-7">Kategori Aroma
                            </h6>
                        </li>
                        <li><a class="dropdown-item py-2" href="{{ route('shop', ['gender[]' => 'Men']) }}"><i
                                    class="fas fa-mars me-2 opacity-50"></i> Pria</a></li>
                        <li><a class="dropdown-item py-2" href="{{ route('shop', ['gender[]' => 'Women']) }}"><i
                                    class="fas fa-venus me-2 opacity-50"></i> Wanita</a></li>
                        <li><a class="dropdown-item py-2" href="{{ route('shop', ['gender[]' => 'Unisex']) }}"><i
                                    class="fas fa-transgender me-2 opacity-50"></i> Unisex</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('brands.index') ? 'active' : '' }}"
                        href="{{ route('brands.index') }}">Brand</a>
                </li>
            </ul>
        </div>

        <div class="navbar-right-zone d-flex align-items-center justify-content-end gap-3" style="flex: 1;">

            <form action="{{ route('shop') }}" method="GET" class="m-0 d-flex align-items-center wrapper-search-nav">

                <div class="search-underline-container">
                    <input type="text" name="search"
                        class="form-control bg-transparent text-light border-0 py-1 nav-search-input"
                        placeholder="Cari parfum, brand..." value="{{ request('search') }}" aria-label="Search">
                </div>

                <button class="btn btn-link text-light p-0 ps-2 pe-1 d-flex align-items-center nav-search-btn"
                    type="submit" style="box-shadow: none;">
                    <i class="fas fa-search" style="font-size: 14px; opacity: 0.7;"></i>
                </button>
            </form>

            @if (Auth::check())
                <div class="nav-item dropdown">
                    <a class="nav-link text-light dropdown-toggle d-flex align-items-center gap-2" href="#"
                        role="button" data-bs-toggle="dropdown">
                        @if (Auth::user()->profile_picture)
                            <img src="{{ asset('images/' . Auth::user()->profile_picture) }}"
                                alt="{{ Auth::user()->name }}" class="rounded-circle"
                                style="width: 28px; height: 28px; object-fit: cover;">
                        @else
                            <i class="fas fa-user-circle fs-5"></i>
                        @endif
                        <span>{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user me-2"></i>
                                Profile</a></li>
                        @if (Auth::user()->role === 'admin')
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i
                                        class="fas fa-user-shield"></i> Panel Admin</a></li>
                        @endif
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item" style="cursor: pointer;">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-light"><i class="fas fa-user"></i></a>
            @endif

            <a href="{{ route('cart.index') }}" class="text-light position-relative ms-1">
                <i class="fas fa-shopping-bag fs-5"></i>
                @if (session('cart') && count(session('cart')) > 0)
                    <span
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark"
                        style="font-size: 10px; padding: 4px 6px;">
                        {{ count(session('cart')) }}
                    </span>
                @endif
            </a>
        </div>

    </div>
</nav>

<style>
    /* Mengunci pembagian layout navbar hanya di tampilan desktop (Layar Besar) */
    @media (min-width: 992px) {
        .navbar-left-zone {
            flex: 1 !important;
        }

        #navbarNav {
            flex: 2 !important;
        }

        .navbar-right-zone {
            flex: 1 !important;
        }

        .shop-dropdown:hover .dropdown-menu {
            display: block;
            margin-top: 0;
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            transition: all 0.25s ease;
        }

        .shop-dropdown .dropdown-menu {
            display: block;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.25s ease;
        }
    }

    /* CONTAINER KHUSUS UNTUK GARIS BAWAH INPUT */
    .search-underline-container {
        border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important;
        transition: all 0.3s ease;
        display: inline-block;
    }

    /* KETIKA INPUT DI-FOCUS, HANYA CONTAINER INPUT YANG BERUBAH KUNING */
    .search-underline-container:focus-within {
        border-bottom-color: #ffc107 !important;
    }

    /* MEMAKSA WRAPPER FLUID SATU BARIS */
    .wrapper-search-nav {
        white-space: nowrap;
        flex-wrap: nowrap;
    }

    /* UKURAN INPUT UTAMA (BEBAS BORDER) */
    .nav-search-input {
        width: 130px;
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: none !important;
        border-radius: 0 !important;
        padding: 4px 0 !important;
        /* Reset padding biar sejajar manis */
    }

    /* EFEK MELEBAR SAAT DIKLIK */
    .nav-search-input:focus {
        width: 190px;
        color: #fff !important;
        background-color: transparent !important;
    }

    .nav-search-input::placeholder {
        color: rgba(255, 255, 255, 0.4);
    }

    /* MEMASTIKAN TOMBOL IKON BERSIH DARI SEGALA JENIS UNDERLINE BROWSER */
    .nav-search-btn,
    .nav-search-btn:hover,
    .nav-search-btn:focus {
        text-decoration: none !important;
        border: none !important;
        outline: none !important;
    }
</style>
