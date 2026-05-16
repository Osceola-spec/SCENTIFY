<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scentify Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Jost', sans-serif;
            background-color: #f8f9fa;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #1a1d20;
            color: #fff;
            z-index: 1000;
            transition: all 0.3s;
        }

        .sidebar-brand {
            height: 70px;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 2px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.8rem 1.5rem;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.2s;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 4px solid #fff;
        }

        .sidebar-link i {
            width: 25px;
            font-size: 1.1rem;
        }

        /* Main Content Styling */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .top-navbar {
            height: 70px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
        }

        .content-wrapper {
            padding: 2rem;
            flex-grow: 1;
        }
    </style>
</head>

<body>

    <nav class="sidebar">
        <div class="sidebar-brand">
            SCENTIFY<span style="font-size: 0.8rem; font-weight: 300; margin-left: 10px; opacity: 0.7;">ADMIN</span>
        </div>
        <div class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}"
                class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i> Dashboard
            </a>

            <a href="{{ route('admin.inventory') }}"
                class="sidebar-link {{ request()->routeIs('admin.inventory') ? 'active' : '' }}">
                <i class="fas fa-box-open"></i> Inventori Produk
            </a>

            <a href="{{ route('admin.brands.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i> Manajemen Brand
            </a>

            <a href="#" class="sidebar-link">
                <i class="fas fa-shopping-cart"></i> Riwayat Pesanan
            </a>
            <a href="#" class="sidebar-link">
                <i class="fas fa-users"></i> Pelanggan
            </a>
        </div>

        <div class="position-absolute bottom-0 w-100 p-3 border-top border-secondary">
            <a href="{{ route('home') }}" class="btn btn-outline-light w-100 btn-sm mb-2"><i
                    class="fas fa-external-link-alt me-2"></i>Lihat Toko</a>
            <form action="{{ route('logout') ?? '#' }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger w-100 btn-sm"><i
                        class="fas fa-sign-out-alt me-2"></i>Logout</button>
            </form>
        </div>
    </nav>

    <div class="main-content">
        <header class="top-navbar">
            <div>
                <h5 class="mb-0 fw-bold">@yield('title', 'Admin Panel')</h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light position-relative rounded-circle p-2">
                    <i class="far fa-bell"></i>
                    <span
                        class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                </button>
                <div class="d-flex align-items-center gap-2">
                    <img src="https://ui-avatars.com/api/?name=Admin+Scentify&background=212529&color=fff"
                        alt="Admin" class="rounded-circle" width="40">
                    <div class="d-none d-md-block">
                        <h6 class="mb-0 text-dark small fw-bold">{{ auth()->user()->name ?? 'Administrator' }}</h6>
                        <small class="text-muted d-block"
                            style="font-size: 0.7rem;">{{ auth()->user()->email ?? 'admin@scentify.com' }}</small>
                    </div>
                </div>
            </div>
        </header>

        <main class="content-wrapper">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
