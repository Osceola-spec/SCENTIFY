@extends('admin.layout')
@section('content')
    <div class="container py-5 mt-4">
        <div class="mb-4">
            <a href="{{ route('admin.orders.index') }}" class="text-decoration-none text-muted small">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Pesanan
            </a>
            <h2 class="fw-light mt-2">Detail Pesanan #{{ $order->order_number }}</h2>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <h5 class="fw-bold mb-3">Item yang Dibeli</h5>
                    <hr class="opacity-10 mt-0">

                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead>
                                <tr class="text-muted border-bottom" style="font-size: 12px;">
                                    <th class="pb-2">PARFUM</th>
                                    <th class="pb-2 text-center">UKURAN</th>
                                    <th class="pb-2 text-center">JUMLAH</th>
                                    <th class="pb-2 text-end">HARGA SATUAN</th>
                                    <th class="pb-2 text-end">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                    <tr class="border-bottom-dashed">
                                        <td class="py-3">
                                            <div class="fw-medium text-dark">
                                                {{ $item->variant->product->name ?? 'Produk Dihapus' }}</div>
                                            <small class="text-muted text-uppercase"
                                                style="font-size: 11px;">{{ $item->variant->product->brand->name ?? '' }}</small>
                                        </td>
                                        <td class="text-center py-3 text-muted">{{ $item->variant->size }}</td>
                                        <td class="text-center py-3 font-monospace">{{ $item->quantity }}x</td>
                                        {{-- 1. DIUBAH KE price_at_purchase SESUAI CONTROLLER --}}
                                        <td class="text-end py-3">Rp {{ number_format($item->price_at_purchase, 0, ',', '.') }}</td>
                                        <td class="text-end py-3 fw-bold">Rp
                                            {{ number_format($item->price_at_purchase * $item->quantity, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="4" class="text-end fw-medium pt-3 text-muted">Grand Total:</td>
                                    {{-- 2. DIUBAH KE total_amount SESUAI DATA CHECKOUT --}}
                                    <td class="text-end fw-bold pt-3 fs-5 text-success">Rp
                                        {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h5 class="fw-bold mb-3"><i class="fas fa-map-marker-alt text-danger me-2 small"></i>Tujuan Pengiriman
                    </h5>
                    <hr class="opacity-10 mt-0">
                    {{-- 3. DIUBAH: Mengambil nama dari string parsing alamat atau relasi user --}}
                    <p class="mb-1 fw-bold">{{ $order->user->name ?? 'Pelanggan Scentify' }}</p>
                    <p class="text-muted small mb-3"><i class="fas fa-phone me-1 opacity-50"></i>
                        {{ $order->phone_number ?? 'No. Telp Tersimpan di Alamat' }}</p>
                    <div class="bg-light p-3 rounded-3 text-dark small" style="line-height: 1.6;">
                        {{ $order->shipping_address }}
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-dark text-white sticky-top" style="top: 110px;">
                    <h5 class="fw-bold mb-3">Status Operasional</h5>
                    <hr class="border-secondary mb-4">

                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label text-muted small text-uppercase">Tahapan Logistik</label>
                            <select name="status"
                                class="form-select bg-secondary-subtle border-0 rounded-3 py-2 fw-medium text-dark">
                                <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending (Belum Direspon)</option>
                                <option value="Processing" {{ $order->status == 'Processing' ? 'selected' : '' }}>Processing (Sedang Dikemas)</option>
                                <option value="Shipped" {{ $order->status == 'Shipped' ? 'selected' : '' }}>Shipped (Diserahkan ke Kurir)</option>
                                <option value="Completed" {{ $order->status == 'Completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                                <option value="Cancelled" {{ $order->status == 'Cancelled' ? 'selected' : '' }}>Cancelled (Batalkan Pesanan)</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small text-uppercase">Nomor Resi Pengiriman</label>
                            <input type="text" name="tracking_number"
                                class="form-control bg-secondary-subtle border-0 rounded-3 py-2 text-dark font-monospace"
                                placeholder="Contoh: JNE123456789" value="{{ $order->tracking_number }}">
                            <small class="text-muted d-block mt-1" style="font-size: 11px;">Isi jika paket sudah diserahkan ke jasa ekspedisi.</small>
                        </div>

                        <button type="submit" class="btn btn-warning w-100 rounded-pill py-2 fw-bold text-dark">
                            <i class="fas fa-save me-1 small"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .border-bottom-dashed {
            border-bottom: 1px dashed #dee2e6 !important;
        }
    </style>
@endsection