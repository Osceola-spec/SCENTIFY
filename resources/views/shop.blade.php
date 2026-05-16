@extends('base.base')

@section('content')
    <div class="container py-5 mt-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-dark text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Shop</li>
            </ol>
        </nav>

        <!-- Header & Sorting -->
        <div class="row mb-5 align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-3">
                    <h1 class="fw-light mb-0">Semua Parfum</h1>

                    <!-- Tombol Insert Product (Tampil Langsung) -->
                    <a href="{{ route('products.insert') }}" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                        <i class="fas fa-plus"></i> Tambah Produk
                    </a>
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
            <!-- Sidebar Filters -->
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
                                        {{ in_array($brand->id, request('brand', [])) ? 'checked' : '' }}>
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

            <!-- Product Grid -->
            <div class="col-lg-9">
                <div class="row g-4" id="product-container">

                    <!-- LOOPING DATA DARI DATABASE -->
                    @forelse($products as $product)
                        <div class="col-6 col-md-4">
                            <div class="card product-card shadow-sm h-100 position-relative">

                                <!-- Tombol Edit & Delete (Tampil Langsung) -->
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
                                        ? asset('product_image/' . $product->image_url)
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

                                    <button class="btn btn-dark w-100 mt-auto rounded-pill" onclick="addToCart()">Tambah
                                        ke
                                        Keranjang</button>
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

                <!-- Pagination -->
                <div class="mt-5 pt-4 border-top border-secondary-subtle d-flex justify-content-center custom-pagination">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>

    <!-- Custom CSS -->
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
            /* Default ke kanan karena value awal 5jt */
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
        // Gunakan fungsi yang langsung dipanggil agar tidak konflik
        (function() {
            const rangeInput = document.getElementById('priceRange');
            const rangeTooltip = document.getElementById('rangeTooltip');
            const priceLabel = document.getElementById('priceLabel');

            function updateUI() {
                const val = Number(rangeInput.value);
                const min = Number(rangeInput.min) || 0;
                const max = Number(rangeInput.max) || 5000000;

                // 1. Hitung Persentase
                const percent = ((val - min) / (max - min)) * 100;

                // 2. Format Rupiah
                const formatted = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0
                }).format(val);

                // 3. Update Konten Teks
                rangeTooltip.innerText = formatted;
                priceLabel.innerText = formatted;

                // 4. Update Posisi Balon (PENTING: Rumus sinkronisasi)
                rangeTooltip.style.left = `calc(${percent}% + (${8 - percent * 0.16}px))`;
            }

            if (rangeInput) {
                rangeInput.addEventListener('input', updateUI);
                // Panggil saat pertama kali load
                updateUI();
            }
        })();
    </script>
@endsection

<script>
    function confirmDelete(productId, productName) {
        Swal.fire({
            title: 'Pindahkan ke Sampah?',
            text: "Produk '" + productName + "' akan disembunyikan dari toko Scentify.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#212529', // Hitam sesuai tema Scentify
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
                // Submit form berdasarkan ID unik produk
                document.getElementById('delete-form-' + productId).submit();
            }
        })
    }
</script>
