@extends('admin.layout')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-8 fade-in">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Dashboard Manajemen Parfum</h1>
            <p class="text-sm text-slate-500 mt-1">Panel ringkasan untuk memantau stok, penjualan, pesanan, dan performa operasional.</p>
        </div>
        <a href="{{ route('admin.inventory') }}" class="inline-flex items-center gap-2 bg-slate-900 text-white px-5 py-2.5 rounded-xl font-medium hover:bg-slate-800 transition-colors shadow-lg shadow-slate-200">
            <i class="fas fa-boxes text-sm text-amber-400"></i> Lihat Inventori
        </a>
    </div>

    <!-- Metrics Grid (Row 1 - Orders) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Metric Card 1 -->
        <div class="bg-white rounded-[1.5rem] p-6 border border-slate-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-out z-0"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <span class="text-xs font-bold text-emerald-500 bg-emerald-50 px-2 py-1 rounded-lg">+12%</span>
                </div>
                <div>
                    <h6 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Total Pesanan</h6>
                    <h2 class="text-3xl font-black text-slate-800">{{ $totalOrders ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <!-- Metric Card 2 -->
        <div class="bg-white rounded-[1.5rem] p-6 border border-slate-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-out z-0"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl">
                        <i class="fas fa-clock"></i>
                    </div>
                    <span class="text-[10px] font-bold text-amber-600 bg-amber-50 border border-amber-200 px-2 py-1 rounded-lg">Perlu Diproses</span>
                </div>
                <div>
                    <h6 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Pesanan Pending</h6>
                    <h2 class="text-3xl font-black text-slate-800">{{ $pendingOrders ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <!-- Metric Card 3 -->
        <div class="bg-white rounded-[1.5rem] p-6 border border-slate-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-out z-0"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div>
                    <h6 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Pesanan Selesai</h6>
                    <h2 class="text-3xl font-black text-slate-800">{{ $completedOrders ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <!-- Metric Card 4 -->
        <div class="bg-white rounded-[1.5rem] p-6 border border-slate-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-out z-0"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <span class="text-xs font-bold text-emerald-500 bg-emerald-50 px-2 py-1 rounded-lg">+5%</span>
                </div>
                <div>
                    <h6 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Pendapatan Hari Ini</h6>
                    <h2 class="text-2xl font-black text-slate-800 truncate" title="Rp {{ number_format($todayRevenue ?? 0, 0, ',', '.') }}">
                        Rp {{ number_format($todayRevenue ?? 0, 0, ',', '.') }}
                    </h2>
                </div>
            </div>
        </div>

    </div>

    <!-- Metrics Grid (Row 2 - Revenue & Products) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="bg-white rounded-[1.5rem] p-6 border border-slate-100 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl shrink-0">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="overflow-hidden">
                <h6 class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Pendapatan Bulan Ini</h6>
                <h2 class="text-2xl font-black text-slate-800 truncate">Rp {{ number_format($monthlyRevenue ?? 0, 0, ',', '.') }}</h2>
            </div>
        </div>

        <div class="bg-white rounded-[1.5rem] p-6 border border-slate-100 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-2xl shrink-0">
                <i class="fas fa-coins"></i>
            </div>
            <div class="overflow-hidden">
                <h6 class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Pendapatan Total</h6>
                <h2 class="text-2xl font-black text-slate-800 truncate">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h2>
            </div>
        </div>

        <div class="bg-slate-900 rounded-[1.5rem] p-6 shadow-lg flex items-center gap-5 relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 opacity-10 text-9xl text-white pointer-events-none">
                <i class="fas fa-box"></i>
            </div>
            <div class="w-14 h-14 rounded-full bg-white/10 text-white flex items-center justify-center text-2xl shrink-0 z-10 backdrop-blur-sm">
                <i class="fas fa-spray-can"></i>
            </div>
            <div class="z-10">
                <h6 class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Produk Aktif Katalog</h6>
                <h2 class="text-3xl font-black text-white">{{ $totalProducts ?? 0 }}</h2>
            </div>
        </div>

    </div>

    <!-- NEW SECTION: Analisis Grafikal & Charts (Diletakkan di Tengah) -->
    <!-- Charts Area -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Line Chart -->
                    <div class="lg:col-span-2 bg-white rounded-[1.5rem] p-6 border border-slate-100 shadow-sm flex flex-col justify-between">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
                            <div>
                                <h5 class="font-bold text-slate-800 text-base">Analisis Grafik Pendapatan</h5>
                                <p class="text-xs text-slate-400 mt-0.5">Statistik pertumbuhan pendapatan kotor Scentify tahun ini.</p>
                            </div>
                            <span class="text-xs text-amber-500 font-bold bg-amber-50 px-3 py-1.5 rounded-xl border border-amber-200">
                                <i class="fas fa-sync-alt mr-1"></i> Real-time
                            </span>
                        </div>
                        <div class="w-full h-80 relative">
                            <canvas id="revenueTrendChart"></canvas>
                        </div>
                    </div>

                    <!-- Doughnut Chart -->
                    <div class="bg-white rounded-[1.5rem] p-6 border border-slate-100 shadow-sm flex flex-col justify-between">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
                            <div>
                                <h5 class="font-bold text-slate-800 text-base">Segmentasi Gender</h5>
                                <p class="text-xs text-slate-400 mt-0.5">Proporsi penjualan berdasarkan target parfum.</p>
                            </div>
                        </div>
                        <div class="w-full h-64 relative flex items-center justify-center">
                            <canvas id="scentCategoryChart"></canvas>
                        </div>
                        <div class="flex justify-around items-center text-xs font-semibold pt-4 border-t border-slate-50 mt-4">
                            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Pria</span>
                            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-rose-400"></span> Wanita</span>
                            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span> Unisex</span>
                        </div>
                    </div>
                </div>

    <!-- Main Tables Section -->
    <div class="bg-white rounded-[1.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h5 class="text-lg font-bold text-slate-800">Pesanan Mendatang / Dalam Proses</h5>
                <p class="text-xs text-slate-500 mt-1">Daftar pesanan terbaru yang masih perlu dikelola dan diproses.</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-amber-600 hover:text-amber-700 transition-colors flex items-center gap-1">
                Semua Pesanan <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            @if(isset($upcomingOrders) && $upcomingOrders->isNotEmpty())
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                            <th class="px-6 py-4 border-b border-slate-100">No. Pesanan</th>
                            <th class="px-6 py-4 border-b border-slate-100">Pelanggan</th>
                            <th class="px-6 py-4 border-b border-slate-100">Status</th>
                            <th class="px-6 py-4 border-b border-slate-100">Total</th>
                            <th class="px-6 py-4 border-b border-slate-100">Dibuat Pada</th>
                            <th class="px-6 py-4 border-b border-slate-100 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($upcomingOrders as $order)
                            <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-mono font-medium text-slate-700">#{{ $order->order_number }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-800">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-xs font-bold uppercase">
                                            {{ substr($order->user?->name ?? 'G', 0, 1) }}
                                        </div>
                                        {{ $order->user?->name ?? 'Guest' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClass = match($order->status) {
                                            'Pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                            'Paid' => 'bg-blue-100 text-blue-700 border-blue-200',
                                            'Completed' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            default => 'bg-slate-100 text-slate-700 border-slate-200'
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $statusClass }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-800">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-slate-500 text-xs">{{ $order->created_at->format('d M Y, H:i') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <button class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-800 transition-colors">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-10 text-center flex flex-col items-center justify-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 mb-4 text-2xl">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <h6 class="text-slate-800 font-bold mb-1">Tidak ada pesanan aktif</h6>
                    <p class="text-sm text-slate-500">Semua pesanan telah diproses atau toko sedang menunggu pesanan baru.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Side-by-side Tables Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Low Stock Table -->
        <div class="bg-white rounded-[1.5rem] border border-slate-100 shadow-sm overflow-hidden flex flex-col">
            <div class="p-6 border-b border-slate-100 bg-rose-50/30">
                <h5 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle text-rose-500"></i> Varian Stok Rendah
                </h5>
            </div>
            <div class="p-0 flex-1 overflow-auto max-h-[350px]">
                @if(isset($lowStockVariants) && $lowStockVariants->isNotEmpty())
                    <ul class="divide-y divide-slate-100">
                        @foreach($lowStockVariants as $variant)
                            <li class="p-4 sm:px-6 hover:bg-slate-50 transition-colors flex items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 shrink-0">
                                        <i class="fas fa-flask"></i>
                                    </div>
                                    <div>
                                        <h6 class="font-bold text-sm text-slate-800 line-clamp-1">{{ $variant->product->name ?? 'Produk tidak tersedia' }}</h6>
                                        <p class="text-xs text-slate-500 mt-0.5">Ukuran: <span class="font-semibold">{{ $variant->size ?? '-' }}</span></p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 rounded-lg bg-rose-100 text-rose-700 text-xs font-bold whitespace-nowrap">
                                    Sisa {{ $variant->stock }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="h-full min-h-[200px] flex flex-col items-center justify-center p-6 text-center">
                        <i class="fas fa-check-circle text-3xl text-emerald-400 mb-3"></i>
                        <p class="text-sm font-medium text-slate-600">Level Stok Aman</p>
                        <p class="text-xs text-slate-400 mt-1">Semua varian berada pada kuantitas aman.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Products Table -->
        <div class="bg-white rounded-[1.5rem] border border-slate-100 shadow-sm overflow-hidden flex flex-col">
            <div class="p-6 border-b border-slate-100">
                <h5 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-star text-amber-400"></i> Produk Baru Ditambahkan
                </h5>
            </div>
            <div class="p-0 flex-1 overflow-auto max-h-[350px]">
                @if(isset($recentProducts) && $recentProducts->isNotEmpty())
                    <ul class="divide-y divide-slate-100">
                        @foreach($recentProducts as $product)
                            <li class="p-4 sm:px-6 hover:bg-slate-50 transition-colors flex items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-slate-100 overflow-hidden shrink-0 border border-slate-200">
                                        <img src="{{ $product->image_url ? (strpos($product->image_url, 'http') === 0 ? $product->image_url : asset('product_image/' . $product->image_url)) : 'https://placehold.co/100x100?text=Pic' }}" class="w-full h-full object-cover" alt="Image">
                                    </div>
                                    <div>
                                        <h6 class="font-bold text-sm text-slate-800 line-clamp-1">{{ $product->name }}</h6>
                                        <p class="text-[11px] font-mono text-amber-600 uppercase tracking-widest mt-0.5">{{ $product->brand?->name ?? 'No Brand' }}</p>
                                    </div>
                                </div>
                                <span class="text-xs font-medium text-slate-500 bg-slate-100 px-2.5 py-1 rounded-md whitespace-nowrap">
                                    {{ $product->category }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="h-full min-h-[200px] flex flex-col items-center justify-center p-6 text-center">
                        <i class="fas fa-box-open text-3xl text-slate-300 mb-3"></i>
                        <p class="text-sm font-medium text-slate-600">Belum ada produk baru.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Konfigurasi warna Chart Scentify
        const isDarkTheme = document.documentElement.classList.contains('dark');
        const gridColor = isDarkTheme ? 'rgba(255, 255, 255, 0.05)' : 'rgba(15, 23, 42, 0.05)';
        const textLabelColor = isDarkTheme ? '#94a3b8' : '#64748b';

        // 1. Line Chart: Tren Penjualan & Pendapatan Bulanan
        const ctxRevenue = document.getElementById('revenueTrendChart').getContext('2d');
        
        // Mock data dummy penyesuaian tren pendapatan Scentify
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const revenueData = [12000000, 19000000, 15000000, 25000000, 22000000, 30000000, 38000000, 35000000, 42000000, 48000000, 55000000, 72000000];

        new Chart(ctxRevenue, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Pendapatan Kotor (Rp)',
                    data: revenueData,
                    borderColor: '#f59e0b', // Amber-500
                    backgroundColor: 'rgba(245, 158, 11, 0.05)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#f59e0b',
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 12,
                        cornerRadius: 12,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: textLabelColor,
                            font: {
                                family: 'Jost'
                            }
                        }
                    },
                    y: {
                        grid: {
                            color: gridColor
                        },
                        ticks: {
                            color: textLabelColor,
                            font: {
                                family: 'Jost'
                            },
                            callback: function(value) {
                                return 'Rp ' + (value / 1000000) + 'jt';
                            }
                        }
                    }
                }
            }
        });

        // 2. Doughnut Chart: Segmentasi Gender Kategori
        const ctxCategory = document.getElementById('scentCategoryChart').getContext('2d');
        
        new Chart(ctxCategory, {
            type: 'doughnut',
            data: {
                labels: ['Pria', 'Wanita', 'Unisex'],
                datasets: [{
                    data: [35, 45, 20], // Proporsi persentase segmen aroma
                    backgroundColor: [
                        '#f59e0b', // Amber
                        '#fda4af', // Rose
                        '#6366f1'  // Indigo
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        padding: 12,
                        cornerRadius: 12,
                        callbacks: {
                            label: function(context) {
                                return ` ${context.label}: ${context.parsed}% Proporsi`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection