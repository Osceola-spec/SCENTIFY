@extends('base.base')

@section('content')
<div id="filterOverlay" class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 lg:hidden" onclick="closeMobileFilter()"></div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32">
    <nav class="mb-8 reveal">
        <ol class="flex items-center space-x-2 text-xs font-mono uppercase tracking-wider text-slate-400 dark:text-zinc-500">
            <li><a href="{{ route('home') }}" class="hover:text-amber-500 transition-colors">Home</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-amber-500 font-semibold">Shop</li>
        </ol>
    </nav>

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-12 pb-8 border-b border-slate-200 dark:border-white/5 reveal">
        <div>
            <span class="text-xs font-mono text-amber-600 dark:text-amber-400 uppercase tracking-widest font-semibold">Discovery</span>
            <h1 class="text-3xl md:text-5xl font-serif mt-1 text-slate-950 dark:text-white">Semua Parfum</h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-zinc-400 mt-2">
                Menampilkan {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk terpilih
            </p>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center justify-between lg:justify-end gap-3 sm:gap-4 w-full lg:w-auto">
            <div class="relative w-full sm:w-56 md:w-64 order-1 sm:order-none">
                <input type="text" name="search" form="filterForm" value="{{ request('search') }}" 
                       placeholder="Cari nama parfum..." 
                       class="w-full appearance-none bg-white dark:bg-darkcard border border-slate-200 dark:border-white/10 rounded-full pl-10 pr-4 py-2.5 text-xs font-semibold focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all text-slate-800 dark:text-zinc-300 shadow-sm placeholder-slate-400 dark:placeholder-zinc-500">
                <button type="submit" form="filterForm" class="absolute inset-y-0 left-0 pl-3.5 pr-2 flex items-center text-slate-400 hover:text-amber-500 transition-colors focus:outline-none">
                    <i class="fas fa-search text-[11px]"></i>
                </button>
            </div>

            <div class="flex items-center justify-between w-full sm:w-auto gap-3 sm:gap-4 order-2 sm:order-none">
                <button type="button" onclick="openMobileFilter()" class="lg:hidden flex items-center gap-2 px-5 py-2.5 rounded-full bg-white dark:bg-darkcard border border-slate-200 dark:border-white/10 text-xs font-mono font-bold uppercase tracking-wider text-slate-800 dark:text-zinc-300 shadow-md focus:outline-none">
                    <i class="fas fa-sliders text-amber-500"></i> Filter
                </button>

                <div class="flex items-center gap-3">
                    <label for="sortSelect" class="text-xs font-mono uppercase text-slate-400 hidden sm:inline-block">Urutkan:</label>
                    <div class="relative">
                        <select name="sort" form="filterForm" id="sortSelect" onchange="document.getElementById('filterForm').submit()" 
                                class="appearance-none bg-white dark:bg-darkcard border border-slate-200 dark:border-white/10 rounded-full px-6 py-2.5 pr-10 text-xs font-semibold focus:outline-none focus:border-amber-500 transition-all text-slate-800 dark:text-zinc-300 shadow-sm cursor-pointer">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-slate-400">
                            <i class="fas fa-chevron-down text-[10px]"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- @if(config('app.debug'))
        <div class="mb-4 text-xs text-rose-500">
            Debug activePromotion: @if(isset($activePromotion) && $activePromotion) ID={{ $activePromotion->id }}, ends_at={{ $activePromotion->ends_at }} @else <span class="text-rose-400">NULL</span> @endif
        </div>
    @endif --}}
    @if(!empty($upcomingPromotion))
        <div id="promo-upcoming-banner" class="mb-6 p-4 rounded-xl bg-amber-500/5 border border-amber-500/10 flex items-center justify-between gap-4">
            <div>
                <div class="text-sm font-mono text-amber-600 uppercase">Promo Akan Datang</div>
                <div class="text-lg font-semibold text-slate-900 dark:text-white">{{ optional($upcomingPromotion)->title }}</div>
                <div class="text-xs text-slate-700 dark:text-zinc-300">{{ optional($upcomingPromotion)->description }}</div>
            </div>
            <div class="text-right">
                <div class="text-xs text-slate-600 dark:text-zinc-300">Mulai dalam:</div>
                <div id="promo-starts-countdown" class="text-xl font-mono font-bold text-amber-600">--:--:--</div>
            </div>
            <input type="hidden" id="promo-starts-at" value="{{ optional(optional($upcomingPromotion)->starts_at)->toIsoString() }}">
        </div>
    @endif

    @if(!empty($activePromotion))
        <div id="promo-banner" class="mb-6 p-4 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-between gap-4">
            <div>
                <div class="text-sm font-mono text-amber-600 uppercase">Promo Aktif</div>
                <div class="text-lg font-semibold text-slate-900 dark:text-white">{{ optional($activePromotion)->title }}</div>
                <div class="text-xs text-slate-700 dark:text-zinc-300">{{ optional($activePromotion)->description }}</div>
            </div>
            <div class="text-right">
                @if(optional($activePromotion)->ends_at)
                    <div class="text-xs text-slate-600 dark:text-zinc-300">Sisa waktu:</div>
                    <div id="promo-countdown" class="text-xl font-mono font-bold text-amber-600">--:--:--</div>
                    @if(optional($activePromotion)->applies_to_all === false && optional($activePromotion)->product)
                        <a href="{{ route('products.edit', optional($activePromotion)->product->id) }}" class="text-xs text-slate-700 dark:text-zinc-300">Lihat produk terkait</a>
                    @endif
                    <input type="hidden" id="promo-ends-at" value="{{ optional(optional($activePromotion)->ends_at)->toIsoString() }}">
                @else
                    <div class="text-sm text-slate-600 dark:text-zinc-300">Promo aktif (tanpa tenggat waktu)</div>
                @endif
            </div>
        </div>
    @endif


    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <aside id="filterSidebar" class="fixed inset-y-0 left-0 z-50 w-80 max-w-[85vw] bg-white dark:bg-darkbg lg:bg-transparent lg:dark:bg-transparent p-6 lg:p-0 border-r border-slate-200 dark:border-white/10 lg:border-none shadow-2xl lg:shadow-none transform -translate-x-full lg:translate-x-0 overflow-y-auto lg:overflow-visible lg:static lg:col-span-3 lg:w-full reveal">
            <div class="flex lg:hidden justify-end mb-6">
                <button type="button" onclick="closeMobileFilter()" class="w-8 h-8 rounded-full border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>

            <form action="{{ route('shop') }}" method="GET" id="filterForm">
                <div class="lg:sticky lg:top-28 space-y-8">
                    <div class="flex items-center justify-between">
                        <h5 class="font-serif text-lg font-semibold tracking-wide flex items-center gap-2">
                            <i class="fas fa-sliders text-sm text-amber-500"></i> Filter
                        </h5>
                        <a href="{{ route('shop') }}" class="text-xs font-mono uppercase text-slate-400 hover:text-amber-500 transition-colors">Reset</a>
                    </div>

                    <div class="pb-6 border-b border-slate-200 dark:border-white/5">
                        <h6 class="text-xs font-mono uppercase tracking-wider text-slate-400 mb-4 font-bold">Kategori</h6>
                        <div class="space-y-3">
                            @foreach (['Men', 'Women', 'Unisex'] as $gender)
                                <label class="flex items-center group cursor-pointer text-sm text-slate-600 dark:text-zinc-300 hover:text-amber-500 transition-colors">
                                    <input type="checkbox" name="gender[]" value="{{ $gender }}" id="cat{{ $gender }}"
                                           {{ in_array($gender, request('gender', [])) ? 'checked' : '' }}
                                           class="rounded border-slate-300 dark:border-zinc-700 text-amber-500 focus:ring-amber-500 bg-transparent mr-3 w-4 h-4 transition-colors">
                                    <span class="font-medium">{{ $gender }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="pb-6 border-b border-slate-200 dark:border-white/5">
                        <h6 class="text-xs font-mono uppercase tracking-wider text-slate-400 mb-4 font-bold">Brand</h6>
                        <div class="space-y-3 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                            @foreach ($brands as $brand)
                                <label class="flex items-center group cursor-pointer text-sm text-slate-600 dark:text-zinc-300 hover:text-amber-500 transition-colors">
                                    <input type="checkbox" name="brand[]" value="{{ $brand->id }}" id="brand{{ $brand->id }}"
                                           {{ in_array($brand->id, (array) request('brand', [])) ? 'checked' : '' }}
                                           class="rounded border-slate-300 dark:border-zinc-700 text-amber-500 focus:ring-amber-500 bg-transparent mr-3 w-4 h-4 transition-colors">
                                    <span class="font-medium">{{ $brand->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <h6 class="text-xs font-mono uppercase tracking-wider text-slate-400 mb-4 font-bold">Harga Maksimal</h6>
                        <div class="relative pt-4 px-2 group">
                            <input type="range" name="max_price" id="priceRange" min="0" max="5000000" step="100000" 
                                   value="{{ request('max_price', 5000000) }}"
                                   class="w-full h-1 bg-slate-200 dark:bg-zinc-800 rounded-lg appearance-none cursor-pointer accent-amber-500">
                            
                            <div id="rangeTooltip" class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-slate-950 dark:bg-amber-500 text-white dark:text-black text-[10px] font-mono font-bold px-2 py-0.5 rounded shadow-lg pointer-events-none hidden group-hover:block group-active:block transition-all duration-150">
                                Rp 5.000.000
                            </div>
                        </div>

                        <div class="flex justify-between text-xs text-slate-400 font-mono mt-3">
                            <span>Rp 0</span>
                            <span id="priceLabel">Rp 5.000.000</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-slate-900 dark:bg-amber-400 text-white dark:text-black font-semibold tracking-wide py-3.5 rounded-xl hover:bg-amber-500 dark:hover:bg-amber-300 active:scale-95 transition-all duration-300 text-sm shadow-lg shadow-amber-500/5">
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </aside>

        <main class="lg:col-span-9">
            @php
                $activeFilters = [];

                if (request()->filled('search')) {
                    $activeFilters[] = ['type' => 'Pencarian', 'label' => '"' . request('search') . '"'];
                }

                $reqGenders = (array) request('gender', []);
                foreach ($reqGenders as $g) {
                    if (!empty($g)) {
                        $activeFilters[] = ['type' => 'Kategori', 'label' => $g];
                    }
                }

                $reqBrands = (array) request('brand', []);
                foreach ($reqBrands as $bId) {
                    if (!empty($bId)) {
                        $brandObj = $brands->firstWhere('id', $bId);
                        if ($brandObj) {
                            $activeFilters[] = ['type' => 'Brand', 'label' => $brandObj->name];
                        }
                    }
                }
            @endphp

            @if (count($activeFilters) > 0)
                <div class="flex items-start sm:items-center justify-between bg-white dark:bg-darkcard border border-slate-200 dark:border-white/5 rounded-2xl mb-8 p-4 shadow-sm reveal">
                    <div class="flex items-center flex-wrap gap-2 sm:gap-3">
                        <i class="fas fa-filter text-amber-500 text-xs mr-1 mt-1 sm:mt-0"></i>
                        <span class="text-xs text-slate-500 dark:text-zinc-400">Filter Aktif:</span>
                        
                        @foreach ($activeFilters as $filter)
                            <span class="bg-slate-900 dark:bg-amber-400 text-white dark:text-black text-[11px] font-semibold px-3 py-1 rounded-full flex items-center gap-1 shadow-sm">
                                <span class="opacity-70 font-normal hidden sm:inline-block">{{ $filter['type'] }}:</span> {{ $filter['label'] }}
                            </span>
                        @endforeach
                    </div>
                    <a href="{{ route('shop') }}" class="flex-shrink-0 w-8 h-8 rounded-full border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:border-rose-500/30 transition-colors ml-4" title="Hapus Semua Filter">
                        <i class="fas fa-times text-xs"></i>
                    </a>
                </div>
            @endif

            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6" id="product-container">
                @forelse($products as $product)
                    <div class="perspective-1000 reveal product-card" data-id="{{ $product->id }}" data-name="{{ e($product->name) }}" data-brand="{{ e($product->brand->name ?? '') }}" data-description="{{ e(strip_tags($product->description ?? '')) }}">
                        <div class="tilt-card bg-white dark:bg-darkcard rounded-2xl sm:rounded-3xl p-3 sm:p-4 border border-slate-200 dark:border-white/5 shadow-md flex flex-col justify-between h-auto min-h-[300px] sm:min-h-[360px] transition-all duration-300 group relative">
                            
                            <div class="w-full h-32 sm:h-44 overflow-hidden rounded-xl sm:rounded-2xl bg-slate-100 dark:bg-zinc-900 relative">
                                <img src="{{ $product->image_url ? (strpos($product->image_url, 'http') === 0 ? $product->image_url : asset('product_image/' . $product->image_url)) : 'https://placehold.co/400x500?text=Scentify' }}"
                                     alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            </div>

                            <div class="mt-3 flex-grow flex flex-col justify-start">
                                <div>
                                    <small class="text-[9px] sm:text-[10px] font-mono text-amber-600 dark:text-amber-400 uppercase tracking-widest font-semibold block">
                                        {{ $product->brand->name ?? 'Unknown Brand' }}
                                    </small>
                                    <h5 class="text-sm sm:text-base font-serif font-bold text-slate-900 dark:text-white mt-0.5 group-hover:text-amber-500 transition-colors line-clamp-1" title="{{ $product->name }}">
                                        {{ $product->name }}
                                    </h5>
                                    
                                    @php
                                        $avgRating   = $product->reviews->avg('rating') ?? 0;
                                        $reviewCount = $product->reviews->count();
                                    @endphp
                                    @if ($reviewCount > 0)
                                        <div class="flex items-center gap-0.5 mt-1">
                                            @for ($s = 1; $s <= 5; $s++)
                                                <i class="fas fa-star text-[9px] {{ $s <= round($avgRating) ? 'text-amber-400' : 'text-slate-200 dark:text-zinc-700' }}"></i>
                                            @endfor
                                            <span class="text-[9px] text-slate-400 font-mono ml-1">{{ number_format($avgRating, 1) }} ({{ $reviewCount }})</span>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-0.5 mt-1">
                                            @for ($s = 1; $s <= 5; $s++)
                                                <i class="far fa-star text-[9px] text-slate-200 dark:text-zinc-700"></i>
                                            @endfor
                                            <span class="text-[9px] text-slate-400 font-mono ml-1">Belum ada ulasan</span>
                                        </div>
                                    @endif
                                </div>

                                @php
                                    $basePrice = $product->variants->first()->price ?? 0;
                                    $promoApplies = false;
                                    $discountedPrice = null;
                                    if (!empty($activePromotion)) {
                                        $promoApplies = $activePromotion->applies_to_all || ($activePromotion->product_id && $activePromotion->product_id == $product->id);
                                        if ($promoApplies) {
                                            $dv = (float) $activePromotion->discount_value;
                                            if ($activePromotion->discount_type === 'percent') {
                                                $discountedPrice = max(0, round($basePrice * (1 - ($dv / 100))));
                                            } else {
                                                $discountedPrice = max(0, round($basePrice - $dv));
                                            }
                                        }
                                    }
                                @endphp

                                <p class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white mt-1">
                                    @if($discountedPrice !== null)
                                        <span class="text-xs text-slate-400 line-through mr-2">Rp {{ number_format($basePrice, 0, ',', '.') }}</span>
                                        <span class="text-xs text-amber-600">Rp {{ number_format($discountedPrice, 0, ',', '.') }}</span>
                                    @else
                                        Rp {{ number_format($basePrice, 0, ',', '.') }}
                                    @endif
                                </p>
                            </div>

                            <div class="mt-3 flex items-center gap-2 w-full">
                                @if (auth()->check() && auth()->user()->role === 'admin')
                                    <a href="{{ route('products.edit', $product->id) }}"
                                       class="flex-grow py-1.5 sm:py-2 text-[10px] sm:text-xs font-semibold tracking-wide bg-blue-500 hover:bg-blue-600 text-white rounded-full transition-colors duration-300 shadow-md focus:outline-none flex items-center justify-center gap-1.5"
                                       title="Edit Produk">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST" class="flex-grow flex">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete('{{ $product->id }}', '{{ $product->name }}')"
                                                class="w-full py-1.5 sm:py-2 text-[10px] sm:text-xs font-semibold tracking-wide bg-rose-500 hover:bg-rose-600 text-white rounded-full transition-colors duration-300 shadow-md focus:outline-none flex items-center justify-center gap-1.5"
                                                title="Hapus Produk">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                @else
                                    <button type="button" onclick="toggleWishlist(this, event, {{ $product->id }})" class="w-7 h-7 sm:w-8 sm:h-8 shrink-0 flex items-center justify-center rounded-full bg-slate-100 dark:bg-zinc-800 text-slate-500 dark:text-zinc-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors shadow-sm focus:outline-none" title="Tambah/Hapus Wishlist">
                                        <i class="{{ in_array($product->id, $wishlistedProductIds ?? []) ? 'fas text-rose-500' : 'far' }} fa-heart text-[10px] sm:text-xs transition-transform duration-300"></i>
                                    </button>

                                   @if ($product->variants->isNotEmpty())
                                        @auth
                                            <button type="button" class="variant-selector-btn flex-grow py-1.5 sm:py-2 text-[10px] sm:text-xs font-semibold tracking-wide bg-slate-900 dark:bg-amber-400 text-white dark:text-black rounded-full hover:bg-amber-500 dark:hover:bg-amber-300 transition-colors duration-300 shadow-md focus:outline-none flex items-center justify-center gap-1.5"
                                                    data-product-id="{{ $product->id }}"
                                                    data-product-name="{{ $product->name }}"
                                                    data-product-brand="{{ $product->brand->name ?? 'Unknown Brand' }}"
                                                    data-product-image="{{ $product->image_url ? (strpos($product->image_url, 'http') === 0 ? $product->image_url : asset('product_image/' . $product->image_url)) : 'https://placehold.co/400x500?text=Scentify' }}"
                                                    data-product-description="{{ $product->description ?? '' }}"
                                                    data-product-images="{{ json_encode($product->images->map(fn($img) => $img->url)) }}"
                                                    data-variants="{{ json_encode($product->variants) }}"
                                                    data-reviews="{{ json_encode($product->reviews->map(fn($r) => [
                                                        'rating' => $r->rating,
                                                        'comment' => $r->comment,
                                                        'date' => $r->created_at->format('d M Y'),
                                                        'user_name' => $r->user->name ?? 'Pelanggan Scentify'
                                                    ])) }}">
                                                <i class="fas fa-cart-plus"></i> Beli
                                            </button>
                                        @else
                                            <a href="{{ route('login') }}" class="flex-grow py-1.5 sm:py-2 text-center text-[10px] sm:text-xs font-semibold tracking-wide bg-slate-900 dark:bg-amber-400 text-white dark:text-black rounded-full hover:bg-amber-500 dark:hover:bg-amber-300 transition-colors duration-300 shadow-md flex items-center justify-center gap-1.5"
                                               onclick="event.preventDefault(); showLoginAlert(this.href)">
                                                <i class="fas fa-cart-plus"></i> Beli
                                            </a>
                                        @endauth
                                    @else
                                        <button class="flex-grow py-1.5 sm:py-2 text-[10px] sm:text-xs font-semibold tracking-wide bg-slate-300 dark:bg-zinc-800 text-slate-500 dark:text-zinc-600 rounded-full cursor-not-allowed flex items-center justify-center gap-1.5" disabled>
                                            <i class="fas fa-times-circle"></i> Habis
                                        </button>
                                    @endif

                                    <button type="button" onclick="shareProduct('{{ addslashes($product->name) }}', event)" class="w-7 h-7 sm:w-8 sm:h-8 shrink-0 flex items-center justify-center rounded-full bg-slate-100 dark:bg-zinc-800 text-slate-500 dark:text-zinc-400 hover:text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-500/10 transition-colors shadow-sm focus:outline-none" title="Bagikan Produk">
                                        <i class="fas fa-share-nodes text-[10px] sm:text-xs"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-20 reveal">
                        <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-darkcard border border-slate-200 dark:border-white/5 flex items-center justify-center mx-auto mb-4 text-slate-400">
                            <i class="fas fa-box-open text-xl"></i>
                        </div>
                        <h3 class="font-serif text-lg">Tidak ada produk yang sesuai.</h3>
                        <p class="text-xs text-slate-400 mt-1">Coba gunakan kata kunci lain atau kurangi filter Anda.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-16 pt-8 border-t border-slate-200 dark:border-white/5 flex justify-center custom-pagination reveal">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </main>
    </div>
</div>

<div id="variantModal" class="fixed inset-0 z-[1000] flex items-center justify-center p-4 bg-black/60 backdrop-blur-md opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="bg-white dark:bg-darkcard border border-slate-200 dark:border-white/5 rounded-3xl w-full max-w-3xl overflow-hidden shadow-2xl transform scale-95 transition-transform duration-300 max-h-[90vh] overflow-y-auto">
        <div class="p-6 md:p-8">
            <div class="flex justify-end mb-2">
                <button onclick="closeVariantModal()" class="w-8 h-8 rounded-full border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors focus:outline-none">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
                <div class="md:col-span-5">
                    {{-- Gambar Utama --}}
                    <div class="w-full h-64 md:h-72 overflow-hidden rounded-2xl bg-slate-100 dark:bg-zinc-900">
                        <img id="modalProductImage" src="" alt="Product" class="w-full h-full object-cover transition-all duration-300">
                    </div>
                    {{-- Thumbnail Gallery --}}
                    <div id="modalGallery" class="flex gap-2 mt-3 overflow-x-auto pb-1"></div>
                </div>

                <div class="md:col-span-7 flex flex-col justify-between h-full">
                    <div>
                        <small id="modalProductBrand" class="text-[10px] font-mono text-amber-600 dark:text-amber-400 uppercase tracking-widest font-semibold block"></small>
                        <h4 id="modalProductName" class="text-2xl font-serif font-bold text-slate-950 dark:text-white mt-1"></h4>
                        <div id="modalProductPrice" class="text-xl font-bold text-amber-600 dark:text-amber-400 mt-3">Rp 0</div>
                        <p id="modalProductDescription" class="text-xs text-slate-500 dark:text-zinc-400 leading-relaxed mt-4 line-clamp-3"></p>

                        <h6 class="text-xs font-mono uppercase text-slate-400 tracking-wider mt-6 mb-3 font-semibold">Ukuran Tersedia:</h6>
                        <div id="variantsList" class="flex flex-wrap gap-2"></div>

                        <h6 class="text-xs font-mono uppercase text-slate-400 tracking-wider mt-5 mb-3 font-semibold">Kuantitas:</h6>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center bg-slate-100 dark:bg-zinc-800/50 rounded-xl p-1 border border-slate-200 dark:border-white/5">
                                <button type="button" onclick="decrementQuantity()" class="w-8 h-8 flex items-center justify-center text-slate-500 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-white dark:hover:bg-zinc-700 rounded-lg transition-all focus:outline-none">
                                    <i class="fas fa-minus text-[10px]"></i>
                                </button>
                                <input type="number" id="modalQuantity" value="1" min="1" readonly
                                       class="w-10 text-center bg-transparent text-sm font-bold text-slate-900 dark:text-white focus:outline-none appearance-none">
                                <button type="button" onclick="incrementQuantity()" class="w-8 h-8 flex items-center justify-center text-slate-500 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-white dark:hover:bg-zinc-700 rounded-lg transition-all focus:outline-none">
                                    <i class="fas fa-plus text-[10px]"></i>
                                </button>
                            </div>
                            <span id="modalStockStatus" class="text-xs text-slate-500 font-medium hidden"></span>
                        </div>

                        <div id="variantNotice" class="mt-4 text-xs text-rose-500 flex items-center gap-1.5 font-medium">
                            <i class="fas fa-exclamation-circle"></i> Pilih salah satu varian terlebih dahulu.
                        </div>
                    </div>

                    <div class="border-t border-slate-200 dark:border-white/5 mt-8 pt-6">
                        <h5 class="text-sm font-serif font-bold text-slate-950 dark:text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-star text-amber-500 text-xs"></i> 
                            Ulasan Pelanggan (<span id="modalReviewCount">0</span>)
                        </h5>
                        <div id="modalReviewsList" class="space-y-3 max-h-48 overflow-y-auto pr-2 custom-scrollbar"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mt-8">
                        <button type="button" id="addToCartBtn" onclick="submitVariantSelection()" disabled
                                class="py-3.5 font-semibold text-xs tracking-wider uppercase bg-slate-900 dark:bg-amber-400 text-white dark:text-black rounded-xl hover:bg-amber-500 dark:hover:bg-amber-300 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                            Tambah ke Keranjang
                        </button>
                        <button type="button" onclick="closeVariantModal()"
                                class="py-3.5 font-semibold text-xs tracking-wider uppercase border border-slate-200 dark:border-white/10 text-slate-700 dark:text-zinc-300 rounded-xl hover:bg-slate-50 dark:hover:bg-zinc-800 transition-all focus:outline-none">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="hiddenCartForm" action="" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="variant_id" id="hiddenVariantId" value="">
    <input type="hidden" name="quantity" id="hiddenQuantity" value="1">
</form>

<style>
    .custom-pagination .page-link { color: inherit; border: none; background: transparent; }
    .custom-pagination .page-item.active .page-link {
        background-color: #f59e0b !important; color: #000 !important;
        border-radius: 9999px; width: 36px; height: 36px;
        display: flex; align-items: center; justify-content: center; font-weight: 700;
    }
    .custom-pagination .page-link:hover { background-color: rgba(245, 158, 11, 0.1) !important; color: #f59e0b !important; border-radius: 9999px; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: rgba(156, 163, 175, 0.3); border-radius: 10px; }
    input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number] { -moz-appearance: textfield; }
</style>

<script>
    const isDarkMode = () => document.documentElement.classList.contains('dark');

    // Variable global untuk pencatatan modal varian produk
    let selectedVariantId = null;
    let maxVariantStock = 0;

    // ==========================================
    // 1. MOBILE FILTER DRAWER
    // ==========================================
    function openMobileFilter() {
        const sidebar = document.getElementById('filterSidebar');
        const overlay = document.getElementById('filterOverlay');
        if (sidebar) sidebar.classList.remove('-translate-x-full');
        if (overlay) overlay.classList.remove('opacity-0', 'pointer-events-none');
    }

    function closeMobileFilter() {
        const sidebar = document.getElementById('filterSidebar');
        const overlay = document.getElementById('filterOverlay');
        if (sidebar) sidebar.classList.add('-translate-x-full');
        if (overlay) overlay.classList.add('opacity-0', 'pointer-events-none');
    }

    // ==========================================
    // 2. LOGIC PRICE RANGE SLIDER & DISPATCHERS
    // ==========================================
    document.addEventListener('DOMContentLoaded', () => {
        const rangeInput = document.getElementById('priceRange');
        const rangeTooltip = document.getElementById('rangeTooltip');
        const priceLabel = document.getElementById('priceLabel');

        function updateSliderUI() {
            if (!rangeInput) return;
            const val = Number(rangeInput.value);
            const formatted = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val);
            
            if (priceLabel) priceLabel.textContent = formatted;
            if (rangeTooltip) {
                rangeTooltip.textContent = formatted;
                const min = rangeInput.min ? Number(rangeInput.min) : 0;
                const max = rangeInput.max ? Number(rangeInput.max) : 5000000;
                const pct = ((val - min) / (max - min)) * 100;
                rangeTooltip.style.left = `calc(${pct}% + (${8 - pct * 0.15}px))`;
            }
        }

        if (rangeInput) {
            rangeInput.addEventListener('input', updateSliderUI);
            updateSliderUI();
        }

        // Delegasi Event Click tombol Beli / Variant Selector
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('.variant-selector-btn');
            if (btn) {
                openVariantModal(btn);
            }
        });
    });

    // ==========================================
    // 3. VARIANT SELECTION & MODAL IMPLEMENTATION
    // ==========================================
    function openVariantModal(btn) {
        const id = btn.getAttribute('data-product-id');
        const name = btn.getAttribute('data-product-name');
        const brand = btn.getAttribute('data-product-brand');
        const image = btn.getAttribute('data-product-image');
        const desc = btn.getAttribute('data-product-description');
        const images = JSON.parse(btn.getAttribute('data-product-images') || '[]');
        const variants = JSON.parse(btn.getAttribute('data-variants') || '[]');
        const reviews = JSON.parse(btn.getAttribute('data-reviews') || '[]');

        // Pengisian Data Utama Modal
        document.getElementById('modalProductName').textContent = name;
        document.getElementById('modalProductBrand').textContent = brand;
        document.getElementById('modalProductDescription').textContent = desc || 'Tidak ada deskripsi.';
        document.getElementById('modalProductImage').src = image;
        document.getElementById('modalQuantity').value = 1;
        
        // Reset State Varian Terpilih
        selectedVariantId = null;
        maxVariantStock = 0;
        document.getElementById('addToCartBtn').disabled = true;
        document.getElementById('variantNotice').classList.remove('hidden');
        document.getElementById('modalStockStatus').classList.add('hidden');
        document.getElementById('modalProductPrice').textContent = 'Rp 0';

        // Render Galeri Foto Mini (Thumbnails)
        const galleryContainer = document.getElementById('modalGallery');
        galleryContainer.innerHTML = '';
        const allImages = [image, ...images].filter(Boolean);
        const uniqueImages = [...new Set(allImages)];

        uniqueImages.forEach((imgUrl, idx) => {
            const thumb = document.createElement('button');
            thumb.type = 'button';
            thumb.className = `w-12 h-12 rounded-xl overflow-hidden border-2 ${idx === 0 ? 'border-amber-500' : 'border-transparent'} bg-slate-100 dark:bg-zinc-900 shrink-0 transition-all`;
            thumb.innerHTML = `<img src="${imgUrl}" class="w-full h-full object-cover">`;
            thumb.addEventListener('click', () => {
                document.getElementById('modalProductImage').src = imgUrl;
                galleryContainer.querySelectorAll('button').forEach(b => b.classList.remove('border-amber-500'));
                thumb.classList.add('border-amber-500');
            });
            galleryContainer.appendChild(thumb);
        });

        // Render Daftar Pilihan Varian Ukuran
        const variantsContainer = document.getElementById('variantsList');
        variantsContainer.innerHTML = '';

        variants.forEach(v => {
            const vBtn = document.createElement('button');
            vBtn.type = 'button';
            const hasStock = v.stock > 0;
            vBtn.className = `px-4 py-2 text-xs font-semibold font-mono border rounded-xl transition-all ${hasStock ? 'border-slate-200 dark:border-white/10 text-slate-800 dark:text-zinc-300 hover:border-amber-500' : 'border-slate-100 dark:border-zinc-800 text-slate-300 dark:text-zinc-600 line-through cursor-not-allowed'}`;
            vBtn.textContent = v.name || `${v.size}ml`;
            
            if (hasStock) {
                vBtn.addEventListener('click', () => {
                    selectedVariantId = v.id;
                    maxVariantStock = v.stock;
                    
                    variantsContainer.querySelectorAll('button').forEach(b => {
                        b.classList.remove('bg-amber-500', 'text-white', 'border-amber-500', 'dark:text-black');
                        b.classList.add('border-slate-200', 'dark:border-white/10', 'text-slate-800', 'dark:text-zinc-300');
                    });
                    vBtn.classList.remove('border-slate-200', 'dark:border-white/10', 'text-slate-800', 'dark:text-zinc-300');
                    vBtn.classList.add('bg-amber-500', 'text-white', 'border-amber-500', 'dark:text-black');

                    const formattedPrice = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(v.price);
                    document.getElementById('modalProductPrice').textContent = formattedPrice;

                    const stockStatus = document.getElementById('modalStockStatus');
                    stockStatus.textContent = `Stok: ${v.stock} tersedia`;
                    stockStatus.classList.remove('hidden');

                    document.getElementById('addToCartBtn').disabled = false;
                    document.getElementById('variantNotice').classList.add('hidden');
                    document.getElementById('modalQuantity').value = 1;
                });
            }
            variantsContainer.appendChild(vBtn);
        });

        // Render Dinamis Komentar Ulasan
        document.getElementById('modalReviewCount').textContent = reviews.length;
        const reviewsContainer = document.getElementById('modalReviewsList');
        reviewsContainer.innerHTML = '';

        if (reviews.length === 0) {
            reviewsContainer.innerHTML = `<p class="text-xs text-slate-400 dark:text-zinc-500 italic py-2">Belum ada ulasan untuk produk ini.</p>`;
        } else {
            reviews.forEach(r => {
                const rDiv = document.createElement('div');
                rDiv.className = 'p-3 bg-slate-50 dark:bg-zinc-800/40 rounded-xl border border-slate-100 dark:border-white/5';
                
                let starsHtml = '';
                for (let s = 1; s <= 5; s++) {
                    starsHtml += `<i class="fas fa-star text-[9px] ${s <= r.rating ? 'text-amber-400' : 'text-slate-200 dark:text-zinc-700'}"></i>`;
                }

                rDiv.innerHTML = `
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-semibold text-slate-800 dark:text-zinc-200">${r.user_name}</span>
                        <span class="text-[10px] font-mono text-slate-400">${r.date}</span>
                    </div>
                    <div class="flex items-center gap-0.5 mb-1.5">${starsHtml}</div>
                    <p class="text-xs text-slate-600 dark:text-zinc-400 leading-relaxed">${r.comment || 'Tidak ada komentar.'}</p>
                `;
                reviewsContainer.appendChild(rDiv);
            });
        }

        // Tampilkan Modal dengan Transisi Smooth
        const modal = document.getElementById('variantModal');
        modal.classList.remove('opacity-0', 'pointer-events-none');
        modal.firstElementChild.classList.remove('scale-95');
        modal.firstElementChild.classList.add('scale-100');
    }

    function closeVariantModal() {
        const modal = document.getElementById('variantModal');
        modal.classList.add('opacity-0', 'pointer-events-none');
        modal.firstElementChild.classList.remove('scale-100');
        modal.firstElementChild.classList.add('scale-95');
    }

    function incrementQuantity() {
        const qtyInput = document.getElementById('modalQuantity');
        let val = parseInt(qtyInput.value) || 1;
        if (selectedVariantId && val < maxVariantStock) {
            qtyInput.value = val + 1;
        } else if (!selectedVariantId) {
            document.getElementById('variantNotice').classList.remove('hidden');
        }
    }

    function decrementQuantity() {
        const qtyInput = document.getElementById('modalQuantity');
        let val = parseInt(qtyInput.value) || 1;
        if (val > 1) {
            qtyInput.value = val - 1;
        }
    }

    function submitVariantSelection() {
        if (!selectedVariantId) {
            document.getElementById('variantNotice').classList.remove('hidden');
            return;
        }
        
        const qty = document.getElementById('modalQuantity').value;
        const hiddenForm = document.getElementById('hiddenCartForm');
        
        document.getElementById('hiddenQuantity').value = qty;
        document.getElementById('hiddenVariantId').value = selectedVariantId;
        
        // Sesuaikan endpoint action berdasarkan konfigurasi routing aplikasi Anda
        hiddenForm.action = `/cart/add/${selectedVariantId}`; 
        hiddenForm.submit();
    }

    // ==========================================
    // 4. GENERAL UTILITIES (WISHLIST, ACTION CONTROLS)
    // ==========================================
    function toggleWishlist(btn, event, productId) {
        event.preventDefault();
        event.stopPropagation();
        
        // Asinkronus AJAX toggle wishlist menggunakan standard Fetch API
        fetch(`/wishlist/toggle/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            const icon = btn.querySelector('i');
            if (data.status === 'added') {
                icon.className = 'fas fa-heart text-[10px] sm:text-xs text-rose-500 transition-transform duration-300 scale-125';
                setTimeout(() => icon.classList.remove('scale-125'), 300);
            } else {
                icon.className = 'far fa-heart text-[10px] sm:text-xs text-slate-500 dark:text-zinc-400 transition-transform duration-300';
            }
        })
        .catch(err => console.error('Gagal memproses wishlist:', err));
    }

    function confirmDelete(id, name) {
        if (confirm(`Apakah Anda yakin ingin menghapus produk "${name}"?`)) {
            document.getElementById(`delete-form-${id}`).submit();
        }
    }

    function showLoginAlert(loginUrl) {
        alert('Silakan login terlebih dahulu untuk melakukan pembelian.');
        window.location.href = loginUrl;
    }

    function shareProduct(name, event) {
        event.preventDefault();
        event.stopPropagation();
        
        if (navigator.share) {
            navigator.share({
                title: name,
                text: `Lihat koleksi parfum premium: ${name}`,
                url: window.location.href
            }).catch(console.error);
        } else {
            navigator.clipboard.writeText(window.location.href)
                .then(() => alert('Tautan produk berhasil disalin ke clipboard!'))
                .catch(err => console.error('Gagal menyalin tautan:', err));
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/fuse.js@6.6.2/dist/fuse.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    const searchInput = document.querySelector('input[name="search"]');
    const productContainer = document.getElementById('product-container');
    const pagination = document.querySelector('.custom-pagination');
    if (!searchInput || !productContainer) return;
    // Build searchable list from rendered cards on page
    const buildListFromDOM = () => {
        const cards = Array.from(document.querySelectorAll('.product-card'));
        return cards.map(c => ({
            id: c.dataset.id,
            name: c.dataset.name || '',
            brand: c.dataset.brand || '',
            description: c.dataset.description || '',
            html: c.outerHTML
        }));
    };

    // Capture initial snapshot so we can fully restore when search cleared
    const originalList = buildListFromDOM();
    let list = originalList.slice();
    const fuse = new Fuse(list, { keys: ['name','brand','description'], threshold: 0.35 });
    let debounce = null;

    // On input, search and replace product container with matched cards
    searchInput.addEventListener('input', function(){
        const q = this.value.trim();
        clearTimeout(debounce);
        debounce = setTimeout(() => {
            if (q.length === 0) {
                // restore full original snapshot
                productContainer.innerHTML = originalList.map(i => i.html).join('');
                if (pagination) pagination.style.display = '';
                // reset list & fuse collection
                list = originalList.slice();
                fuse.setCollection(list);
                return;
            }

            // Use the original snapshot as the search base to ensure full catalog is searched
            fuse.setCollection(originalList);
            const results = fuse.search(q).map(r => r.item);

            if (results.length) {
                productContainer.innerHTML = results.map(r => r.html).join('');
            } else {
                productContainer.innerHTML = `<div class="col-span-full text-center py-20 reveal"><div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-darkcard border border-slate-200 dark:border-white/5 flex items-center justify-center mx-auto mb-4 text-slate-400"><i class="fas fa-box-open text-xl"></i></div><h3 class="font-serif text-lg">Tidak ada produk yang sesuai.</h3><p class="text-xs text-slate-400 mt-1">Coba gunakan kata kunci lain atau kurangi filter Anda.</p></div>`;
            }
            if (pagination) pagination.style.display = 'none';
        }, 200);
    });
});
</script>

<script>
    // ==========================================
    // 5. PROMO COUNTDOWN GLOBAL CONTROL
    // ==========================================
    document.addEventListener("DOMContentLoaded", function () {
        const el = document.getElementById('promo-countdown');
        const endInput = document.getElementById('promo-ends-at');
        
        // Validasi jika elemen tidak ditemukan di halaman
        if (!el || !endInput || !endInput.value) return;

        // Mengonversi string tanggal ISO dari backend menjadi objek Date browser
        const endAt = new Date(endInput.value);
        if (isNaN(endAt.getTime())) {
            el.textContent = "Selamanya";
            return;
        }

        function updateCountdown() {
            const now = new Date();
            let diff = endAt - now;

            // Jika waktu promo sudah terlewati
            if (diff <= 0) {
                el.textContent = 'Selesai';
                clearInterval(tid);
                return;
            }

            // Perhitungan Hari, Jam, Menit, dan Detik
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            diff -= days * (1000 * 60 * 60 * 24);
            
            const hours = Math.floor(diff / (1000 * 60 * 60));
            diff -= hours * (1000 * 60 * 60);
            
            const minutes = Math.floor(diff / (1000 * 60));
            diff -= minutes * (1000 * 60);
            
            const seconds = Math.floor(diff / 1000);

            // Format string keluaran
            const parts = [];
            if (days > 0) {
                parts.push(days + 'd');
            }
            
            // Mengamankan format 2 digit angka (01:05:09)
            const formatTime = String(hours).padStart(2, '0') + ':' + 
                               String(minutes).padStart(2, '0') + ':' + 
                               String(seconds).padStart(2, '0');
            parts.push(formatTime);

            el.textContent = parts.join(' ');
        }

        // Jalankan fungsi sekali di awal agar tidak ada delay kosong 1 detik
        updateCountdown();
        const tid = setInterval(updateCountdown, 1000);
    });

    // Upcoming promo countdown (reload page when it starts)
    document.addEventListener("DOMContentLoaded", function () {
        const elStart = document.getElementById('promo-starts-countdown');
        const startInput = document.getElementById('promo-starts-at');
        if (!elStart || !startInput || !startInput.value) return;

        const startAt = new Date(startInput.value);
        if (isNaN(startAt.getTime())) return;

        function updateStartCountdown() {
            const now = new Date();
            let diff = startAt - now;
            if (diff <= 0) {
                elStart.textContent = 'Dimulai';
                clearInterval(startTid);
                // reload to reflect active promotion
                try { setTimeout(() => window.location.reload(), 500); } catch(e) {}
                return;
            }

            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            diff -= days * (1000 * 60 * 60 * 24);
            const hours = Math.floor(diff / (1000 * 60 * 60));
            diff -= hours * (1000 * 60 * 60);
            const minutes = Math.floor(diff / (1000 * 60));
            diff -= minutes * (1000 * 60);
            const seconds = Math.floor(diff / 1000);

            const parts = [];
            if (days > 0) parts.push(days + 'd');
            parts.push(String(hours).padStart(2,'0') + ':' + String(minutes).padStart(2,'0') + ':' + String(seconds).padStart(2,'0'));
            elStart.textContent = parts.join(' ');
        }

        updateStartCountdown();
        const startTid = setInterval(updateStartCountdown, 1000);
    });
</script>
@endsection

@push('scripts')
<script>
    // Promo countdown
    (function(){
        const el = document.getElementById('promo-countdown');
        const endInput = document.getElementById('promo-ends-at');
        if (!el || !endInput) return;

        let endAt = new Date(endInput.value);
        if (isNaN(endAt.getTime())) return;

        function updateCountdown(){
            const now = new Date();
            let diff = endAt - now;
            if (diff <= 0) {
                el.textContent = 'Selesai';
                clearInterval(tid);
                return;
            }
            const days = Math.floor(diff / (1000*60*60*24));
            diff -= days * (1000*60*60*24);
            const hours = Math.floor(diff / (1000*60*60));
            diff -= hours * (1000*60*60);
            const minutes = Math.floor(diff / (1000*60));
            diff -= minutes * (1000*60);
            const seconds = Math.floor(diff/1000);

            const parts = [];
            if (days > 0) parts.push(days + 'd');
            parts.push(String(hours).padStart(2,'0')+':'+String(minutes).padStart(2,'0')+':'+String(seconds).padStart(2,'0'));
            el.textContent = parts.join(' ');
        }

        updateCountdown();
        const tid = setInterval(updateCountdown, 1000);
    })();
</script>
@endpush