@extends('base.base') @section('title', 'Koleksi Brand Resmi Scentify')

@section('content')
    <div class="container py-5" style="min-height: 80vh;">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-dark mb-2">Our Official Brands</h2>
            <p class="text-muted mx-auto" style="max-width: 600px;">
                Jelajahi berbagai pilihan brand parfum ternama dan eksklusif yang dikurasi khusus untuk melengkapi karakter
                dan keharuman harian Anda.
            </p>
            <div class="bg-warning mx-auto rounded" style="width: 60px; height: 3px;"></div>
        </div>

        <div class="row g-4 justify-content-center">
            @forelse($brands as $brand)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-4 bg-white position-relative overflow-hidden"
                        style="transition: all 0.3s ease; border: 1px solid #f8f9fa!important;">

                        <div class="d-flex align-items-center justify-content-center mx-auto mb-3 border rounded-circle bg-light"
                            style="width: 100px; height: 100px; overflow: hidden;">
                            @if ($brand->logo_url)
                                <img src="{{ asset('storage/' . $brand->logo_url) }}" alt="{{ $brand->name }}"
                                    style="max-width: 80%; max-height: 80%; object-fit: contain;">
                            @else
                                <span class="fs-2 fw-bold text-muted opacity-50">
                                    {{ strtoupper(substr($brand->name, 0, 1)) }}
                                </span>
                            @endif
                        </div>

                        <h5 class="fw-bold text-dark mb-1 fs-6">{{ $brand->name }}</h5>
                        <small class="text-muted d-block mb-3">Official Merchant</small>

                        <a href="{{ route('shop', ['brand' => $brand->id]) }}"
                            class="btn btn-sm btn-outline-dark rounded-pill px-3 py-1.5 fs-7 mt-auto">
                            Lihat Produk
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 text-muted">
                    <i class="fas fa-tags fa-3x mb-3 text-light-shadow"></i>
                    <p class="mb-0">Belum ada brand resmi yang tersedia saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>

    <style>
        /* Efek hover mewah pada card brand */
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, .08) !important;
        }
    </style>
@endsection
