@extends('base.base')

@section('content')
    <div id="filterOverlay"
        class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 lg:hidden"
        onclick="closeMobileFilter()"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32">
        <nav class="mb-8 reveal">
            <ol
                class="flex items-center space-x-2 text-xs font-mono uppercase tracking-wider text-slate-400 dark:text-zinc-500">
                <li><a href="{{ route('home') }}" class="hover:text-amber-500 transition-colors">Home</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-amber-500 font-semibold">Shop</li>
            </ol>
        </nav>

        <div
            class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-12 pb-8 border-b border-slate-200 dark:border-white/5 reveal">
            <div>
                <span
                    class="text-xs font-mono text-amber-600 dark:text-amber-400 uppercase tracking-widest font-semibold">Discovery</span>
                <h1 class="text-3xl md:text-5xl font-serif mt-1 text-slate-950 dark:text-white">Semua Parfum</h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-zinc-400 mt-2">
                    Menampilkan {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} dari
                    {{ $products->total() }} produk terpilih
                </p>
            </div>

            <div
                class="flex flex-col sm:flex-row items-center justify-between lg:justify-end gap-3 sm:gap-4 w-full lg:w-auto">
                <div class="relative w-full sm:w-56 md:w-64 order-1 sm:order-none">
                    <input type="text" name="search" form="filterForm" value="{{ request('search') }}"
                        placeholder="Cari nama parfum..."
                        class="w-full appearance-none bg-white dark:bg-darkcard border border-slate-200 dark:border-white/10 rounded-full pl-10 pr-4 py-2.5 text-xs font-semibold focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all text-slate-800 dark:text-zinc-300 shadow-sm placeholder-slate-400 dark:placeholder-zinc-500">
                    <button type="submit" form="filterForm"
                        class="absolute inset-y-0 left-0 pl-3.5 pr-2 flex items-center text-slate-400 hover:text-amber-500 transition-colors focus:outline-none">
                        <i class="fas fa-search text-[11px]"></i>
                    </button>
                </div>

                <div class="flex items-center justify-between w-full sm:w-auto gap-3 sm:gap-4 order-2 sm:order-none">
                    <button type="button" onclick="openMobileFilter()"
                        class="lg:hidden flex items-center gap-2 px-5 py-2.5 rounded-full bg-white dark:bg-darkcard border border-slate-200 dark:border-white/10 text-xs font-mono font-bold uppercase tracking-wider text-slate-800 dark:text-zinc-300 shadow-md focus:outline-none">
                        <i class="fas fa-sliders text-amber-500"></i> Filter
                    </button>

                    <div class="flex items-center gap-3">
                        <label for="sortSelect"
                            class="text-xs font-mono uppercase text-slate-400 hidden sm:inline-block">Urutkan:</label>
                        <div class="relative">
                            <select name="sort" form="filterForm" id="sortSelect"
                                onchange="document.getElementById('filterForm').submit()"
                                class="appearance-none bg-white dark:bg-darkcard border border-slate-200 dark:border-white/10 rounded-full px-6 py-2.5 pr-10 text-xs font-semibold focus:outline-none focus:border-amber-500 transition-all text-slate-800 dark:text-zinc-300 shadow-sm cursor-pointer">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga:
                                    Rendah ke Tinggi</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga:
                                    Tinggi ke Rendah</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-slate-400">
                                <i class="fas fa-chevron-down text-[10px]"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <aside id="filterSidebar"
                class="fixed inset-y-0 left-0 z-50 w-80 max-w-[85vw] bg-white dark:bg-darkbg lg:bg-transparent lg:dark:bg-transparent p-6 lg:p-0 border-r border-slate-200 dark:border-white/10 lg:border-none shadow-2xl lg:shadow-none transform -translate-x-full lg:translate-x-0 overflow-y-auto lg:overflow-visible lg:static lg:col-span-3 lg:w-full reveal">
                <div class="flex lg:hidden justify-end mb-6">
                    <button type="button" onclick="closeMobileFilter()"
                        class="w-8 h-8 rounded-full border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>

                <form action="{{ route('shop') }}" method="GET" id="filterForm">
                    <div class="lg:sticky lg:top-28 space-y-8">
                        <div class="flex items-center justify-between">
                            <h5 class="font-serif text-lg font-semibold tracking-wide flex items-center gap-2">
                                <i class="fas fa-sliders text-sm text-amber-500"></i> Filter
                            </h5>
                            <a href="{{ route('shop') }}"
                                class="text-xs font-mono uppercase text-slate-400 hover:text-amber-500 transition-colors">Reset</a>
                        </div>

                        <div class="pb-6 border-b border-slate-200 dark:border-white/5">
                            <h6 class="text-xs font-mono uppercase tracking-wider text-slate-400 mb-4 font-bold">Kategori
                            </h6>
                            <div class="space-y-3">
                                @foreach (['Men', 'Women', 'Unisex'] as $gender)
                                    <label
                                        class="flex items-center group cursor-pointer text-sm text-slate-600 dark:text-zinc-300 hover:text-amber-500 transition-colors">
                                        <input type="checkbox" name="gender[]" value="{{ $gender }}"
                                            id="cat{{ $gender }}"
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
                                    <label
                                        class="flex items-center group cursor-pointer text-sm text-slate-600 dark:text-zinc-300 hover:text-amber-500 transition-colors">
                                        <input type="checkbox" name="brand[]" value="{{ $brand->id }}"
                                            id="brand{{ $brand->id }}"
                                            {{ in_array($brand->id, (array) request('brand', [])) ? 'checked' : '' }}
                                            class="rounded border-slate-300 dark:border-zinc-700 text-amber-500 focus:ring-amber-500 bg-transparent mr-3 w-4 h-4 transition-colors">
                                        <span class="font-medium">{{ $brand->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <h6 class="text-xs font-mono uppercase tracking-wider text-slate-400 mb-4 font-bold">Harga
                                Maksimal</h6>
                            <div class="relative pt-4 px-2 group">
                                <input type="range" name="max_price" id="priceRange" min="0" max="5000000"
                                    step="100000" value="{{ request('max_price', 5000000) }}"
                                    class="w-full h-1 bg-slate-200 dark:bg-zinc-800 rounded-lg appearance-none cursor-pointer accent-amber-500">

                                <div id="rangeTooltip"
                                    class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-slate-950 dark:bg-amber-500 text-white dark:text-black text-[10px] font-mono font-bold px-2 py-0.5 rounded shadow-lg pointer-events-none hidden group-hover:block group-active:block transition-all duration-150">
                                    Rp 5.000.000
                                </div>
                            </div>

                            <div class="flex justify-between text-xs text-slate-400 font-mono mt-3">
                                <span>Rp 0</span>
                                <span id="priceLabel">Rp 5.000.000</span>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full bg-slate-900 dark:bg-amber-400 text-white dark:text-black font-semibold tracking-wide py-3.5 rounded-xl hover:bg-amber-500 dark:hover:bg-amber-300 active:scale-95 transition-all duration-300 text-sm shadow-lg shadow-amber-500/5">
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
                    <div
                        class="flex items-start sm:items-center justify-between bg-white dark:bg-darkcard border border-slate-200 dark:border-white/5 rounded-2xl mb-8 p-4 shadow-sm reveal">
                        <div class="flex items-center flex-wrap gap-2 sm:gap-3">
                            <i class="fas fa-filter text-amber-500 text-xs mr-1 mt-1 sm:mt-0"></i>
                            <span class="text-xs text-slate-500 dark:text-zinc-400">Filter Aktif:</span>

                            @foreach ($activeFilters as $filter)
                                <span
                                    class="bg-slate-900 dark:bg-amber-400 text-white dark:text-black text-[11px] font-semibold px-3 py-1 rounded-full flex items-center gap-1 shadow-sm">
                                    <span
                                        class="opacity-70 font-normal hidden sm:inline-block">{{ $filter['type'] }}:</span>
                                    {{ $filter['label'] }}
                                </span>
                            @endforeach
                        </div>
                        <a href="{{ route('shop') }}"
                            class="flex-shrink-0 w-8 h-8 rounded-full border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:border-rose-500/30 transition-colors ml-4"
                            title="Hapus Semua Filter">
                            <i class="fas fa-times text-xs"></i>
                        </a>
                    </div>
                @endif

                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6" id="product-container">
                    @forelse ($products as $product)
                        <div class="perspective-1000 reveal" id="product-card-{{ $product->id }}">
                            <div
                                class="tilt-card bg-white dark:bg-darkcard rounded-2xl sm:rounded-3xl p-3 sm:p-4 border border-slate-200 dark:border-white/5 shadow-md flex flex-col justify-between h-auto min-h-[300px] sm:min-h-[360px] transition-all duration-300 group relative">

                                <div
                                    class="w-full h-32 sm:h-44 overflow-hidden rounded-xl sm:rounded-2xl bg-slate-100 dark:bg-zinc-900 relative">
                                    <img src="{{ $product->image_url ? (strpos($product->image_url, 'http') === 0 ? $product->image_url : asset('product_image/' . $product->image_url)) : 'https://placehold.co/400x500?text=Scentify' }}"
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                </div>

                                <div class="mt-3 flex-grow flex flex-col justify-start">
                                    <div>
                                        <small
                                            class="text-[9px] sm:text-[10px] font-mono text-amber-600 dark:text-amber-400 uppercase tracking-widest font-semibold block">
                                            {{ $product->brand->name ?? 'Unknown Brand' }}
                                        </small>
                                        <h5 class="text-sm sm:text-base font-serif font-bold text-slate-900 dark:text-white mt-0.5 group-hover:text-amber-500 transition-colors line-clamp-1"
                                            title="{{ $product->name }}">
                                            {{ $product->name }}
                                        </h5>

                                        @php
                                            $avgRating = $product->reviews->avg('rating') ?? 0;
                                            $reviewCount = $product->reviews->count();
                                        @endphp
                                        @if ($reviewCount > 0)
                                            <div class="flex items-center gap-0.5 mt-1">
                                                @for ($s = 1; $s <= 5; $s++)
                                                    <i
                                                        class="fas fa-star text-[9px] {{ $s <= round($avgRating) ? 'text-amber-400' : 'text-slate-200 dark:text-zinc-700' }}"></i>
                                                @endfor
                                                <span
                                                    class="text-[9px] text-slate-400 font-mono ml-1">{{ number_format($avgRating, 1) }}
                                                    ({{ $reviewCount }})
                                                </span>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-0.5 mt-1">
                                                @for ($s = 1; $s <= 5; $s++)
                                                    <i
                                                        class="far fa-star text-[9px] text-slate-200 dark:text-zinc-700"></i>
                                                @endfor
                                                <span class="text-[9px] text-slate-400 font-mono ml-1">Belum ada
                                                    ulasan</span>
                                            </div>
                                        @endif
                                    </div>

                                    <p class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white mt-1">
                                        Rp {{ number_format($product->variants->first()->price ?? 0, 0, ',', '.') }}
                                    </p>
                                </div>

                                <div class="mt-3 flex items-center gap-2 w-full">
                                    @if (auth()->check() && auth()->user()->role === 'admin')
                                        <a href="{{ route('products.edit', $product->id) }}"
                                            class="flex-grow py-1.5 sm:py-2 text-[10px] sm:text-xs font-semibold tracking-wide bg-blue-500 hover:bg-blue-600 text-white rounded-full transition-colors duration-300 shadow-md focus:outline-none flex items-center justify-center gap-1.5"
                                            title="Edit Produk">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>

                                        <form id="delete-form-{{ $product->id }}"
                                            action="{{ route('products.destroy', $product->id) }}" method="POST"
                                            class="flex-grow flex">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                onclick="confirmDelete('{{ $product->id }}', '{{ $product->name }}')"
                                                class="w-full py-1.5 sm:py-2 text-[10px] sm:text-xs font-semibold tracking-wide bg-rose-500 hover:bg-rose-600 text-white rounded-full transition-colors duration-300 shadow-md focus:outline-none flex items-center justify-center gap-1.5"
                                                title="Hapus Produk">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" onclick="toggleWishlist(this, event, {{ $product->id }})"
                                            class="w-7 h-7 sm:w-8 sm:h-8 shrink-0 flex items-center justify-center rounded-full bg-slate-100 dark:bg-zinc-800 text-slate-500 dark:text-zinc-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors shadow-sm focus:outline-none"
                                            title="Tambah/Hapus Wishlist">
                                            <i
                                                class="{{ in_array($product->id, $wishlistedProductIds ?? []) ? 'fas text-rose-500' : 'far' }} fa-heart text-[10px] sm:text-xs transition-transform duration-300"></i>
                                        </button>

                                        @if ($product->variants->isNotEmpty())
                                            @auth
                                                <button type="button"
                                                    class="variant-selector-btn flex-grow py-1.5 sm:py-2 text-[10px] sm:text-xs font-semibold tracking-wide bg-slate-900 dark:bg-amber-400 text-white dark:text-black rounded-full hover:bg-amber-500 dark:hover:bg-amber-300 transition-colors duration-300 shadow-md focus:outline-none flex items-center justify-center gap-1.5"
                                                    data-product-id="{{ $product->id }}"
                                                    data-product-name="{{ $product->name }}"
                                                    data-product-brand="{{ $product->brand->name ?? 'Unknown Brand' }}"
                                                    data-product-image="{{ $product->image_url ? (strpos($product->image_url, 'http') === 0 ? $product->image_url : asset('product_image/' . $product->image_url)) : 'https://placehold.co/400x500?text=Scentify' }}"
                                                    data-product-description="{{ $product->description ?? '' }}"
                                                    data-product-images="{{ json_encode($product->images->map(fn($img) => $img->url)) }}"
                                                    data-variants="{{ json_encode($product->variants) }}"
                                                    data-reviews="{{ json_encode(
                                                        $product->reviews->map(
                                                            fn($r) => [
                                                                'rating' => $r->rating,
                                                                'comment' => $r->comment,
                                                                'date' => $r->created_at->format('d M Y'),
                                                                'user_name' => $r->user->name ?? 'Pelanggan Scentify',
                                                            ],
                                                        ),
                                                    ) }}">
                                                    <i class="fas fa-cart-plus"></i> Beli
                                                </button>
                                            @else
                                                <a href="{{ route('login') }}"
                                                    class="flex-grow py-1.5 sm:py-2 text-center text-[10px] sm:text-xs font-semibold tracking-wide bg-slate-900 dark:bg-amber-400 text-white dark:text-black rounded-full hover:bg-amber-500 dark:hover:bg-amber-300 transition-colors duration-300 shadow-md flex items-center justify-center gap-1.5"
                                                    onclick="event.preventDefault(); showLoginAlert(this.href)">
                                                    <i class="fas fa-cart-plus"></i> Beli
                                                </a>
                                            @endauth
                                        @else
                                            <button
                                                class="flex-grow py-1.5 sm:py-2 text-[10px] sm:text-xs font-semibold tracking-wide bg-slate-300 dark:bg-zinc-800 text-slate-500 dark:text-zinc-600 rounded-full cursor-not-allowed flex items-center justify-center gap-1.5"
                                                disabled>
                                                <i class="fas fa-times-circle"></i> Habis
                                            </button>
                                        @endif

                                        <button type="button"
                                            onclick="shareProduct('{{ addslashes($product->name) }}', event)"
                                            class="w-7 h-7 sm:w-8 sm:h-8 shrink-0 flex items-center justify-center rounded-full bg-slate-100 dark:bg-zinc-800 text-slate-500 dark:text-zinc-400 hover:text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-500/10 transition-colors shadow-sm focus:outline-none"
                                            title="Bagikan Produk">
                                            <i class="fas fa-share-nodes text-[10px] sm:text-xs"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-20 reveal">
                            <div
                                class="w-16 h-16 rounded-full bg-slate-100 dark:bg-darkcard border border-slate-200 dark:border-white/5 flex items-center justify-center mx-auto mb-4 text-slate-400">
                                <i class="fas fa-box-open text-xl"></i>
                            </div>
                            <h3 class="font-serif text-lg">Tidak ada produk yang sesuai.</h3>
                            <p class="text-xs text-slate-400 mt-1">Coba gunakan kata kunci lain atau kurangi filter Anda.
                            </p>
                        </div>
                    @endforelse
                </div>

                <div
                    class="mt-16 pt-8 border-t border-slate-200 dark:border-white/5 flex justify-center custom-pagination reveal">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            </main>
        </div>
    </div>

    <div id="variantModal"
        class="fixed inset-0 z-[1000] flex items-center justify-center p-4 bg-black/60 backdrop-blur-md opacity-0 pointer-events-none transition-opacity duration-300">
        <div
            class="bg-white dark:bg-darkcard border border-slate-200 dark:border-white/5 rounded-3xl w-full max-w-3xl overflow-hidden shadow-2xl transform scale-95 transition-transform duration-300 max-h-[90vh] overflow-y-auto">
            <div class="p-6 md:p-8">
                <div class="flex justify-end mb-2">
                    <button onclick="closeVariantModal()"
                        class="w-8 h-8 rounded-full border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors focus:outline-none">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
                    <div class="md:col-span-5">
                        {{-- Gambar Utama --}}
                        <div class="w-full h-64 md:h-72 overflow-hidden rounded-2xl bg-slate-100 dark:bg-zinc-900">
                            <img id="modalProductImage" src="" alt="Product"
                                class="w-full h-full object-cover transition-all duration-300">
                        </div>
                        {{-- Thumbnail Gallery --}}
                        <div id="modalGallery" class="flex gap-2 mt-3 overflow-x-auto pb-1"></div>
                    </div>

                    <div class="md:col-span-7 flex flex-col justify-between h-full">
                        <div>
                            <small id="modalProductBrand"
                                class="text-[10px] font-mono text-amber-600 dark:text-amber-400 uppercase tracking-widest font-semibold block"></small>
                            <h4 id="modalProductName"
                                class="text-2xl font-serif font-bold text-slate-950 dark:text-white mt-1"></h4>
                            <div id="modalProductPrice" class="text-xl font-bold text-amber-600 dark:text-amber-400 mt-3">
                                Rp 0</div>
                            <p id="modalProductDescription"
                                class="text-xs text-slate-500 dark:text-zinc-400 leading-relaxed mt-4 line-clamp-3"></p>

                            <h6 class="text-xs font-mono uppercase text-slate-400 tracking-wider mt-6 mb-3 font-semibold">
                                Ukuran Tersedia:</h6>
                            <div id="variantsList" class="flex flex-wrap gap-2"></div>

                            <h6 class="text-xs font-mono uppercase text-slate-400 tracking-wider mt-5 mb-3 font-semibold">
                                Kuantitas:</h6>
                            <div class="flex items-center gap-4">
                                <div
                                    class="flex items-center bg-slate-100 dark:bg-zinc-800/50 rounded-xl p-1 border border-slate-200 dark:border-white/5">
                                    <button type="button" onclick="decrementQuantity()"
                                        class="w-8 h-8 flex items-center justify-center text-slate-500 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-white dark:hover:bg-zinc-700 rounded-lg transition-all focus:outline-none">
                                        <i class="fas fa-minus text-[10px]"></i>
                                    </button>
                                    <input type="number" id="modalQuantity" value="1" min="1" readonly
                                        class="w-10 text-center bg-transparent text-sm font-bold text-slate-900 dark:text-white focus:outline-none appearance-none">
                                    <button type="button" onclick="incrementQuantity()"
                                        class="w-8 h-8 flex items-center justify-center text-slate-500 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-white dark:hover:bg-zinc-700 rounded-lg transition-all focus:outline-none">
                                        <i class="fas fa-plus text-[10px]"></i>
                                    </button>
                                </div>
                                <span id="modalStockStatus" class="text-xs text-slate-500 font-medium hidden"></span>
                            </div>

                            <div id="variantNotice"
                                class="mt-4 text-xs text-rose-500 flex items-center gap-1.5 font-medium">
                                <i class="fas fa-exclamation-circle"></i> Pilih salah satu varian terlebih dahulu.
                            </div>
                        </div>

                        <div class="border-t border-slate-200 dark:border-white/5 mt-8 pt-6">
                            <h5
                                class="text-sm font-serif font-bold text-slate-950 dark:text-white mb-4 flex items-center gap-2">
                                <i class="fas fa-star text-amber-500 text-xs"></i>
                                Ulasan Pelanggan (<span id="modalReviewCount">0</span>)
                            </h5>
                            <div id="modalReviewsList" class="space-y-3 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                            </div>
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
        .custom-pagination .page-link {
            color: inherit;
            border: none;
            background: transparent;
        }

        .custom-pagination .page-item.active .page-link {
            background-color: #f59e0b !important;
            color: #000 !important;
            border-radius: 9999px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .custom-pagination .page-link:hover {
            background-color: rgba(245, 158, 11, 0.1) !important;
            color: #f59e0b !important;
            border-radius: 9999px;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.3);
            border-radius: 10px;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>

    <script>
        const isDarkMode = () => document.documentElement.classList.contains('dark');

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
                const formatted = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0
                }).format(val);

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

            document.getElementById('modalProductName').textContent = name;
            document.getElementById('modalProductBrand').textContent = brand;
            document.getElementById('modalProductDescription').textContent = desc || 'Tidak ada deskripsi.';
            document.getElementById('modalProductImage').src = image;
            document.getElementById('modalQuantity').value = 1;

            selectedVariantId = null;
            maxVariantStock = 0;
            document.getElementById('addToCartBtn').disabled = true;
            document.getElementById('variantNotice').classList.remove('hidden');
            document.getElementById('modalStockStatus').classList.add('hidden');
            document.getElementById('modalProductPrice').textContent = 'Rp 0';

            const galleryContainer = document.getElementById('modalGallery');
            galleryContainer.innerHTML = '';
            const allImages = [image, ...images].filter(Boolean);
            const uniqueImages = [...new Set(allImages)];

            uniqueImages.forEach((imgUrl, idx) => {
                const thumb = document.createElement('button');
                thumb.type = 'button';
                thumb.className =
                    `w-12 h-12 rounded-xl overflow-hidden border-2 ${idx === 0 ? 'border-amber-500' : 'border-transparent'} bg-slate-100 dark:bg-zinc-900 shrink-0 transition-all`;
                thumb.innerHTML = `<img src="${imgUrl}" class="w-full h-full object-cover">`;
                thumb.addEventListener('click', () => {
                    document.getElementById('modalProductImage').src = imgUrl;
                    galleryContainer.querySelectorAll('button').forEach(b => b.classList.remove(
                        'border-amber-500'));
                    thumb.classList.add('border-amber-500');
                });
                galleryContainer.appendChild(thumb);
            });

            const variantsContainer = document.getElementById('variantsList');
            variantsContainer.innerHTML = '';

            variants.forEach(v => {
                const vBtn = document.createElement('button');
                vBtn.type = 'button';
                const hasStock = v.stock > 0;
                vBtn.className =
                    `px-4 py-2 text-xs font-semibold font-mono border rounded-xl transition-all ${hasStock ? 'border-slate-200 dark:border-white/10 text-slate-800 dark:text-zinc-300 hover:border-amber-500' : 'border-slate-100 dark:border-zinc-800 text-slate-300 dark:text-zinc-600 line-through cursor-not-allowed'}`;
                vBtn.textContent = v.name || `${v.size}ml`;

                if (hasStock) {
                    vBtn.addEventListener('click', () => {
                        selectedVariantId = v.id;
                        maxVariantStock = v.stock;

                        variantsContainer.querySelectorAll('button').forEach(b => {
                            b.classList.remove('bg-amber-500', 'text-white', 'border-amber-500',
                                'dark:text-black');
                            b.classList.add('border-slate-200', 'dark:border-white/10',
                                'text-slate-800', 'dark:text-zinc-300');
                        });
                        vBtn.classList.remove('border-slate-200', 'dark:border-white/10', 'text-slate-800',
                            'dark:text-zinc-300');
                        vBtn.classList.add('bg-amber-500', 'text-white', 'border-amber-500',
                            'dark:text-black');

                        const formattedPrice = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            maximumFractionDigits: 0
                        }).format(v.price);
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

            document.getElementById('modalReviewCount').textContent = reviews.length;
            const reviewsContainer = document.getElementById('modalReviewsList');
            reviewsContainer.innerHTML = '';

            if (reviews.length === 0) {
                reviewsContainer.innerHTML =
                    `<p class="text-xs text-slate-400 dark:text-zinc-500 italic py-2">Belum ada ulasan untuk produk ini.</p>`;
            } else {
                reviews.forEach(r => {
                    const rDiv = document.createElement('div');
                    rDiv.className =
                        'p-3 bg-slate-50 dark:bg-zinc-800/40 rounded-xl border border-slate-100 dark:border-white/5';

                    let starsHtml = '';
                    for (let s = 1; s <= 5; s++) {
                        starsHtml +=
                            `<i class="fas fa-star text-[9px] ${s <= r.rating ? 'text-amber-400' : 'text-slate-200 dark:text-zinc-700'}"></i>`;
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

            hiddenForm.action = `/cart/add/${selectedVariantId}`;
            hiddenForm.submit();
        }

        // ==========================================
        // 4. GENERAL UTILITIES (WISHLIST, ACTION CONTROLS)
        // ==========================================
        function toggleWishlist(btn, event, productId) {
            event.preventDefault();
            event.stopPropagation();

            fetch(`/wishlist/toggle/${productId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                            '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    const icon = btn.querySelector('i');
                    if (data.status === 'added') {
                        icon.className =
                            'fas fa-heart text-[10px] sm:text-xs text-rose-500 transition-transform duration-300 scale-125';
                        setTimeout(() => icon.classList.remove('scale-125'), 300);
                    } else {
                        icon.className =
                            'far fa-heart text-[10px] sm:text-xs text-slate-500 dark:text-zinc-400 transition-transform duration-300';
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

    <script type="module">
        // Deteksi apakah yang sedang membuka halaman ini adalah Admin
        const isAdmin = {{ auth()->check() && auth()->user()->role === 'admin' ? 'true' : 'false' }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
            '{{ csrf_token() }}';

        // Deklarasikan channel satu kali saja agar lebih rapi
        const channel = window.Echo.channel('scentify-live');

        // ==========================================
        // EVENT: PRODUK DITAMBAHKAN
        // ==========================================
        channel.listen('.product.added', (e) => {
            console.log("🔥 SINYAL TAMBAH DITERIMA:", e);

            Swal.fire({
                icon: 'info',
                title: '✨ Rilis Baru!',
                html: `Koleksi terbaru <b>${e.product.name}</b> baru saja ditambahkan ke katalog kami!`,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });

            const container = document.getElementById('product-container');
            if (!container) return;

            let actionButtons = '';
            if (isAdmin) {
                actionButtons = `
                    <a href="/products/${e.product.id}/edit"
                        class="flex-grow py-1.5 sm:py-2 text-[10px] sm:text-xs font-semibold tracking-wide bg-blue-500 hover:bg-blue-600 text-white rounded-full transition-colors duration-300 shadow-md focus:outline-none flex items-center justify-center gap-1.5"
                        title="Edit Produk"><i class="fas fa-edit"></i> Edit</a>
                    <form id="delete-form-${e.product.id}" action="/products/${e.product.id}" method="POST" class="flex-grow flex">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="button" onclick="confirmDelete('${e.product.id}', '${e.product.name}')"
                            class="w-full py-1.5 sm:py-2 text-[10px] sm:text-xs font-semibold tracking-wide bg-rose-500 hover:bg-rose-600 text-white rounded-full transition-colors duration-300 shadow-md focus:outline-none flex items-center justify-center gap-1.5"
                            title="Hapus Produk"><i class="fas fa-trash"></i> Hapus</button>
                    </form>
                `;
            } else {
                actionButtons = `
                    <button type="button" onclick="window.location.reload()"
                        class="w-full py-1.5 sm:py-2 text-[10px] sm:text-xs font-semibold tracking-wide bg-amber-500 hover:bg-amber-600 text-white rounded-full transition-colors duration-300 shadow-md flex items-center justify-center gap-1.5">
                        <i class="fas fa-sync"></i> Refresh untuk Beli
                    </button>
                `;
            }

            const newProductDiv = document.createElement('div');
            newProductDiv.className = 'perspective-1000 reveal opacity-0';
            // BERI ID PADA KARTU BARU AGAR BISA DIHAPUS/EDIT NANTINYA
            newProductDiv.id = 'product-card-' + e.product.id;

            const imgUrl = e.product.image_url ?
                (e.product.image_url.startsWith('http') ? e.product.image_url :
                    `/product_image/${e.product.image_url}`) :
                'https://placehold.co/400x500?text=Baru';

            newProductDiv.innerHTML = `
                <div class="tilt-card bg-white dark:bg-darkcard rounded-2xl sm:rounded-3xl p-3 sm:p-4 border border-slate-200 dark:border-white/5 shadow-md flex flex-col justify-between h-auto min-h-[300px] sm:min-h-[360px] transition-all duration-300 group relative ring-2 ring-amber-400">
                    <div class="absolute -top-3 -right-3 z-10 bg-amber-500 text-black text-[9px] font-bold uppercase tracking-widest px-3 py-1.5 rounded-full shadow-lg">Baru!</div>
                    <div class="w-full h-32 sm:h-44 overflow-hidden rounded-xl sm:rounded-2xl bg-slate-100 dark:bg-zinc-900 relative">
                        <img src="${imgUrl}" alt="${e.product.name}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    </div>
                    <div class="mt-3 flex-grow flex flex-col justify-start">
                        <div>
                            <small class="text-[9px] sm:text-[10px] font-mono text-amber-600 dark:text-amber-400 uppercase tracking-widest font-semibold block">${e.product.brand?.name || 'Scentify'}</small>
                            <h5 class="text-sm sm:text-base font-serif font-bold text-slate-900 dark:text-white mt-0.5 group-hover:text-amber-500 transition-colors line-clamp-1" title="${e.product.name}">${e.product.name}</h5>
                            <div class="flex items-center gap-0.5 mt-1">
                                <i class="far fa-star text-[9px] text-slate-200 dark:text-zinc-700"></i>
                                <span class="text-[9px] text-slate-400 font-mono ml-1">Belum ada ulasan</span>
                            </div>
                        </div>
                        <p class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white mt-1">Cek Detail Baru</p>
                    </div>
                    <div class="mt-3 flex items-center gap-2 w-full">${actionButtons}</div>
                </div>
            `;

            container.insertAdjacentElement('afterbegin', newProductDiv);
            if (window.gsap) gsap.to(newProductDiv, {
                opacity: 1,
                duration: 1
            });
        });

        // ==========================================
        // EVENT: PRODUK DIHAPUS
        // ==========================================
        channel.listen('.product.deleted', (e) => {
            console.log("🔥 SINYAL HAPUS DITERIMA:", e);
            const cardToRemove = document.getElementById('product-card-' + e.productId);

            if (cardToRemove) {
                if (window.gsap) {
                    gsap.to(cardToRemove, {
                        opacity: 0,
                        scale: 0.8,
                        duration: 0.5,
                        onComplete: () => cardToRemove.remove()
                    });
                } else {
                    cardToRemove.remove();
                }

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: 'Produk Ditarik',
                    text: 'Satu produk baru saja dihapus dari katalog.',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        });

        // ==========================================
        // EVENT: PRODUK DI-EDIT
        // ==========================================
        channel.listen('.product.updated', (e) => {
            console.log("🔥 SINYAL UPDATE DITERIMA:", e);
            const cardToUpdate = document.getElementById('product-card-' + e.product.id);

            if (cardToUpdate) {
                // Animasi kedip (highlight)
                if (window.gsap) {
                    gsap.fromTo(cardToUpdate, {
                        opacity: 0.5,
                        scale: 0.95
                    }, {
                        opacity: 1,
                        scale: 1,
                        duration: 0.5,
                        ease: "bounce.out"
                    });
                }

                // Update Nama
                const titleElement = cardToUpdate.querySelector('h5');
                if (titleElement) {
                    titleElement.textContent = e.product.name;
                    titleElement.title = e.product.name;
                }

                // Update Gambar
                const imgElement = cardToUpdate.querySelector('img');
                if (imgElement && e.product.image_url) {
                    const newImgUrl = e.product.image_url.startsWith('http') ?
                        e.product.image_url :
                        `/product_image/${e.product.image_url}`;
                    imgElement.src = newImgUrl;
                }

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Katalog Diperbarui',
                    text: `${e.product.name} baru saja diperbarui.`,
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        });
    </script>
@endsection
