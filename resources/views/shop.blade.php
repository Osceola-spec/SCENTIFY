@extends('base.base')

@section('content')
<!-- Backdrop Overlay untuk Mobile Filter (Menghindari Redundansi Kode Form) -->
<div id="filterOverlay" class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 lg:hidden" onclick="closeMobileFilter()"></div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32">
    <!-- Breadcrumb -->
    <nav class="mb-8 reveal">
        <ol class="flex items-center space-x-2 text-xs font-mono uppercase tracking-wider text-slate-400 dark:text-zinc-500">
            <li><a href="{{ route('home') }}" class="hover:text-amber-500 transition-colors">Home</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-amber-500 font-semibold">Shop</li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-12 pb-8 border-b border-slate-200 dark:border-white/5 reveal">
        <div>
            <span class="text-xs font-mono text-amber-600 dark:text-amber-400 uppercase tracking-widest font-semibold">Discovery</span>
            <h1 class="text-3xl md:text-5xl font-serif mt-1 text-slate-950 dark:text-white">Semua Parfum</h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-zinc-400 mt-2">
                Menampilkan {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk terpilih
            </p>
        </div>
        
        <!-- Filter & Sorting Widgets (Responsive Grid) -->
        <div class="flex items-center justify-between md:justify-end gap-4 w-full md:w-auto">
            <!-- Tombol Filter Khusus HP -->
            <button type="button" onclick="openMobileFilter()" class="lg:hidden flex items-center gap-2 px-5 py-2.5 rounded-full bg-white dark:bg-darkcard border border-slate-200 dark:border-white/10 text-xs font-mono font-bold uppercase tracking-wider text-slate-800 dark:text-zinc-300 shadow-md">
                <i class="fas fa-sliders text-amber-500"></i> Filter
            </button>

            <!-- Sorting Widget -->
            <div class="flex items-center gap-3">
                <label for="sortSelect" class="text-xs font-mono uppercase text-slate-400 hidden sm:inline-block">Urutkan:</label>
                <div class="relative">
                    <select name="sort" form="filterForm" id="sortSelect" onchange="document.getElementById('filterForm').submit()" 
                            class="appearance-none bg-white dark:bg-darkcard border border-slate-200 dark:border-white/10 rounded-full px-6 py-2.5 pr-10 text-xs font-semibold focus:outline-none focus:border-amber-500 transition-all text-slate-800 dark:text-zinc-300 shadow-sm">
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

    <!-- Main Content Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        <!-- Sidebar Filter (Desktop: Col-3 static dengan lg:w-full, Mobile: Slide-over Drawer) -->
        <aside id="filterSidebar" class="fixed inset-y-0 left-0 z-50 w-80 max-w-[85vw] bg-white dark:bg-darkbg lg:bg-transparent lg:dark:bg-transparent p-6 lg:p-0 border-r border-slate-200 dark:border-white/10 lg:border-none shadow-2xl lg:shadow-none transform -translate-x-full lg:translate-x-0 overflow-y-auto lg:overflow-visible lg:static lg:col-span-3 lg:w-full reveal">
            <!-- Tombol Tutup Filter (Khusus HP) -->
            <div class="flex lg:hidden justify-end mb-6">
                <button type="button" onclick="closeMobileFilter()" class="w-8 h-8 rounded-full border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>

            <form action="{{ route('shop') }}" method="GET" id="filterForm">
                <div class="lg:sticky lg:top-28 space-y-8">
                    <!-- Filter Top Section -->
                    <div class="flex items-center justify-between">
                        <h5 class="font-serif text-lg font-semibold tracking-wide flex items-center gap-2">
                            <i class="fas fa-sliders text-sm text-amber-500"></i> Filter
                        </h5>
                        <a href="{{ route('shop') }}" class="text-xs font-mono uppercase text-slate-400 hover:text-amber-500 transition-colors">Reset</a>
                    </div>

                    <!-- Gender Category Filter -->
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

                    <!-- Brand Filter -->
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

                    <!-- Max Price Filter -->
                    <div>
                        <h6 class="text-xs font-mono uppercase tracking-wider text-slate-400 mb-4 font-bold">Harga Maksimal</h6>
                        <div class="relative pt-4 px-2">
                            <input type="range" name="max_price" id="priceRange" min="0" max="5000000" step="100000" 
                                   value="{{ request('max_price', 5000000) }}"
                                   class="w-full h-1 bg-slate-200 dark:bg-zinc-800 rounded-lg appearance-none cursor-pointer accent-amber-500">
                            
                            <!-- Custom Tooltip floating on input slider -->
                            <div id="rangeTooltip" class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-slate-950 dark:bg-amber-500 text-white dark:text-black text-[10px] font-mono font-bold px-2 py-0.5 rounded shadow-lg pointer-events-none hidden group-active:block">
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

        <!-- Product Display Area (Right Side) -->
        <main class="lg:col-span-9">
            
            <!-- Active Overall Filter Notification -->
            @php
                $activeFilters = [];

                // Check for active genders
                $reqGenders = (array) request('gender', []);
                foreach ($reqGenders as $g) {
                    if (!empty($g)) {
                        $activeFilters[] = ['type' => 'Kategori', 'label' => $g];
                    }
                }

                // Check for active brands
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

            <!-- Product Grid (Responsive: 2 columns on mobile, 3 columns on tablet/desktop) -->
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6" id="product-container">
                @forelse($products as $product)
                    <div class="perspective-1000 reveal">
                        <!-- Ukuran tinggi kartu total dirampingkan dari h-[380px]/[450px] menjadi h-[290px]/[370px] -->
                        <div class="tilt-card bg-white dark:bg-darkcard rounded-2xl sm:rounded-3xl p-3 sm:p-5 border border-slate-200 dark:border-white/5 shadow-md flex flex-col justify-between h-[290px] sm:h-[370px] transition-all duration-300 group relative">
                            
                            <!-- Admin Controls -->
                            @if (auth()->check() && auth()->user()->role === 'admin')
                                <div class="absolute top-3 right-3 p-1 sm:p-2 z-30 flex gap-2">
                                    <a href="{{ route('products.edit', $product->id) }}"
                                       class="w-7 h-7 sm:w-8 sm:h-8 bg-white/90 dark:bg-zinc-800/90 text-blue-500 rounded-full flex items-center justify-center shadow-md hover:scale-110 transition-transform"
                                       title="Edit Produk">
                                        <i class="fas fa-edit text-[10px] sm:text-xs"></i>
                                    </a>

                                    <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete('{{ $product->id }}', '{{ $product->name }}')"
                                                class="w-7 h-7 sm:w-8 sm:h-8 bg-white/90 dark:bg-zinc-800/90 text-rose-500 rounded-full flex items-center justify-center shadow-md hover:scale-110 transition-transform"
                                                title="Hapus Produk">
                                            <i class="fas fa-trash text-[10px] sm:text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif

                            <!-- Product Image Wrapper (Ukuran gambar dikecilkan untuk menghemat ruang vertikal) -->
                            <div class="w-full h-36 sm:h-48 overflow-hidden rounded-xl sm:rounded-2xl bg-slate-100 dark:bg-zinc-900 relative">
                                <img src="{{ $product->image_url ? (strpos($product->image_url, 'http') === 0 ? $product->image_url : asset('product_image/' . $product->image_url)) : 'https://placehold.co/400x500?text=Scentify' }}"
                                     alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            </div>

                            <!-- Product Information (Jarak nama dan harga dirapatkan) -->
                            <div class="mt-2 flex-grow flex flex-col justify-start">
                                <div>
                                    <small class="text-[9px] sm:text-[10px] font-mono text-amber-600 dark:text-amber-400 uppercase tracking-widest font-semibold block">
                                        {{ $product->brand->name ?? 'Unknown Brand' }}
                                    </small>
                                    <h5 class="text-sm sm:text-base font-serif font-bold text-slate-900 dark:text-white mt-0.5 group-hover:text-amber-500 transition-colors line-clamp-1" title="{{ $product->name }}">
                                        {{ $product->name }}
                                    </h5>
                                </div>

                                <p class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white mt-0.5">
                                    Rp {{ number_format($product->variants->first()->price ?? 0, 0, ',', '.') }}
                                </p>
                            </div>

                            <!-- Action Button (Interactive Variant Modal Trigger) -->
                            <div class="mt-2">
                                @if ($product->variants->isNotEmpty())
                                    @auth
                                        <button type="button" class="variant-selector-btn w-full py-1.5 sm:py-2 text-[10px] sm:text-xs font-semibold tracking-wide bg-slate-900 dark:bg-amber-400 text-white dark:text-black rounded-full hover:bg-amber-500 dark:hover:bg-amber-300 transition-colors duration-300 shadow-md focus:outline-none"
                                                data-product-id="{{ $product->id }}"
                                                data-product-name="{{ $product->name }}"
                                                data-product-brand="{{ $product->brand->name ?? 'Unknown Brand' }}"
                                                data-product-image="{{ $product->image_url ? (strpos($product->image_url, 'http') === 0 ? $product->image_url : asset('product_image/' . $product->image_url)) : 'https://placehold.co/400x500?text=Scentify' }}"
                                                data-product-description="{{ $product->description ?? '' }}"
                                                data-variants="{{ json_encode($product->variants) }}">
                                            Beli
                                        </button>
                                    @else
                                        <a href="{{ route('login') }}" class="block w-full py-1.5 sm:py-2 text-center text-[10px] sm:text-xs font-semibold tracking-wide bg-slate-900 dark:bg-amber-400 text-white dark:text-black rounded-full hover:bg-amber-500 dark:hover:bg-amber-300 transition-colors duration-300 shadow-md"
                                           onclick="event.preventDefault(); showLoginAlert(this.href)">
                                            Beli
                                        </a>
                                    @endauth
                                @else
                                    <button class="w-full py-1.5 sm:py-2 text-[10px] sm:text-xs font-semibold tracking-wide bg-slate-300 dark:bg-zinc-800 text-slate-500 dark:text-zinc-600 rounded-full cursor-not-allowed" disabled>
                                        Habis
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
                        <h3 class="font-serif text-lg">Tidak ada produk</h3>
                        <p class="text-xs text-slate-400 mt-1">Gunakan kombinasi pencarian atau filter lain.</p>
                    </div>
                @endforelse
            </div>

            <!-- Custom Pagination (Tailwind Adaptive Theme) -->
            <div class="mt-16 pt-8 border-t border-slate-200 dark:border-white/5 flex justify-center custom-pagination reveal">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>

        </main>
    </div>
</div>

<!-- =========================================================================
     MODAL SELEKSI VARIAN PREMIUM (Tailwind Glassmorphic Design)
     ========================================================================= -->
<div id="variantModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-md opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="bg-white dark:bg-darkcard border border-slate-200 dark:border-white/5 rounded-3xl w-full max-w-3xl overflow-hidden shadow-2xl transform scale-95 transition-transform duration-300 max-h-[90vh] overflow-y-auto">
        <div class="p-6 md:p-8">
            <div class="flex justify-end mb-2">
                <button onclick="closeVariantModal()" class="w-8 h-8 rounded-full border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                <!-- Modal Image -->
                <div class="md:col-span-5">
                    <div class="w-full h-64 md:h-80 overflow-hidden rounded-2xl bg-slate-100 dark:bg-zinc-900">
                        <img id="modalProductImage" src="" alt="Product" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Modal Info -->
                <div class="md:col-span-7 flex flex-col justify-between h-full">
                    <div>
                        <small id="modalProductBrand" class="text-[10px] font-mono text-amber-600 dark:text-amber-400 uppercase tracking-widest font-semibold block"></small>
                        <h4 id="modalProductName" class="text-2xl font-serif font-bold text-slate-950 dark:text-white mt-1"></h4>
                        <div id="modalProductPrice" class="text-xl font-bold text-amber-600 dark:text-amber-400 mt-3">Rp 0</div>
                        <p id="modalProductDescription" class="text-xs text-slate-500 dark:text-zinc-400 leading-relaxed mt-4 line-clamp-3"></p>

                        <!-- Variant Size Selector Options -->
                        <h6 class="text-xs font-mono uppercase text-slate-400 tracking-wider mt-6 mb-3 font-semibold">Ukuran Tersedia:</h6>
                        <div id="variantsList" class="flex flex-wrap gap-2"></div>

                        <div id="variantNotice" class="hidden mt-4 text-xs text-rose-500 flex items-center gap-1.5 font-medium">
                            <i class="fas fa-exclamation-circle"></i> Pilih salah satu varian terlebih dahulu.
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mt-8">
                        <button type="button" id="addToCartBtn" onclick="submitVariantSelection()" disabled
                                class="py-3.5 font-semibold text-xs tracking-wider uppercase bg-slate-900 dark:bg-amber-400 text-white dark:text-black rounded-xl hover:bg-amber-500 dark:hover:bg-amber-300 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                            Tambah ke Keranjang
                        </button>
                        <button type="button" onclick="closeVariantModal()"
                                class="py-3.5 font-semibold text-xs tracking-wider uppercase border border-slate-200 dark:border-white/10 text-slate-700 dark:text-zinc-300 rounded-xl hover:bg-slate-50 dark:hover:bg-zinc-800 transition-all">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Laravel Cart Submission Form -->
<form id="hiddenCartForm" action="" method="POST" class="hidden">
    @csrf
</form>

<!-- Styling Kustom Khusus Komponen Pagination / Slide Range (Tailwind Compliant) -->
<style>
    .custom-pagination .page-link {
        color: inherit;
        border: none;
        background: transparent;
    }
    .custom-pagination .page-item.active .page-link {
        background-color: #f59e0b !important; /* Amber-500 */
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
    /* Hidden default arrow custom styling on sidebars */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: rgba(156, 163, 175, 0.3);
        border-radius: 10px;
    }
</style>

<!-- =========================================================================
     SCRIPTS & EVENT HANDLERS (Shop Page Specific Scripts)
     ========================================================================= -->
<script>
    // FUNGSI KHUSUS MOBILE FILTER DRAWER
    function openMobileFilter() {
        const sidebar = document.getElementById('filterSidebar');
        const overlay = document.getElementById('filterOverlay');
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('opacity-0', 'pointer-events-none');
    }

    function closeMobileFilter() {
        const sidebar = document.getElementById('filterSidebar');
        const overlay = document.getElementById('filterOverlay');
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('opacity-0', 'pointer-events-none');
    }

    // 1. Logic Price Range Slider
    (function() {
        const rangeInput = document.getElementById('priceRange');
        const rangeTooltip = document.getElementById('rangeTooltip');
        const priceLabel = document.getElementById('priceLabel');

        function updateUI() {
            if (!rangeInput) return;
            const val = Number(rangeInput.value);
            const min = Number(rangeInput.min) || 0;
            const max = Number(rangeInput.max) || 5000000;

            const percent = ((val - min) / (max - min)) * 100;

            const formatted = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            }).format(val);

            if (rangeTooltip) {
                rangeTooltip.innerText = formatted;
                rangeTooltip.style.left = `calc(${percent}% + (${8 - percent * 0.16}px))`;
            }
            if (priceLabel) {
                priceLabel.innerText = formatted;
            }
        }

        if (rangeInput) {
            rangeInput.addEventListener('input', updateUI);
            updateUI();
        }
    })();

    // 2. Premium Variant Modal Logic
    let selectedVariant = null;
    let variantsMap = {};

    document.querySelectorAll('.variant-selector-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const productName = this.dataset.productName;
            const productBrand = this.dataset.productBrand;
            const productImage = this.dataset.productImage;
            const productDescription = this.dataset.productDescription;
            const variants = JSON.parse(this.dataset.variants);

            openVariantModal(productId, productName, productBrand, productImage, productDescription, variants);
        });
    });

    function openVariantModal(productId, productName, productBrand, productImage, productDescription, variants) {
        selectedVariant = null;
        variantsMap = {};
        
        document.getElementById('modalProductImage').src = productImage;
        document.getElementById('modalProductName').textContent = productName;
        document.getElementById('modalProductBrand').textContent = productBrand;
        document.getElementById('modalProductDescription').textContent = productDescription;

        variants.forEach(variant => {
            variantsMap[variant.id] = variant;
        });

        let variantsList = '';
        variants.forEach(variant => {
            const stock = variant.stock || 0;
            const isOutOfStock = stock <= 0;

            variantsList += `
                <button type="button" class="variant-btn px-4 py-2 text-xs font-mono font-bold border-2 border-slate-200 dark:border-white/10 rounded-xl hover:border-amber-500 dark:hover:border-amber-400 transition-all ${isOutOfStock ? 'opacity-40 cursor-not-allowed' : ''}"
                        onclick="${!isOutOfStock ? `selectVariant(this, ${variant.id})` : ''}"
                        ${isOutOfStock ? 'disabled' : ''}>
                    ${variant.size}ml
                </button>
            `;
        });

        document.getElementById('variantsList').innerHTML = variantsList;
        document.getElementById('variantNotice').classList.add('hidden');
        document.getElementById('addToCartBtn').disabled = true;
        document.getElementById('modalProductPrice').textContent = 'Rp 0';

        // Animate modal popup with class
        const modal = document.getElementById('variantModal');
        modal.classList.remove('opacity-0', 'pointer-events-none');
        modal.querySelector('.bg-white').classList.remove('scale-95');
    }

    function selectVariant(element, variantId) {
        document.querySelectorAll('.variant-btn').forEach(btn => {
            btn.classList.remove('border-amber-500', 'text-amber-500', 'bg-amber-500/10');
            btn.classList.add('border-slate-200', 'dark:border-white/10');
        });

        element.classList.remove('border-slate-200', 'dark:border-white/10');
        element.classList.add('border-amber-500', 'text-amber-500', 'bg-amber-500/10');

        selectedVariant = variantId;
        document.getElementById('addToCartBtn').disabled = false;
        document.getElementById('variantNotice').classList.add('hidden');

        const priceFormatted = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        }).format(variantsMap[variantId].price);

        document.getElementById('modalProductPrice').textContent = priceFormatted;
    }

    function closeVariantModal() {
        const modal = document.getElementById('variantModal');
        modal.classList.add('opacity-0', 'pointer-events-none');
        modal.querySelector('.bg-white').classList.add('scale-95');
    }

    function submitVariantSelection() {
        if (!selectedVariant) {
            document.getElementById('variantNotice').classList.remove('hidden');
            return;
        }

        document.getElementById('hiddenCartForm').action = `/cart/add/${selectedVariant}`;
        document.getElementById('hiddenCartForm').submit();
    }

    // 3. Unauthorized Add to Cart Interceptor
    function showLoginAlert(loginUrl) {
        Swal.fire({
            title: 'Opps, Belum Login!',
            text: 'Silakan login terlebih dahulu untuk mulai memasukkan parfum ke keranjang Scentify.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Login Sekarang',
            cancelButtonText: 'Nanti Saja',
            customClass: {
                popup: 'rounded-[1.5rem] dark-swal shadow-2xl'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = loginUrl;
            }
        });
    }

    // 4. Admin Delete Protection Confirm Dialog
    function confirmDelete(productId, productName) {
        Swal.fire({
            title: 'Pindahkan ke Sampah?',
            text: "Produk '" + productName + "' akan disembunyikan dari katalog Scentify.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff2a5f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash mr-2"></i>Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-[1.5rem] dark-swal shadow-2xl',
                confirmButton: 'rounded-full px-4',
                cancelButton: 'rounded-full px-4'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + productId).submit();
            }
        });
    }
</script>
@endsection