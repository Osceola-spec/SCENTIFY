<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">Scentify<span class="text-warning">.</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('shop') ? 'active' : '' }}" href="{{ route('shop') }}">Shop</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#koleksi">Koleksi</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button"
                        data-bs-toggle="dropdown">Kategori</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Pria</a></li>
                        <li><a class="dropdown-item" href="#">Wanita</a></li>
                        <li><a class="dropdown-item" href="#">Unisex</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('brands.index') ? 'active' : '' }}" href="{{ route('brands.index') }}">Brand</a>
                </li>
            </ul>
            <div class="d-flex align-items-center gap-3">
                <a href="#" class="text-light"><i class="fas fa-search"></i></a>

                <!-- User Icon / Dropdown -->
                @if (Auth::check())
                    <div class="nav-item dropdown">
                        <a class="nav-link text-light dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown">
                            @if (Auth::user()->profile_picture)
                                <img src="{{ asset('images/' . Auth::user()->profile_picture) }}"
                                    alt="{{ Auth::user()->name }}" class="rounded-circle"
                                    style="width: 30px; height: 30px; object-fit: cover;">
                            @else
                                <i class="fas fa-user-circle"></i>
                            @endif
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user"></i>
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
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-light"><i class="fas fa-user"></i></a>
                @endif

                <a href="{{ route('cart.index') }}" class="text-light position-relative">
                    <i class="fas fa-shopping-bag"></i>

                    <!-- Menampilkan angka badge hanya jika ada isi di dalam keranjang -->
                    @if (session('cart') && count(session('cart')) > 0)
                        <span
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark">
                            {{ count(session('cart')) }}
                        </span>
                    @endif
                </a>
            </div>
        </div>
    </div>
</nav>
