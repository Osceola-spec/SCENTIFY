@extends('admin.layout')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="row gy-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1">Dashboard Manajemen Parfum</h1>
                    <p class="text-muted mb-0">Panel untuk memantau stok, penjualan, pesanan, dan performa operasional tim admin.</p>
                </div>
                <div>
                    <a href="{{ route('admin.inventory') }}" class="btn btn-primary">Lihat Inventori</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="text-uppercase text-secondary small mb-1">Total Pesanan</h6>
                        <h2 class="mb-0">{{ $totalOrders ?? 0 }}</h2>
                    </div>
                    <i class="fas fa-receipt fa-2x text-info"></i>
                </div>
                <p class="text-muted mb-0">Total semua pesanan yang pernah masuk.</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="text-uppercase text-secondary small mb-1">Pesanan Pending</h6>
                        <h2 class="mb-0">{{ $pendingOrders ?? 0 }}</h2>
                    </div>
                    <i class="fas fa-clock fa-2x text-warning"></i>
                </div>
                <p class="text-muted mb-0">Pesanan yang perlu diproses oleh tim.</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="text-uppercase text-secondary small mb-1">Pesanan Selesai</h6>
                        <h2 class="mb-0">{{ $completedOrders ?? 0 }}</h2>
                    </div>
                    <i class="fas fa-check-circle fa-2x text-success"></i>
                </div>
                <p class="text-muted mb-0">Pesanan yang sudah berhasil dikirim dan selesai.</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="text-uppercase text-secondary small mb-1">Pendapatan Hari Ini</h6>
                        <h2 class="mb-0">Rp {{ number_format($todayRevenue ?? 0, 0, ',', '.') }}</h2>
                    </div>
                    <i class="fas fa-wallet fa-2x text-primary"></i>
                </div>
                <p class="text-muted mb-0">Total omzet order yang sukses hari ini.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="text-uppercase text-secondary small mb-1">Pendapatan Bulan Ini</h6>
                        <h2 class="mb-0">Rp {{ number_format($monthlyRevenue ?? 0, 0, ',', '.') }}</h2>
                    </div>
                    <i class="fas fa-calendar-alt fa-2x text-success"></i>
                </div>
                <p class="text-muted mb-0">Total pendapatan dari pesanan sukses bulan ini.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="text-uppercase text-secondary small mb-1">Pendapatan Total</h6>
                        <h2 class="mb-0">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h2>
                    </div>
                    <i class="fas fa-coins fa-2x text-warning"></i>
                </div>
                <p class="text-muted mb-0">Total pendapatan dari semua pesanan yang sukses.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="text-uppercase text-secondary small mb-1">Produk Aktif</h6>
                        <h2 class="mb-0">{{ $totalProducts ?? 0 }}</h2>
                    </div>
                    <i class="fas fa-box fa-2x text-secondary"></i>
                </div>
                <p class="text-muted mb-0">Jumlah produk yang tersedia di inventori saat ini.</p>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="mb-1">Pesanan Mendatang / Dalam Proses</h5>
                        <p class="text-muted mb-0">Daftar pesanan yang masih perlu dikelola oleh tim.</p>
                    </div>
                    <a href="#upcoming-orders" class="text-decoration-none">Lihat detail</a>
                </div>

                @if(isset($upcomingOrders) && $upcomingOrders->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No. Pesanan</th>
                                    <th>Pelanggan</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Dibuat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcomingOrders as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->user?->name ?? 'Guest' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $order->status === 'Pending' ? 'warning' : ($order->status === 'Paid' ? 'primary' : 'success') }} text-dark">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td class="text-muted">{{ $order->created_at->format('d M Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">Tidak ada pesanan yang sedang diproses saat ini.</p>
                @endif
            </div>
        </div>

        <div class="col-12">
            <div class="row gx-4 gy-4">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                        <h5 class="mb-3">Varian Dengan Stok Rendah</h5>
                        @if(isset($lowStockVariants) && $lowStockVariants->isNotEmpty())
                            <div class="list-group list-group-flush">
                                @foreach($lowStockVariants as $variant)
                                    <div class="list-group-item px-0 py-3 border-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">{{ $variant->product->name ?? 'Produk tidak tersedia' }}</h6>
                                                <small class="text-muted">Varian: {{ $variant->size ?? '-' }}</small>
                                            </div>
                                            <span class="badge bg-danger rounded-pill">Stok {{ $variant->stock }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">Semua varian berada pada level stok aman.</p>
                        @endif
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                        <h5 class="mb-3">Produk Terbaru</h5>
                        @if(isset($recentProducts) && $recentProducts->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-borderless align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>Brand</th>
                                            <th class="text-end">Kategori</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentProducts as $product)
                                            <tr>
                                                <td>{{ $product->name }}</td>
                                                <td>{{ $product->brand?->name ?? '-' }}</td>
                                                <td class="text-end text-muted">{{ $product->category }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">Belum ada produk baru yang ditambahkan.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
