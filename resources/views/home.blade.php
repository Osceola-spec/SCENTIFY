@extends('base.base')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <p class="text-uppercase tracking-wider mb-3 text-warning">Essence of Elegance</p>
        <h1 class="hero-title">Temukan Aura <br> Khas Anda</h1>
        <p class="lead mb-4 mx-auto" style="max-width: 600px;">Kurasi parfum desainer, niche, dan lokal premium untuk mengekspresikan karakter unik Anda.</p>
        <a href="#produk-terlaris" class="btn btn-warning btn-lg px-5 py-3 rounded-pill">Mulai Belanja</a>
    </div>
</section>

<!-- Categories Section -->
<section id="koleksi" class="py-5">
    <div class="container">
        <h2 class="text-center fw-light mb-5">Pilihan Koleksi</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <a href="#" class="category-card shadow-sm">
                    <img src="https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&q=80&w=600" alt="[Gambar Parfum Designer]">
                    <h3>Designer</h3>
                </a>
            </div>
            <div class="col-md-4">
                <a href="#" class="category-card shadow-sm">
                    <img src="https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&q=80&w=600" alt="[Gambar Parfum Niche]">
                    <h3>Niche</h3>
                </a>
            </div>
            <div class="col-md-4">
                <a href="#" class="category-card shadow-sm">
                    <img src="https://images.unsplash.com/photo-1592914610354-fd354d45e5b0?auto=format&fit=crop&q=80&w=600" alt="[Gambar Parfum Lokal Premium]">
                    <h3>Lokal Premium</h3>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section id="produk-terlaris" class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <h2 class="fw-light mb-0">Produk Terlaris</h2>
            <a href="#" class="text-dark text-decoration-none border-bottom border-dark">Lihat Semua</a>
        </div>
        
        <div class="row g-4" id="product-container">
            <!-- Data produk akan di-render menggunakan JavaScript dari file layout/base -->
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="py-5 bg-dark text-light text-center">
    <div class="container py-4">
        <h3 class="fw-light mb-3">Bergabung dengan Scentify</h3>
        <p class="mb-4 opacity-75">Dapatkan penawaran eksklusif dan info rilis parfum terbaru.</p>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <input type="email" class="form-control form-control-lg bg-transparent text-light" placeholder="Alamat Email Anda" style="border-color: rgba(255,255,255,0.2);">
                    <button class="btn btn-warning px-4" type="button">Subscribe</button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection