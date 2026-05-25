@extends('base.base')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-32 relative z-10">
    
    <!-- Ambient Glow Orbs -->
    <div class="absolute top-[10%] left-[5%] w-[250px] h-[250px] bg-amber-500/10 dark:bg-amber-500/5 rounded-full animate-float pointer-events-none filter blur-[80px] -z-10"></div>
    <div class="absolute bottom-[20%] right-[5%] w-[300px] h-[300px] bg-purple-500/10 dark:bg-purple-900/5 rounded-full animate-float pointer-events-none filter blur-[80px] -z-10" style="animation-delay: 2s;"></div>

    <!-- Breadcrumb -->
    <nav class="mb-8 reveal">
        <ol class="flex items-center space-x-2 text-xs font-mono uppercase tracking-wider text-slate-400 dark:text-zinc-500">
            <li><a href="{{ route('home') }}" class="hover:text-amber-500 transition-colors">Home</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-amber-500 font-semibold">My Orders</li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10 pb-6 border-b border-slate-200 dark:border-white/5 reveal">
        <div>
            <span class="text-[10px] sm:text-xs font-mono text-amber-600 dark:text-amber-400 uppercase tracking-widest font-semibold">Riwayat Transaksi</span>
            <h1 class="text-3xl md:text-5xl font-serif mt-2 text-slate-950 dark:text-white">Pesanan <span class="italic text-amber-500 font-normal">Saya</span></h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-zinc-400 mt-3 max-w-lg leading-relaxed">
                Pantau status pengiriman, kelola transaksi, dan lihat kembali koleksi parfum Scentify yang pernah Anda pesan.
            </p>
        </div>
        
        <!-- Filter Cepat (Opsional Visual) -->
        <div class="flex gap-2">
            <a href="?status=all" class="px-4 py-2 rounded-full text-[10px] sm:text-xs font-bold uppercase tracking-wider transition-all {{ !request('status') || request('status') == 'all' ? 'bg-slate-900 text-white dark:bg-amber-400 dark:text-black' : 'bg-slate-100 dark:bg-zinc-800 text-slate-500 dark:text-zinc-400 hover:bg-slate-200 dark:hover:bg-zinc-700' }}">Semua</a>
            <a href="?status=active" class="px-4 py-2 rounded-full text-[10px] sm:text-xs font-bold uppercase tracking-wider transition-all {{ request('status') == 'active' ? 'bg-slate-900 text-white dark:bg-amber-400 dark:text-black' : 'bg-slate-100 dark:bg-zinc-800 text-slate-500 dark:text-zinc-400 hover:bg-slate-200 dark:hover:bg-zinc-700' }}">Berlangsung</a>
        </div>
    </div>

    <!-- Daftar Pesanan -->
    <div class="space-y-6 sm:space-y-8">
        @forelse($orders ?? [] as $order)
            <div class="glass-card bg-white/60 dark:bg-darkcard/60 rounded-[1.5rem] sm:rounded-[2rem] border border-slate-200 dark:border-white/5 shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden reveal group relative">
                
                <!-- Hover Glow Effect -->
                <div class="absolute inset-0 bg-gradient-to-r from-amber-500/0 via-amber-500/0 to-amber-500/0 group-hover:from-amber-500/5 group-hover:via-transparent group-hover:to-transparent transition-all duration-500 pointer-events-none"></div>

                <!-- Card Header -->
                <div class="px-5 sm:px-8 py-4 sm:py-5 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-zinc-900/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex flex-wrap items-center gap-4 sm:gap-6">
                        <div>
                            <p class="text-[10px] font-mono uppercase text-slate-400 dark:text-zinc-500 tracking-wider mb-0.5">No. Pesanan</p>
                            <p class="text-sm sm:text-base font-bold text-slate-900 dark:text-white">#{{ $order->order_number }}</p>
                        </div>
                        <div class="hidden sm:block w-px h-8 bg-slate-200 dark:bg-white/10"></div>
                        <div>
                            <p class="text-[10px] font-mono uppercase text-slate-400 dark:text-zinc-500 tracking-wider mb-0.5">Tanggal Pembelian</p>
                            <p class="text-xs sm:text-sm font-medium text-slate-700 dark:text-zinc-300 flex items-center gap-1.5">
                                <i class="far fa-calendar-alt text-amber-500"></i> {{ $order->created_at->format('d M Y') }}
                            </p>
                        </div>
                    </div>

                    <!-- Status Badge -->
                    @php
                        $statusClass = match($order->status) {
                            'Pending' => 'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20',
                            'Processing' => 'bg-blue-100 text-blue-700 border-blue-200 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20',
                            'Shipped' => 'bg-indigo-100 text-indigo-700 border-indigo-200 dark:bg-indigo-500/10 dark:text-indigo-400 dark:border-indigo-500/20',
                            'Completed' => 'bg-emerald-100 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20',
                            'Cancelled' => 'bg-rose-100 text-rose-700 border-rose-200 dark:bg-rose-500/10 dark:text-rose-400 dark:border-rose-500/20',
                            default => 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-zinc-800 dark:text-zinc-400 dark:border-white/10'
                        };
                        
                        $statusIcon = match($order->status) {
                            'Pending' => 'fa-clock',
                            'Processing' => 'fa-box-open',
                            'Shipped' => 'fa-truck-fast',
                            'Completed' => 'fa-check-circle',
                            'Cancelled' => 'fa-times-circle',
                            default => 'fa-circle'
                        };
                    @endphp
                    <div class="flex items-center">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] sm:text-xs font-bold uppercase tracking-wider border {{ $statusClass }}">
                            <i class="fas {{ $statusIcon }}"></i> {{ $order->status }}
                        </span>
                    </div>
                </div>

                <!-- Card Body (Items) -->
                <div class="p-5 sm:p-8">
                    <div class="space-y-4">
                        <!-- Tampilkan max 2 item pertama agar card tidak terlalu panjang -->
                        @foreach($order->items->take(2) as $item)
                            @php
                                $variant     = $item->variant;
                                $product     = $variant?->product;
                                $productName = $product?->name ?? null;
                                $imgRaw      = $product?->image_url ?? null;

                                $imgSrc = $imgRaw
                                    ? asset('product_image/' . $imgRaw)  // ← sesuaikan folder ini
                                    : null;
                            @endphp

                            <div class="flex items-center gap-4 sm:gap-6">
                                {{-- Gambar --}}
                                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl sm:rounded-2xl overflow-hidden bg-slate-100 dark:bg-zinc-800 shrink-0 border border-slate-200 dark:border-white/5 flex items-center justify-center">
                                    @if($imgSrc)
                                        <img src="{{ $imgSrc }}"
                                            alt="{{ $productName }}"
                                            class="w-full h-full object-cover"
                                            onerror="this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center text-slate-300 dark:text-zinc-600\'><i class=\'fas fa-spray-can text-2xl\'></i></div>'">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-zinc-600">
                                            <i class="fas fa-spray-can text-2xl"></i>
                                        </div>
                                    @endif
                                </div>

                                {{-- Info Produk --}}
                                <div class="flex-grow min-w-0">
                                    @if($productName)
                                        <h4 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white line-clamp-1">
                                            {{ $productName }}
                                        </h4>
                                        <p class="text-[10px] sm:text-xs text-slate-500 dark:text-zinc-400 mt-0.5 sm:mt-1">
                                            Ukuran: <span class="font-semibold">{{ $variant->size ?? '-' }}</span>
                                            | Qty: <span class="font-semibold">{{ $item->quantity }}x</span>
                                        </p>
                                    @else
                                        {{-- Data lama tanpa referensi produk --}}
                                        <h4 class="text-sm font-medium text-slate-400 dark:text-zinc-500 italic line-clamp-1">
                                            Produk tidak tersedia
                                        </h4>
                                        <p class="text-[10px] sm:text-xs text-slate-400 dark:text-zinc-600 mt-0.5 sm:mt-1">
                                            Qty: <span class="font-semibold">{{ $item->quantity }}x</span>
                                        </p>
                                    @endif

                                    <p class="text-xs sm:text-sm font-semibold text-slate-700 dark:text-zinc-300 mt-1 sm:mt-2">
                                        Rp {{ number_format($item->price_at_purchase, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach

                        @if($order->items->count() > 2)
                            <div class="text-[10px] sm:text-xs font-medium text-slate-400 dark:text-zinc-500 pt-2 flex items-center gap-2">
                                <span class="flex-grow h-px bg-slate-200 dark:bg-white/5"></span>
                                <span>+ {{ $order->items->count() - 2 }} produk lainnya</span>
                                <span class="flex-grow h-px bg-slate-200 dark:bg-white/5"></span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Card Footer (Total & Actions) -->
                {{-- Ganti bagian Card Footer --}}
            <div class="px-5 sm:px-8 py-4 sm:py-5 border-t border-slate-100 dark:border-white/5 bg-slate-50/30 dark:bg-zinc-900/10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <p class="text-[10px] font-mono uppercase text-slate-400 dark:text-zinc-500 tracking-wider mb-0.5">Total Pembayaran</p>
                    <p class="text-lg sm:text-xl font-black text-amber-600 dark:text-amber-400">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                </div>

                <div class="flex items-center gap-2 sm:gap-3 w-full sm:w-auto">

                    {{-- Badge status review (hanya untuk Completed) --}}
                    @if ($order->status === 'Completed')
                        @php
                            $totalItems    = $order->items->count();
                            $reviewedItems = $order->items->filter(fn($i) => $i->review)->count();
                        @endphp
                        @if ($reviewedItems === $totalItems && $totalItems > 0)
                            <span class="hidden sm:inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-[10px] font-bold">
                                <i class="fas fa-star"></i> Sudah Diulas
                            </span>
                        @else
                            <span class="hidden sm:inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 text-amber-600 dark:text-amber-400 text-[10px] font-bold">
                                <i class="far fa-star"></i> {{ $reviewedItems }}/{{ $totalItems }} Diulas
                            </span>
                        @endif
                    @endif

                    @if ($order->status === 'Pending')
                        <button onclick="payNow('{{ $order->order_number }}')"
                                class="flex-1 sm:flex-none px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-black text-[11px] sm:text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-lg shadow-amber-500/20 active:scale-95 text-center">
                            Bayar Sekarang
                        </button>
                    @elseif ($order->status === 'Shipped' && $order->tracking_number)
                        <button onclick="trackOrder('{{ $order->tracking_number }}')"
                                class="flex-1 sm:flex-none px-5 py-2.5 bg-indigo-500 hover:bg-indigo-600 text-white text-[11px] sm:text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-lg shadow-indigo-500/20 active:scale-95 text-center">
                            Lacak Resi
                        </button>
                    @endif

                    <a href="{{ route('orders.show', $order->id) }}"
                    class="flex-1 sm:flex-none px-5 py-2.5 bg-white dark:bg-zinc-800 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-zinc-700 hover:text-amber-500 dark:hover:text-amber-400 text-[11px] sm:text-xs font-bold uppercase tracking-wider rounded-xl transition-all text-center">
                        Detail
                    </a>
                </div>
            </div>

            </div>
        @empty
            <!-- Tampilan Kosong (Empty State) -->
            <div class="text-center py-20 reveal">
                <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-full bg-slate-100 dark:bg-darkcard border border-slate-200 dark:border-white/5 flex items-center justify-center mx-auto mb-6 text-slate-300 dark:text-zinc-600 shadow-inner relative">
                    <i class="fas fa-box-open text-4xl sm:text-5xl"></i>
                    <!-- Dekorasi Sparkle -->
                    <i class="fas fa-star text-amber-400 absolute top-4 right-4 text-xs animate-pulse"></i>
                </div>
                <h3 class="font-serif text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">Belum Ada Riwayat Pesanan</h3>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-zinc-400 mt-3 max-w-md mx-auto leading-relaxed">
                    Koleksi riwayat pesanan Anda masih kosong. Temukan aroma khas Anda dan mulai perjalanan memori Anda bersama Scentify.
                </p>
                <a href="{{ route('shop') }}" class="inline-block mt-8 px-8 py-4 font-semibold text-xs tracking-widest uppercase bg-slate-900 dark:bg-amber-400 text-white dark:text-black rounded-xl hover:bg-slate-800 dark:hover:bg-amber-300 hover:scale-105 active:scale-95 transition-all shadow-xl shadow-slate-900/10 dark:shadow-amber-500/15">
                    <i class="fas fa-compass mr-2"></i> Jelajahi Koleksi
                </a>
            </div>
        @endforelse

        <!-- Custom Pagination -->
        @if(isset($orders) && method_exists($orders, 'links'))
            <div class="mt-12 pt-8 border-t border-slate-200 dark:border-white/5 flex justify-center custom-pagination reveal">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>

<!-- Style khusus pagination agar selaras dengan tema (Disesuaikan dari cart/shop) -->
<style>
    .custom-pagination .page-link {
        color: inherit;
        border: none;
        background: transparent;
        border-radius: 0.5rem;
    }
    .custom-pagination .page-item.active .page-link {
        background-color: #f59e0b !important; /* Amber-500 */
        color: #000 !important;
        border-radius: 9999px;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.2);
    }
    .dark .custom-pagination .page-link {
        color: #a1a1aa;
    }
    .custom-pagination .page-link:hover {
        background-color: rgba(245, 158, 11, 0.1) !important;
        color: #f59e0b !important;
        border-radius: 9999px;
    }
</style>

<!-- Interaksi Kustom Halaman Order -->
<script>
    function payNow(orderNumber) {
        Swal.fire({
            title: 'Lanjutkan Pembayaran?',
            text: `Anda akan diarahkan ke gerbang pembayaran untuk pesanan #${orderNumber}.`,
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Bayar Sekarang',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: document.documentElement.classList.contains('dark') ? 'dark-swal rounded-[1.5rem]' : 'rounded-[1.5rem]',
                confirmButton: 'rounded-xl px-5 py-2.5 font-bold',
                cancelButton: 'rounded-xl px-5 py-2.5 font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Di Laravel sesungguhnya, arahkan ke rute pembayaran:
                // window.location.href = `/payment/${orderNumber}`;
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Membuka gerbang pembayaran Scentify Payment...',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 2000,
                    customClass: { popup: document.documentElement.classList.contains('dark') ? 'dark-swal rounded-[1.5rem]' : 'rounded-[1.5rem]' }
                });
            }
        });
    }

    function trackOrder(resi) {
        Swal.fire({
            title: 'Lacak Pengiriman',
            html: `
                <div class="text-left mt-4 mb-2">
                    <p class="text-sm text-slate-500 dark:text-zinc-400 mb-1">Nomor Resi Anda:</p>
                    <div class="bg-slate-100 dark:bg-zinc-800 p-3 rounded-xl font-mono text-lg font-bold text-center tracking-widest text-slate-900 dark:text-white border border-slate-200 dark:border-white/10 select-all">
                        ${resi}
                    </div>
                </div>
            `,
            icon: 'truck',
            iconHtml: '<i class="fas fa-shipping-fast text-indigo-500"></i>',
            confirmButtonColor: '#6366f1',
            confirmButtonText: '<i class="fas fa-copy mr-2"></i> Salin Resi',
            customClass: {
                popup: document.documentElement.classList.contains('dark') ? 'dark-swal rounded-[1.5rem]' : 'rounded-[1.5rem]',
                confirmButton: 'rounded-xl px-5 py-2.5 font-bold w-full'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                navigator.clipboard.writeText(resi);
                Swal.fire({
                    toast: true,
                    position: 'bottom-end',
                    icon: 'success',
                    title: 'Resi disalin ke clipboard!',
                    showConfirmButton: false,
                    timer: 2500,
                    customClass: { popup: document.documentElement.classList.contains('dark') ? 'dark-swal rounded-xl' : 'rounded-xl' }
                });
            }
        });
    }
</script>
@endsection