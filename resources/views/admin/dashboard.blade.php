@extends('admin.layout')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-8 fade-in">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Dashboard Manajemen Parfum</h1>
            <p class="text-sm text-slate-500 mt-1">Panel ringkasan untuk memantau stok, penjualan, pesanan, dan performa operasional.</p>
        </div>
        <a href="{{ route('admin.inventory') }}" class="inline-flex items-center gap-2 bg-slate-900 text-white px-5 py-2.5 rounded-xl font-medium hover:bg-slate-800 transition-colors shadow-lg shadow-slate-200">
            <i class="fas fa-boxes text-sm text-amber-400"></i> Lihat Inventori
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white rounded-[1.5rem] p-6 border border-slate-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-out z-0"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl">
                        <i class="fas fa-receipt"></i>
                    </div>
                    @if(($orderDiffPercentage ?? 0) >= 0)
                        <span class="text-xs font-bold text-emerald-500 bg-emerald-50 px-2 py-1 rounded-lg border border-emerald-100">
                            +{{ $orderDiffPercentage ?? 0 }}%
                        </span>
                    @else
                        <span class="text-xs font-bold text-rose-500 bg-rose-50 px-2 py-1 rounded-lg border border-rose-100">
                            {{ $orderDiffPercentage ?? 0 }}%
                        </span>
                    @endif
                </div>
                <div>
                    <h6 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Total Pesanan</h6>
                    <h2 class="text-3xl font-black text-slate-800">{{ $totalOrders ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[1.5rem] p-6 border border-slate-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-out z-0"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl">
                        <i class="fas fa-clock"></i>
                    </div>
                    <span class="text-[10px] font-bold text-amber-600 bg-amber-50 border border-amber-200 px-2.5 py-1 rounded-lg uppercase tracking-wider">Perlu Diproses</span>
                </div>
                <div>
                    <h6 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Pesanan Pending</h6>
                    <h2 class="text-3xl font-black text-slate-800">{{ $pendingOrders ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[1.5rem] p-6 border border-slate-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-out z-0"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    @if(($completedDiffPercentage ?? 0) >= 0)
                        <span class="text-xs font-bold text-emerald-500 bg-emerald-50 px-2 py-1 rounded-lg border border-emerald-100">
                            +{{ $completedDiffPercentage ?? 0 }}%
                        </span>
                    @else
                        <span class="text-xs font-bold text-rose-500 bg-rose-50 px-2 py-1 rounded-lg border border-rose-100">
                            {{ $completedDiffPercentage ?? 0 }}%
                        </span>
                    @endif
                </div>
                <div>
                    <h6 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Pesanan Selesai</h6>
                    <h2 class="text-3xl font-black text-slate-800">{{ $completedOrders ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[1.5rem] p-6 border border-slate-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-out z-0"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl">
                        <i class="fas fa-wallet"></i>
                    </div>
                    @if(($revenueDiffPercentage ?? 0) >= 0)
                        <span class="text-xs font-bold text-emerald-500 bg-emerald-50 px-2 py-1 rounded-lg border border-emerald-100">
                            +{{ $revenueDiffPercentage ?? 0 }}%
                        </span>
                    @else
                        <span class="text-xs font-bold text-rose-500 bg-rose-50 px-2 py-1 rounded-lg border border-rose-100">
                            {{ $revenueDiffPercentage ?? 0 }}%
                        </span>
                    @endif
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
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

    <div class="bg-white rounded-[1.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h5 class="text-lg font-bold text-slate-800">Daftar Pesanan Pending</h5>
                <p class="text-xs text-slate-500 mt-1">Pesanan masuk yang membutuhkan verifikasi atau konfirmasi tindakan segera.</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-amber-600 hover:text-amber-700 transition-colors flex items-center gap-1">
                Semua Riwayat Pesanan <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            @php
                $pendingCollection = $pendingOrdersList ?? collect();
            @endphp

            @if($pendingCollection->isNotEmpty())
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
                        @foreach($pendingCollection as $order)
                            <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-mono font-medium text-slate-700">#{{ $order->order_number }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-800">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center text-xs font-bold uppercase">
                                            {{ substr($order->user?->name ?? 'P', 0, 1) }}
                                        </div>
                                        {{ $order->user?->username ?? 'Pelanggan' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-amber-50 text-amber-600 border-amber-200">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-800">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-slate-500 text-xs">{{ $order->created_at->format('d M Y, H:i') }} WIB</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 text-slate-500 hover:bg-slate-900 hover:text-white transition-all shadow-sm" title="Lihat Detail & Update">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-10 text-center flex flex-col items-center justify-center">
                    <div class="w-16 h-16 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mb-4 text-2xl border border-emerald-100">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h6 class="text-slate-800 font-bold mb-1">Tidak Ada Pesanan Pending</h6>
                    <p class="text-sm text-slate-500">Bagus! Semua pesanan masuk telah Anda verifikasi atau proses.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
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
                                        <p class="text-xs text-slate-500 mt-0.5">Ukuran: <span class="font-semibold">{{ $variant->size ?? '-' }}ml</span></p>
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

        <div class="bg-white rounded-[1.5rem] border border-slate-100 shadow-sm overflow-hidden flex flex-col">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h5 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-star text-amber-400"></i> Produk Baru (15 Hari Terakhir)
                </h5>
                @if(isset($recentProducts) && $recentProducts->isNotEmpty())
                    <span class="text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-200 px-2.5 py-1 rounded-lg">
                        {{ $recentProducts->count() }} Produk
                    </span>
                @endif
            </div>

            <div class="p-0 flex-1 overflow-auto max-h-[350px]">
                @if(isset($recentProducts) && $recentProducts->isNotEmpty())
                    <ul class="divide-y divide-slate-100">
                        @foreach($recentProducts as $product)
                            <li class="p-4 sm:px-6 hover:bg-slate-50 transition-colors flex items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-slate-100 overflow-hidden shrink-0 border border-slate-200">
                                        <img src="{{ $product->image_url ? (strpos($product->image_url, 'http') === 0 ? $product->image_url : asset('product_image/' . $product->image_url)) : 'https://placehold.co/100x100?text=Pic' }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                                    </div>
                                    <div>
                                        <h6 class="font-bold text-sm text-slate-800 line-clamp-1">{{ $product->name }}</h6>
                                        <p class="text-[11px] font-mono text-amber-600 uppercase tracking-widest mt-0.5">
                                            {{ $product->brand?->name ?? 'No Brand' }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="text-right shrink-0">
                                    <span class="text-xs font-medium text-slate-500 bg-slate-100 px-2.5 py-1 rounded-md block text-center">
                                        {{ $product->category }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 block mt-1">
                                        <i class="far fa-clock mr-0.5"></i> {{ $product->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="h-full min-h-[250px] flex flex-col items-center justify-center p-6 text-center">
                        <div class="w-12 h-12 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 mb-3">
                            <i class="fas fa-box-open text-lg"></i>
                        </div>
                        <p class="text-sm font-semibold text-slate-600">Belum ada produk baru</p>
                        <p class="text-xs text-slate-400 mt-1">Tidak ada produk yang ditambahkan dalam 15 hari terakhir.</p>
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
        const isDarkTheme = document.documentElement.classList.contains('dark');
        const gridColor = isDarkTheme ? 'rgba(255, 255, 255, 0.05)' : 'rgba(15, 23, 42, 0.05)';
        const textLabelColor = isDarkTheme ? '#94a3b8' : '#64748b';

        // 1. Line Chart: Pendapatan Bulanan
        const ctxRevenue = document.getElementById('revenueTrendChart').getContext('2d');
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const revenueData = @json($revenueData ?? []);

        new Chart(ctxRevenue, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Pendapatan Kotor (Rp)',
                    data: revenueData,
                    borderColor: '#f59e0b',
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
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 12,
                        cornerRadius: 12,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) label += ': ';
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
                        grid: { display: false },
                        ticks: { color: textLabelColor, font: { family: 'Jost' } }
                    },
                    y: {
                        grid: { color: gridColor },
                        ticks: {
                            color: textLabelColor,
                            font: { family: 'Jost' },
                            callback: function(value) {
                                return 'Rp ' + (value / 1000000) + 'jt';
                            }
                        }
                    }
                }
            }
        });

        // 2. Doughnut Chart: Segmentasi Gender
        const ctxCategory = document.getElementById('scentCategoryChart').getContext('2d');
        const genderData = @json($genderData ?? []);

        new Chart(ctxCategory, {
            type: 'doughnut',
            data: {
                labels: ['Pria', 'Wanita', 'Unisex'],
                datasets: [{
                    data: genderData, 
                    backgroundColor: ['#f59e0b', '#fda4af', '#6366f1'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: { display: false },
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