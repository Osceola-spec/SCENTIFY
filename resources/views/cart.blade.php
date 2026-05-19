<!-- resources/views/cart.blade.php -->
@extends('base.base')

@section('content')
    <div class="container py-5 mt-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-dark text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop') }}" class="text-dark text-decoration-none">Shop</a></li>
                <li class="breadcrumb-item active" aria-current="page">Keranjang</li>
            </ol>
        </nav>

        <h2 class="fw-light mb-4">Keranjang Belanja Anda</h2>

        @if (count($cart) > 0)
            <div class="row g-5">
                <!-- Tabel Item Keranjang -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-body p-0">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="ps-4 py-3 text-muted fw-normal">Produk</th>
                                        <th scope="col" class="py-3 text-muted fw-normal">Harga</th>
                                        <th scope="col" class="py-3 text-muted fw-normal">Kuantitas</th>
                                        <th scope="col" class="py-3 text-muted fw-normal">Total</th>
                                        <th scope="col" class="pe-4 py-3 text-end"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cart as $id => $item)
                                        <tr>
                                            <td class="ps-4 py-4">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ strpos($item['image_url'], 'http') === 0 ? $item['image_url'] : asset('product_image/' . $item['image_url']) }}" alt="{{ $item['product_name'] }}"
                                                        class="rounded bg-light"
                                                        style="width: 70px; height: 70px; object-fit: cover;">
                                                    <div class="ms-3">
                                                        <small class="text-muted text-uppercase"
                                                            style="font-size: 0.7rem;">{{ $item['brand_name'] }}</small>
                                                        <h6 class="mb-1 fw-bold">{{ $item['product_name'] }}</h6>
                                                        <small class="text-muted">Ukuran: {{ $item['size'] }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                            <td class="py-4">
                                                <span
                                                    class="badge bg-light text-dark border px-3 py-2 fs-6">{{ $item['quantity'] }}</span>
                                            </td>
                                            <td class="py-4 fw-bold">Rp
                                                {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</td>
                                            <td class="pe-4 py-4 text-end">
                                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-light text-danger rounded-circle"
                                                        title="Hapus"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Belanja -->
                <div class="col-lg-4">
                    <div class="card border-0 bg-light rounded-4 sticky-top" style="top: 100px;">
                        <div class="card-body p-4 p-md-5">
                            <h5 class="fw-bold mb-4 pb-3 border-bottom border-secondary-subtle">Ringkasan Belanja</h5>

                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Subtotal ({{ count($cart) }} item)</span>
                                <span class="fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>

                            <!-- Pajak & Pengiriman akan dihitung detail di checkout -->
                            <div class="d-flex justify-content-between mb-4 pb-4 border-bottom border-secondary-subtle">
                                <span class="text-muted small">Pajak & Pengiriman dihitung saat checkout</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-5">
                                <h5 class="fw-bold mb-0">Estimasi Total</h5>
                                <h4 class="fw-bold mb-0 text-dark">Rp {{ number_format($total, 0, ',', '.') }}</h4>
                            </div>

                            <a href="{{ route('checkout') }}"
                                class="btn btn-dark btn-lg w-100 rounded-pill py-3 fw-bold">Lanjutkan ke Checkout</a>
                            <a href="{{ route('shop') }}"
                                class="btn btn-outline-dark btn-lg w-100 rounded-pill py-3 fw-bold mt-3">Belanja Lagi</a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Tampilan jika keranjang kosong -->
            <div class="text-center py-5 my-5">
                <i class="fas fa-shopping-bag fa-4x text-muted mb-4 opacity-50"></i>
                <h4 class="fw-bold">Keranjang Anda masih kosong</h4>
                <p class="text-muted mb-4">Temukan aroma tanda tangan Anda di koleksi kami.</p>
                <a href="{{ route('shop') }}" class="btn btn-dark rounded-pill px-5 py-3">Mulai Belanja</a>
            </div>
        @endif
    </div>
@endsection
