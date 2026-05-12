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
            <p class="text-muted mt-2 mb-0">Menampilkan {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk</p>
        </div>
        <div class="col-md-6 d-flex justify-content-md-end mt-3 mt-md-0">
            <div class="d-flex align-items-center gap-2">
                <label for="sortSelect" class="text-muted text-nowrap mb-0">Urutkan:</label>
                <select class="form-select w-auto border-dark rounded-pill" id="sortSelect">
                    <option selected>Terbaru</option>
                    <option value="asc">Harga: Rendah ke Tinggi</option>
                    <option value="desc">Harga: Tinggi ke Rendah</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row g-5">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <div class="sticky-top" style="top: 100px;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Filter</h5>
                    <a href="#" class="text-muted text-decoration-none small">Reset</a>
                </div>
                
                <!-- Filter Kategori -->
                <div class="mb-4 pb-4 border-bottom border-secondary-subtle">
                    <h6 class="fw-bold mb-3">Kategori</h6>
                    @foreach(['Men', 'Women', 'Unisex'] as $gender)
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" value="{{ $gender }}" id="cat{{ $gender }}">
                        <label class="form-check-label text-muted d-flex justify-content-between w-100" for="cat{{ $gender }}">
                            <span>{{ $gender }}</span>
                        </label>
                    </div>
                    @endforeach
                </div>

                <!-- Filter Brand -->
                <div class="mb-4 pb-4 border-bottom border-secondary-subtle">
                    <h6 class="fw-bold mb-3">Brand</h6>
                    @foreach($brands as $brand)
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" value="{{ $brand->id }}" id="brand{{ $brand->id }}">
                        <label class="form-check-label text-muted" for="brand{{ $brand->id }}">{{ $brand->name }}</label>
                    </div>
                    @endforeach
                </div>

                <!-- Filter Harga -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-3">Rentang Harga</h6>
                    <input type="range" class="form-range" id="priceRange" min="0" max="10" step="1">
                    <div class="d-flex justify-content-between text-muted small mt-2">
                        <span>Rp 0</span>
                        <span>Rp 10jt+</span>
                    </div>
                </div>
                
                <button class="btn btn-dark w-100 rounded-pill mt-3 py-2">Terapkan Filter</button>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="col-lg-9">
            <div class="row g-4" id="product-container">
                
                <!-- LOOPING DATA DARI DATABASE -->
                @forelse($products as $product)
                <div class="col-6 col-md-4">
                    <div class="card product-card shadow-sm h-100 position-relative">
                        
                        <!-- Tombol Edit & Delete (Tampil Langsung) -->
                        <div class="position-absolute top-0 end-0 p-2 z-3 d-flex gap-1">
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-light shadow-sm text-primary rounded-circle" title="Edit Produk"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light shadow-sm text-danger rounded-circle" title="Hapus Produk"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>

                        <div class="product-img-wrapper" style="height: 250px; overflow: hidden; background-color: #eee;">
                            <img src="{{ $product->image_url ? asset('product_image/' . $product->image_url) : 
                             'https://placehold.co/200x200?text=No+Image' }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <small class="text-muted text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">
                                {{ $product->brand->name ?? 'Unknown Brand' }}
                            </small>
                            <h5 class="card-title fw-light mb-2 text-truncate" title="{{ $product->name }}">{{ $product->name }}</h5>
                            
                            <p class="card-text fw-bold mb-3">
                                Rp {{ number_format($product->variants->first()->price ?? 0, 0, ',', '.') }}
                            </p>
                            
                            <button class="btn btn-dark w-100 mt-auto rounded-pill" onclick="addToCart()">Tambah ke Keranjang</button>
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
@endsection