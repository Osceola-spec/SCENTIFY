<header id="navbar" class="fixed w-full z-40 top-0 transition-all duration-300 py-4 sm:py-5 px-4 sm:px-8 bg-transparent" style="pointer-events: auto;">
    <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="shrink-0 text-2xl font-serif tracking-widest uppercase hover:scale-105 transition-transform duration-300">
            Scentify
        </a>
        
        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center gap-8 text-xs font-semibold tracking-[0.2em] uppercase pointer-events-auto">
            <a href="{{ route('home') }}" class="pointer-events-auto transition-colors duration-300 relative py-1 group {{ request()->routeIs('home') ? 'text-amber-500' : 'hover:text-amber-500' }}">
                <span>Home</span>
                <span class="absolute bottom-0 left-0 h-[1.5px] bg-amber-500 transition-all duration-300 {{ request()->routeIs('home') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
            </a>
            <a href="{{ route('shop') }}" class="pointer-events-auto transition-colors duration-300 relative py-1 group {{ request()->routeIs('shop') ? 'text-amber-500' : 'hover:text-amber-500' }}">
                <span>Shop</span>
                <span class="absolute bottom-0 left-0 h-[1.5px] bg-amber-500 transition-all duration-300 {{ request()->routeIs('shop') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
            </a>
            <a href="{{ route('brands.index') }}" class="transition-colors duration-300 relative py-1 group {{ request()->routeIs('brands.index') ? 'text-amber-500' : 'hover:text-amber-500' }}">
                <span>Brands</span>
                <span class="absolute bottom-0 left-0 h-[1.5px] bg-amber-500 transition-all duration-300 {{ request()->routeIs('brands.index') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
            </a>
            <a href="{{ route('stores.index') }}" class="hover:text-amber-500 transition-colors duration-300 relative py-1 group {{ request()->routeIs('stores.index') ? 'text-amber-500' : '' }}">
                <span>Stores</span>
                <span class="absolute bottom-0 left-0 h-[1.5px] bg-amber-500 transition-all duration-300 {{ request()->routeIs('stores.index') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
            </a>
            <a href="{{ route('orders.index', ['status' => 'processing']) }}" class="hover:text-amber-500 transition-colors duration-300 relative py-1 group {{ request()->routeIs('orders.index') ? 'text-amber-500' : '' }}">
                <span>My Orders</span>
                <span class="absolute bottom-0 left-0 h-[1.5px] bg-amber-500 transition-all duration-300 {{ request()->routeIs('orders.index') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
            </a>
        </nav>

        <!-- Right Side Icons -->
        <div class="flex items-center gap-1 sm:gap-2 pointer-events-auto shrink-0">

            <a href="{{ route('wishlist.index') }}" class="relative p-2 text-lg sm:text-xl hover:text-rose-500 transition-transform duration-300 hover:scale-110 focus:outline-none">
                <i class="far fa-heart"></i>
                @auth
                    @php $wishlistCount = \App\Models\Wishlist::where('user_id', auth()->id())->count(); @endphp
                    <span id="wishlist-badge" class="absolute top-0.5 right-0.5 inline-flex items-center justify-center min-w-[16px] h-4 px-1 text-[9px] font-bold leading-none text-white bg-rose-500 rounded-full transition-all duration-300 {{ $wishlistCount > 0 ? '' : 'opacity-0' }}">
                        {{ $wishlistCount }}
                    </span>
                @else
                    <span id="wishlist-badge" class="absolute top-0.5 right-0.5 inline-flex items-center justify-center min-w-[16px] h-4 px-1 text-[9px] font-bold leading-none text-white bg-rose-500 rounded-full transition-all duration-300 opacity-0">0</span>
                @endauth
            </a>
            
            <!-- Keranjang Belanja -->
            <a href="{{ route('cart.index') }}" class="relative p-2 text-lg sm:text-xl hover:text-amber-500 transition-transform duration-300 hover:scale-110 focus:outline-none">
                <i class="fas fa-shopping-bag"></i>
                @if (session('cart') && count(session('cart')) > 0)
                    <span id="cart-badge" class="absolute top-0.5 right-0.5 inline-flex items-center justify-center min-w-[16px] h-4 px-1 text-[9px] font-bold leading-none text-black bg-amber-400 rounded-full transition-all duration-300">
                        {{ count(session('cart')) }}
                    </span>
                @else
                    <span id="cart-badge" class="absolute top-0.5 right-0.5 inline-flex items-center justify-center min-w-[16px] h-4 px-1 text-[9px] font-bold leading-none text-black bg-amber-400 rounded-full transition-all duration-300 opacity-0">
                        0
                    </span>
                @endif
            </a>

            <!-- User Profile / Auth Section -->
            @auth
                <div class="relative group ml-1">
                    <button type="button" class="flex items-center gap-2 focus:outline-none hover:text-amber-500 transition-colors px-2 py-1.5 rounded-xl">
                        @if(Auth::user()->profile_picture)
                            <img src="{{ asset('images/' . Auth::user()->profile_picture) }}" alt="Profile" class="w-7 h-7 sm:w-8 sm:h-8 rounded-full border border-slate-200 dark:border-white/10 object-cover shadow-sm transition-transform group-hover:scale-105 shrink-0">
                        @else
                            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-slate-100 dark:bg-zinc-800 flex items-center justify-center border border-slate-200 dark:border-white/10 shadow-sm text-slate-500 dark:text-zinc-400 transition-transform group-hover:scale-105 shrink-0">
                                <i class="fas fa-user text-[10px] sm:text-xs"></i>
                            </div>
                        @endif
                        <span class="hidden lg:block text-xs font-semibold max-w-[80px] truncate">{{ Auth::user()->username }}</span>
                        <i class="fas fa-chevron-down text-[8px] hidden md:block transition-transform duration-300 group-hover:rotate-180"></i>
                    </button>
                    
                    <!-- Dropdown Menu Profil -->
                    <div class="absolute right-0 top-full w-56 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 pt-2">
                        <div class="bg-white dark:bg-darkcard border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl p-2">
                            
                            <!-- Header Info -->
                            <div class="px-3 py-3 border-b border-slate-100 dark:border-white/5 mb-2">
                                <p class="text-[10px] uppercase font-mono text-slate-400 dark:text-zinc-500">Masuk sebagai</p>
                                <p class="text-sm font-bold text-slate-900 dark:text-white truncate mt-0.5">{{ Auth::user()->name }}</p>
                            </div>
                            
                            <a href="{{ route('profile') }}" class="flex items-center gap-3 px-3 py-2.5 text-xs font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-zinc-800 transition-colors text-slate-700 dark:text-zinc-300 hover:text-amber-500">
                                <i class="far fa-user w-4 text-center"></i> Profil Saya
                            </a>
                            
                            <a href="{{ route('addresses.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-xs font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-zinc-800 transition-colors text-slate-700 dark:text-zinc-300 hover:text-amber-500">
                                <i class="fas fa-map-marker-alt w-4 text-center"></i> Alamat Saya
                            </a>
                            
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-xs font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-zinc-800 transition-colors text-slate-700 dark:text-zinc-300 hover:text-amber-500">
                                    <i class="fas fa-shield-alt w-4 text-center"></i> Panel Admin
                                </a>
                            @endif
                            
                            <div class="h-px bg-slate-100 dark:bg-white/5 my-1.5"></div>
                            
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-xs font-medium rounded-xl text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors text-left">
                                    <i class="fas fa-sign-out-alt w-4 text-center"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="relative p-2 text-lg sm:text-xl hover:text-amber-500 transition-transform duration-300 hover:scale-110 focus:outline-none" title="Login / Register">
                    <i class="far fa-user"></i>
                </a>
            @endauth
            
            <!-- Mobile Menu Toggle -->
            <button onclick="toggleMobileMenu()" class="md:hidden text-lg sm:text-xl focus:outline-none hover:text-amber-500 transition-colors p-2 ml-1">
                <i class="fas fa-bars" id="menu-icon"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden mt-3 bg-white/95 dark:bg-darkbg/95 backdrop-blur-xl border border-slate-200 dark:border-white/5 py-5 px-5 space-y-1 rounded-2xl shadow-2xl">
        <a href="{{ route('home') }}" class="flex items-center font-medium text-sm hover:text-amber-500 transition-colors py-2.5 px-2 rounded-xl hover:bg-slate-50 dark:hover:bg-white/5">Beranda</a>
        <a href="{{ route('shop') }}" class="flex items-center font-medium text-sm hover:text-amber-500 transition-colors py-2.5 px-2 rounded-xl hover:bg-slate-50 dark:hover:bg-white/5">Shop</a>
        <a href="{{ route('brands.index') }}" class="flex items-center font-medium text-sm hover:text-amber-500 transition-colors py-2.5 px-2 rounded-xl hover:bg-slate-50 dark:hover:bg-white/5">Brands</a>
        <a href="{{ route('stores.index') }}" class="flex items-center font-medium text-sm hover:text-amber-500 transition-colors py-2.5 px-2 rounded-xl hover:bg-slate-50 dark:hover:bg-white/5">Stores</a>
        <a href="{{ route('orders.index') }}" class="flex items-center font-medium text-sm hover:text-amber-500 transition-colors py-2.5 px-2 rounded-xl hover:bg-slate-50 dark:hover:bg-white/5">My Orders</a>
        
        <div class="h-px bg-slate-200 dark:bg-white/10 !my-3"></div>
        
        @auth
            <div class="flex items-center gap-3 px-2 py-2">
                @if(Auth::user()->profile_picture)
                    <img src="{{ asset('images/' . Auth::user()->profile_picture) }}" alt="Profile" class="w-9 h-9 rounded-full border border-slate-200 dark:border-white/10 object-cover shadow-sm shrink-0">
                @else
                    <div class="w-9 h-9 rounded-full bg-slate-100 dark:bg-zinc-800 flex items-center justify-center border border-slate-200 dark:border-white/10 shadow-sm text-slate-500 dark:text-zinc-400 shrink-0">
                        <i class="fas fa-user text-xs"></i>
                    </div>
                @endif
                <div class="min-w-0">
                    <p class="text-[10px] uppercase font-mono text-slate-400 dark:text-zinc-500">Masuk sebagai</p>
                    <p class="font-bold text-sm text-slate-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                </div>
            </div>
            
            <a href="{{ route('profile') }}" class="flex items-center gap-3 font-medium text-sm hover:text-amber-500 transition-colors py-2.5 px-2 rounded-xl hover:bg-slate-50 dark:hover:bg-white/5">
                <i class="far fa-user w-4 text-center text-slate-400"></i> Profil Saya
            </a>
            <a href="{{ route('addresses.index') }}" class="flex items-center gap-3 font-medium text-sm hover:text-amber-500 transition-colors py-2.5 px-2 rounded-xl hover:bg-slate-50 dark:hover:bg-white/5">
                <i class="fas fa-map-marker-alt w-4 text-center text-slate-400"></i> Alamat Saya
            </a>
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 font-medium text-sm hover:text-amber-500 transition-colors py-2.5 px-2 rounded-xl hover:bg-slate-50 dark:hover:bg-white/5">
                    <i class="fas fa-shield-alt w-4 text-center text-slate-400"></i> Panel Admin
                </a>
            @endif
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 font-medium text-sm text-rose-500 hover:text-rose-600 transition-colors py-2.5 px-2 rounded-xl hover:bg-rose-50 dark:hover:bg-rose-500/10 text-left">
                    <i class="fas fa-sign-out-alt w-4 text-center"></i> Keluar
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="flex items-center gap-3 font-bold text-sm text-amber-500 hover:text-amber-600 transition-colors py-2.5 px-2 rounded-xl hover:bg-amber-50 dark:hover:bg-amber-500/10">
                <i class="far fa-user w-4 text-center"></i> Login / Register
            </a>
        @endauth
    </div>
</header>
