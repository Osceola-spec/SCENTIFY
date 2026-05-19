@extends('base.base')

@section('content')
    <div class="container py-5 mt-4">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-dark text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop') }}" class="text-dark text-decoration-none">Shop</a></li>
                <li class="breadcrumb-item active" aria-current="page">Checkout</li>
            </ol>
        </nav>

        <h2 class="fw-light mb-4">Checkout</h2>

        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf
            <div class="row g-5">

                <div class="col-md-7">
                    <h5 class="fw-bold mb-4 pb-2 border-bottom">1. Informasi Pengiriman</h5>

                    <div class="row g-3 mb-5">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Nama Depan</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Nama Belakang</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Email</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ auth()->user()->email ?? '' }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Nomor Telepon / WhatsApp</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted small">Alamat Lengkap</label>
                            <textarea name="address" class="form-control" rows="3" placeholder="Nama Jalan, Gedung, No. Rumah, RT/RW"
                                required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Kota / Kabupaten</label>
                            <input type="text" name="city" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Kode Pos</label>
                            <input type="text" name="postal_code" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card border-0 bg-light rounded-4 sticky-top" style="top: 100px;">
                        <div class="card-body p-4 p-md-5">
                            <h5 class="fw-bold mb-4 pb-3 border-bottom border-secondary-subtle">Ringkasan Pesanan</h5>

                            @foreach ($cart as $item)
                                <div class="d-flex align-items-center mb-4 pb-2">
                                    <div class="bg-white rounded p-1 border" style="width: 60px; height: 60px;">
                                        <img src="{{ strpos($item['image_url'], 'http') === 0 ? $item['image_url'] : asset('product_image/' . $item['image_url']) }}" alt="{{ $item['product_name'] }}"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                    <div class="ms-3 flex-grow-1">
                                        <h6 class="mb-0 fw-bold">{{ $item['product_name'] }}</h6>
                                        <small class="text-muted">{{ $item['size'] }} | Qty: {{ $item['quantity'] }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="fw-bold">Rp
                                            {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endforeach

                            <div class="d-flex justify-content-between mb-2 mt-4 pt-3 border-top">
                                <span class="text-muted">Subtotal</span>
                                <span class="fw-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Pengiriman</span>
                                <span class="fw-bold">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-4 pb-4 border-bottom border-secondary-subtle">
                                <span class="text-muted">Pajak (11%)</span>
                                <span class="fw-bold">Rp {{ number_format($taxAmount, 0, ',', '.') }}</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-5">
                                <h5 class="fw-bold mb-0">Total</h5>
                                <h4 class="fw-bold mb-0 text-dark">Rp {{ number_format($totalAmount, 0, ',', '.') }}</h4>
                            </div>

                            <button type="submit" class="btn btn-dark btn-lg w-100 rounded-pill py-3 fw-bold">
                                Lanjutkan Pembayaran <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                            <p class="text-center text-muted small mt-3 mb-0">
                                <i class="fas fa-shield-alt me-1"></i> Pembayaran aman didukung oleh Midtrans.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
