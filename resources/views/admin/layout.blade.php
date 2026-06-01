<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scentify Admin Panel</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        adminbg: '#f8fafc', // slate-50
                        sidebar: '#0f172a', // slate-900
                    },
                    fontFamily: {
                        sans: ['Jost', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                        mono: ['ui-monospace', 'SFMono-Regular', 'Menlo', 'Monaco', 'Consolas', "Liberation Mono", "Courier New", 'monospace'],
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome & Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Jost', sans-serif; background-color: #f8fafc; }
        
        /* Custom Scrollbar untuk Admin */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        .sidebar-scroll::-webkit-scrollbar-thumb { background: #334155; }
        
        /* Animasi Transisi Halus */
        .fade-in { animation: fadeIn 0.5s ease-out forwards; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body class="text-slate-800 antialiased overflow-hidden flex h-screen">

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/50 z-40 lg:hidden opacity-0 pointer-events-none transition-opacity duration-300 backdrop-blur-sm"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-sidebar text-slate-300 flex flex-col transition-transform duration-300 ease-in-out transform -translate-x-full lg:translate-x-0 lg:static lg:flex-shrink-0 shadow-2xl lg:shadow-none">
        <!-- Sidebar Brand -->
        <div class="h-20 flex items-center px-8 border-b border-slate-800 shrink-0">
            <h1 class="text-2xl font-serif text-white tracking-widest uppercase flex items-center gap-2">
                Scentify <span class="text-[10px] font-sans font-bold bg-amber-500 text-slate-900 px-2 py-0.5 rounded-full tracking-normal">ADMIN</span>
            </h1>
        </div>

        <!-- Sidebar Menu -->
        <nav class="flex-1 overflow-y-auto sidebar-scroll py-6 px-4 space-y-1.5">
            <p class="px-4 text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-3 mt-4">Menu Utama</p>
            
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.dashboard') ? 'bg-amber-500/10 text-amber-400' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-home w-5 text-center {{ request()->routeIs('admin.dashboard') ? 'text-amber-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i> 
                <span class="font-medium text-sm">Dashboard</span>
            </a>

            <a href="{{ route('admin.inventory') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.inventory') ? 'bg-amber-500/10 text-amber-400' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-box-open w-5 text-center {{ request()->routeIs('admin.inventory') ? 'text-amber-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i> 
                <span class="font-medium text-sm">Inventori Produk</span>
            </a>

            <a href="{{ route('admin.brands.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.brands.*') ? 'bg-amber-500/10 text-amber-400' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-tags w-5 text-center {{ request()->routeIs('admin.brands.*') ? 'text-amber-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i> 
                <span class="font-medium text-sm">Manajemen Brand</span>
            </a>

            <a href="{{ route('admin.branches.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.branches.*') ? 'bg-amber-500/10 text-amber-400' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-store w-5 text-center {{ request()->routeIs('admin.branches.*') ? 'text-amber-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i> 
                <span class="font-medium text-sm">Manajemen Cabang</span>
            </a>

            <a href="{{ route('admin.orders.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.orders.*') ? 'bg-amber-500/10 text-amber-400' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-shopping-cart w-5 text-center {{ request()->routeIs('admin.orders.*') ? 'text-amber-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i> 
                <span class="font-medium text-sm">Riwayat Pesanan</span>
            </a>

            <a href="{{ route('admin.customers.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.customers.*') ? 'bg-amber-500/10 text-amber-400' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-users w-5 text-center {{ request()->routeIs('admin.customers.*') ? 'text-amber-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                <span class="font-medium text-sm">Pelanggan</span>
            </a>
        </nav>

        <!-- Sidebar Bottom Actions -->
        <div class="p-6 border-t border-slate-800 bg-slate-900/50 shrink-0">
            <div class="flex items-center gap-3 mb-6">
                <img src="https://ui-avatars.com/api/?name=Admin+Scentify&background=f59e0b&color=0f172a&bold=true" alt="Admin" class="w-10 h-10 rounded-full border-2 border-slate-700">
                <div class="overflow-hidden">
                    <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name ?? 'Administrator' }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email ?? 'admin@scentify.com' }}</p>
                </div>
            </div>

            <div class="space-y-2">
                <a href="{{ route('home') }}" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-lg border border-slate-700 text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-colors">
                    <i class="fas fa-external-link-alt text-xs"></i> Lihat Toko
                </a>
                <form action="{{ route('logout') ?? '#' }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-lg bg-rose-500/10 border border-rose-500/20 text-sm font-medium text-rose-500 hover:bg-rose-500 hover:text-white transition-colors">
                        <i class="fas fa-sign-out-alt text-xs"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Top Navbar -->
        <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-6 lg:px-10 z-30 shrink-0 shadow-sm">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="lg:hidden w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center hover:bg-slate-200 transition-colors focus:outline-none">
                    <i class="fas fa-bars"></i>
                </button>
                <h2 class="text-xl font-bold text-slate-800 hidden sm:block">@yield('title', 'Admin Panel')</h2>
            </div>

            {{-- <div class="flex items-center gap-4 sm:gap-6">
                <!-- Search Box (Optional visual) -->
                <div class="hidden md:flex items-center bg-slate-100 rounded-full px-4 py-2">
                    <i class="fas fa-search text-slate-400 text-sm"></i>
                    <input type="text" placeholder="Cari data..." class="bg-transparent border-none focus:outline-none text-sm ml-2 w-48 text-slate-700">
                </div>

                <!-- Notifications -->
                <button class="relative w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-slate-200 transition-colors focus:outline-none">
                    <i class="far fa-bell"></i>
                    <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-rose-500 border-2 border-white rounded-full"></span>
                </button>
            </div> --}}
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-adminbg p-6 lg:p-10 relative">
            <!-- Decorative Background Element -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-amber-500/5 rounded-full blur-[100px] pointer-events-none -z-10"></div>
            
            @yield('content')
            
        </main>
    </div>

    <!-- Script Sidebar Toggle -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.toggle('-translate-x-full');
            
            if(sidebar.classList.contains('-translate-x-full')) {
                overlay.classList.remove('opacity-100', 'pointer-events-auto');
                overlay.classList.add('opacity-0', 'pointer-events-none');
            } else {
                overlay.classList.remove('opacity-0', 'pointer-events-none');
                overlay.classList.add('opacity-100', 'pointer-events-auto');
            }
        }
    </script>
    @yield('scripts')

    <script>
        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: @json(session('success')), timer: 3000, timerProgressBar: true, showConfirmButton: false, toast: true, position: 'top-end' });
        @endif
        @if(session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal!', text: @json(session('error')), toast: true, position: 'top-end', showConfirmButton: false, timer: 4000, timerProgressBar: true });
        @endif
    </script>
</body>
</html>