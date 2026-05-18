@extends('admin.layout')
@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-light mb-1">Riwayat Pesanan</h2>
                <p class="text-muted small mb-0">Kelola transaksi, status pengiriman, dan lacak pesanan masuk Scentify.</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4 p-3 bg-light">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md-5">
                    <div class="input-group bg-white rounded-pill px-3 border">
                        <span class="input-group-text bg-transparent border-0 text-muted"><i
                                class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control border-0 bg-transparent"
                            placeholder="Cari No. Pesanan atau Nama..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select rounded-pill border bg-white" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Processing" {{ request('status') == 'Processing' ? 'selected' : '' }}>Processing
                        </option>
                        <option value="Shipped" {{ request('status') == 'Shipped' ? 'selected' : '' }}>Shipped (Dikirim)
                        </option>
                        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed
                            (Selesai)</option>
                        <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled (Batal)
                        </option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-dark rounded-pill w-100">Filter</button>
                    <a href="{{ route('admin.orders.index') }}"
                        class="btn btn-outline-secondary rounded-pill w-100">Reset</a>
                </div>
            </form>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4 py-3 fw-normal fs-7">NO. PESANAN</th>
                            <th class="py-3 fw-normal fs-7">PELANGGAN</th>
                            <th class="py-3 fw-normal fs-7">TANGGAL MASUK</th>
                            <th class="py-3 fw-normal fs-7">TOTAL HARGA</th>
                            <th class="py-3 fw-normal fs-7 text-center">STATUS</th>
                            <th class="pe-4 py-3 fw-normal fs-7 text-end">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td class="ps-4 fw-medium">#{{ $order->order_number }}</td>
                                <td>
                                    <div class="fw-normal text-dark">{{ $order->user->name ?? 'Pelanggan Scentify' }}</div>
                                    <small class="text-muted" style="font-size: 11px;">{{ $order->phone_number }}</small>
                                </td>
                                <td class="text-muted">{{ $order->created_at->format('d M Y, H:i') }} WIB</td>
                                <td class="fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    @if ($order->status === 'Pending')
                                        <span
                                            class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2 fw-medium fs-7">Pending</span>
                                    @elseif($order->status === 'Processing')
                                        <span
                                            class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 fw-medium fs-7">Processing</span>
                                    @elseif($order->status === 'Shipped')
                                        <span
                                            class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3 py-2 fw-medium fs-7">Shipped</span>
                                    @elseif($order->status === 'Completed')
                                        <span
                                            class="badge bg-success-subtle text-success rounded-pill px-3 py-2 fw-medium fs-7">Completed</span>
                                    @elseif($order->status === 'Cancelled')
                                        <span
                                            class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2 fw-medium fs-7">Cancelled</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                        class="btn btn-sm btn-outline-dark rounded-pill px-3">
                                        <i class="fas fa-eye me-1 small"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-receipt fa-3x mb-3 opacity-25"></i>
                                    <p class="mb-0">Belum ada riwayat transaksi masuk.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection