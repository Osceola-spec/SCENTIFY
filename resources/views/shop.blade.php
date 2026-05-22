@extends('base.base')

@section('content')
    <div class="container py-5 mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-dark text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Shop</li>
            </ol>
        </nav>

        <div class="row mb-5 align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-3">
                    <h1 class="fw-light mb-0">Semua Parfum</h1>
                </div>
                <p class="text-muted mt-2 mb-0">Menampilkan {{ $products->firstItem() ?? 0 }} -
                    {{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk</p>
            </div>
            <div class="col-md-6 d-flex justify-content-md-end mt-3 mt-md-0">
                <div class="d-flex align-items-center gap-2">
                    <label for="sortSelect" class="text-muted text-nowrap mb-0">Urutkan:</label>
                    <select name="sort" form="filterForm" class="form-select w-auto border-dark rounded-pill"
                        id="sortSelect" onchange="document.getElementById('filterForm').submit()">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga: Rendah ke
                            Tinggi</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga: Tinggi ke
                            Rendah</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row g-5">
            <div class="col-lg-3">
                <form action="{{ route('shop') }}" method="GET" id="filterForm">
                    <div class="sticky-top" style="top: 100px;">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0">Filter</h5>
                            <a href="{{ route('shop') }}" class="text-muted text-decoration-none small">Reset</a>
                        </div>

                        <div class="mb-4 pb-4 border-bottom border-secondary-subtle">
                            <h6 class="fw-bold mb-3">Kategori</h6>
                            @foreach (['Men', 'Women', 'Unisex'] as $gender)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="gender[]"
                                        value="{{ $gender }}" id="cat{{ $gender }}"
                                        {{ in_array($gender, request('gender', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label text-muted d-flex justify-content-between w-100"
                                        for="cat{{ $gender }}">
                                        <span>{{ $gender }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div class="mb-4 pb-4 border-bottom border-secondary-subtle">
                            <h6 class="fw-bold mb-3">Brand</h6>
                            @foreach ($brands as $brand)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="brand[]"
                                        value="{{ $brand->id }}" id="brand{{ $brand->id }}"
                                        {{ in_array($brand->id, (array) request('brand', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label text-muted"
                                        for="brand{{ $brand->id }}">{{ $brand->name }}</label>
                                </div>
                            @endforeach
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Harga Maksimal</h6>
                            <div class="position-relative pt-4 px-2">
                                <input type="range" class="form-range" name="max_price" id="priceRange" min="0"
                                    max="5000000" step="100000" value="{{ request('max_price', 5000000) }}">

                                <div id="rangeTooltip" class="range-tooltip">
                                    Rp 5.000.000
                                </div>
                            </div>

                            <div class="d-flex justify-content-between text-muted small mt-2">
                                <span>Rp 0</span>
                                <span id="priceLabel">Rp 5.000.000</span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 rounded-pill mt-3 py-2">Terapkan Filter</button>
                    </div>
                </form>
            </div>

            <div class="col-lg-9">

                @if (request()->has('brand') && request('brand') != '')
                    @php
                        // Ambil ID brand baik berupa string tunggal maupun array dari checkbox
                        $brandParam = request('brand');
                        $brandId = is_array($brandParam) ? $brandParam[0] ?? null : $brandParam;
                        $activeBrand = $brands->firstWhere('id', $brandId);
                    @endphp

                    @if ($activeBrand)
                        <div
                            class="alert alert-light border rounded-4 d-flex align-items-center justify-content-between mb-4 p-3 shadow-sm">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-filter text-muted small"></i>
                                <span class="text-muted small">Menampilkan koleksi resmi dari brand:</span>
                                <span
                                    class="badge bg-dark rounded-pill px-3 py-2 fw-medium fs-7">{{ $activeBrand->name }}</span>
                            </div>
                            <a href="{{ route('shop') }}"
                                class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 30px; height: 30px;" title="Hapus Filter">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    @endif
                @endif

                <div class="row g-4" id="product-container">

                    @forelse($products as $product)
                        <div class="col-6 col-md-4">
                            <div class="card product-card shadow-sm h-100 position-relative">

                                @if (auth()->check() && auth()->user()->role === 'admin')
                                    <div class="position-absolute top-0 end-0 p-2 z-3 d-flex gap-1">
                                        <a href="{{ route('products.edit', $product->id) }}"
                                            class="btn btn-sm btn-light shadow-sm text-primary rounded-circle"
                                            title="Edit Produk">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form id="delete-form-{{ $product->id }}"
                                            action="{{ route('products.destroy', $product->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn btn-sm btn-light shadow-sm text-danger rounded-circle"
                                                title="Hapus Produk"
                                                onclick="confirmDelete('{{ $product->id }}', '{{ $product->name }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif

                                <div class="product-img-wrapper"
                                    style="height: 250px; overflow: hidden; background-color: #eee;">
                                    <img src="{{ $product->image_url
                                        ? (strpos($product->image_url, 'http') === 0 ? $product->image_url : asset('product_image/' . $product->image_url))
                                        : 'https://placehold.co/200x200?text=No+Image' }}"
                                        alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <small class="text-muted text-uppercase"
                                        style="font-size: 0.7rem; letter-spacing: 1px;">
                                        {{ $product->brand->name ?? 'Unknown Brand' }}
                                    </small>
                                    <h5 class="card-title fw-light mb-2 text-truncate" title="{{ $product->name }}">
                                        {{ $product->name }}</h5>

                                    <p class="card-text fw-bold mb-3">
                                        Rp {{ number_format($product->variants->first()->price ?? 0, 0, ',', '.') }}
                                    </p>

                                    @if ($product->variants->isNotEmpty())
                                        @auth
                                            <button type="button" class="btn btn-dark w-100 rounded-pill mt-auto variant-selector-btn"
                                                data-product-id="{{ $product->id }}"
                                                data-product-name="{{ $product->name }}"
                                                data-product-brand="{{ $product->brand->name ?? 'Unknown Brand' }}"
                                                data-product-image="{{ $product->image_url ? (strpos($product->image_url, 'http') === 0 ? $product->image_url : asset('product_image/' . $product->image_url)) : 'https://placehold.co/200x200?text=No+Image' }}"
                                                data-product-description="{{ $product->description ?? '' }}"
                                                data-variants="{{ json_encode($product->variants) }}">
                                                Tambah ke Keranjang
                                            </button>
                                        @else
                                            <a href="{{ route('login') }}"
                                                class="btn btn-dark w-100 mt-auto rounded-pill text-center text-decoration-none"
                                                onclick="event.preventDefault(); Swal.fire({
               title: 'Opps, Belum Login!',
               text: 'Silakan login terlebih dahulu untuk mulai memasukkan parfum ke keranjang Scentify.',
               icon: 'warning',
               showCancelButton: true,
               confirmButtonColor: '#198754',
               cancelButtonColor: '#6c757d',
               confirmButtonText: 'Login Sekarang',
               cancelButtonText: 'Nanti Saja',
               customClass: {
                   popup: 'rounded-4'
               }
           }).then((result) => {
               if (result.isConfirmed) {
                   window.location.href = this.href;
               }
           });">
                                                Tambah ke Keranjang
                                            </a>
                                        @endauth
                                    @else
                                        <button class="btn btn-secondary w-100 mt-auto rounded-pill" disabled>
                                            Stok Habis
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Tidak ada produk yang ditemukan.</p>
                        </div>
                    @endforelse

                </div>

                <div class="mt-5 pt-4 border-top border-secondary-subtle d-flex justify-content-center custom-pagination">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>

    <style>
        .custom-pagination .page-link {
            color: #212529;
            border: none;
            background: transparent;
        }

        .custom-pagination .page-item.active .page-link {
            background-color: #212529 !important;
            border-color: #212529 !important;
            color: #fff !important;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .custom-pagination .page-link:hover,
        .custom-pagination .page-link:focus {
            background-color: #f8f9fa !important;
            color: #000 !important;
            box-shadow: none;
        }
    </style>

    <style>
        .range-tooltip {
            position: absolute;
            top: -10px;
            left: 100%;
            transform: translateX(-50%);
            background: #212529;
            color: white;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 100;
            pointer-events: none;
            display: none;
        }

        .range-tooltip::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid #212529;
        }

        #priceRange:active~.range-tooltip,
        #priceRange:focus~.range-tooltip {
            display: block;
        }
    </style>

    <script>
        (function() {
            const rangeInput = document.getElementById('priceRange');
            const rangeTooltip = document.getElementById('rangeTooltip');
            const priceLabel = document.getElementById('priceLabel');

            function updateUI() {
                const val = Number(rangeInput.value);
                const min = Number(rangeInput.min) || 0;
                const max = Number(rangeInput.max) || 5000000;

                const percent = ((val - min) / (max - min)) * 100;

                const formatted = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0
                }).format(val);

                rangeTooltip.innerText = formatted;
                priceLabel.innerText = formatted;

                rangeTooltip.style.left = `calc(${percent}% + (${8 - percent * 0.16}px))`;
            }

            if (rangeInput) {
                rangeInput.addEventListener('input', updateUI);
                updateUI();
            }
        })();
    </script>

    <style>
        .variant-modal-img {
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
        }

        .variant-option {
            display: inline-block;
            padding: 8px 16px;
            border: 2px solid #dee2e6;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-right: 8px;
            margin-bottom: 8px;
            font-size: 0.9rem;
            background-color: #fff;
        }

        .variant-option:hover {
            border-color: #212529;
            background-color: #f8f9fa;
        }

        .variant-option.selected {
            border-color: #212529;
            background-color: #212529;
            color: white;
        }

        .product-price-display {
            font-size: 1.5rem;
            font-weight: 600;
            color: #212529;
            margin: 15px 0;
        }

        .product-description {
            font-size: 0.95rem;
            color: #6c757d;
            line-height: 1.6;
            margin: 15px 0;
        }
    </style>

    <!-- Modal untuk Memilih Varian Produk -->
    <div class="modal fade" id="variantModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="row">
                        <div class="col-md-5">
                            <img id="modalProductImage" src="" alt="Product" class="variant-modal-img w-100">
                        </div>
                        <div class="col-md-7">
                            <small id="modalProductBrand" class="text-muted text-uppercase"
                                style="font-size: 0.7rem; letter-spacing: 1px;"></small>
                            <h4 id="modalProductName" class="fw-light mt-2 mb-0"></h4>
                            <div id="modalProductPrice" class="product-price-display">Rp 0</div>

                            <p id="modalProductDescription" class="product-description"></p>

                            <h6 class="fw-bold mb-2">Ukuran:</h6>
                            <div id="variantsList" style="margin-bottom: 15px;"></div>

                            <div id="variantNotice" class="alert alert-danger mt-3" style="display: none;">
                                <small>Pilih varian terlebih dahulu.</small>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="button" id="addToCartBtn" class="btn btn-dark rounded-pill py-2"
                                    onclick="submitVariantSelection()" disabled>
                                    Tambah ke Keranjang
                                </button>
                                <button type="button" class="btn btn-outline-secondary rounded-pill py-2"
                                    data-bs-dismiss="modal">
                                    Batal
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Hidden untuk Submit -->
    <form id="hiddenCartForm" action="" method="POST" style="display: none;">
        @csrf
    </form>

    <script>
        let selectedVariant = null;
        let variantsMap = {};

        // Event listener untuk tombol variant selector
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
            
            // Update modal content
            document.getElementById('modalProductImage').src = productImage;
            document.getElementById('modalProductName').textContent = productName;
            document.getElementById('modalProductBrand').textContent = productBrand;
            document.getElementById('modalProductDescription').textContent = productDescription;

            // Create map of variants for quick lookup
            variants.forEach(variant => {
                variantsMap[variant.id] = variant;
            });

            // Generate variant options - only show size name
            let variantsList = '';
            variants.forEach(variant => {
                const stock = variant.stock || 0;
                const isOutOfStock = stock === 0 || stock < 0;

                variantsList += `
                    <div class="variant-option ${isOutOfStock ? 'opacity-50' : ''}" 
                         onclick="${!isOutOfStock ? `selectVariant(${variant.id})` : ''}"
                         style="${isOutOfStock ? 'pointer-events: none;' : ''}">
                        ${variant.size}
                    </div>
                `;
            });

            document.getElementById('variantsList').innerHTML = variantsList;
            document.getElementById('variantNotice').style.display = 'none';
            document.getElementById('addToCartBtn').disabled = true;
            document.getElementById('modalProductPrice').textContent = 'Rp 0';

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('variantModal'));
            modal.show();
        }

        function selectVariant(variantId) {
            // Remove previous selection
            document.querySelectorAll('.variant-option').forEach(opt => {
                opt.classList.remove('selected');
            });

            // Find and select the clicked element
            document.querySelectorAll('.variant-option').forEach(opt => {
                if (opt.textContent.trim() === variantsMap[variantId].size) {
                    opt.classList.add('selected');
                }
            });

            selectedVariant = variantId;
            document.getElementById('addToCartBtn').disabled = false;
            document.getElementById('variantNotice').style.display = 'none';

            // Update price
            const priceFormatted = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            }).format(variantsMap[variantId].price);

            document.getElementById('modalProductPrice').textContent = priceFormatted;
        }

        function submitVariantSelection() {
            if (!selectedVariant) {
                document.getElementById('variantNotice').style.display = 'block';
                return;
            }

            document.getElementById('hiddenCartForm').action = `/cart/add/${selectedVariant}`;
            document.getElementById('hiddenCartForm').submit();
        }
    </script>
@endsection

<script>
    function confirmDelete(productId, productName) {
        Swal.fire({
            title: 'Pindahkan ke Sampah?',
            text: "Produk '" + productName + "' akan disembunyikan dari toko Scentify.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#212529',
            cancelButtonColor: '#d33',
            confirmButtonText: '<i class="fas fa-trash me-2"></i>Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-4 shadow-lg',
                confirmButton: 'rounded-pill px-4',
                cancelButton: 'rounded-pill px-4'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + productId).submit();
            }
        })
    }
</script>
