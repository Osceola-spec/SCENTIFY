@extends('base.base')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-32 relative z-10">
    
    <!-- Ambient Glow Orbs -->
    <div class="absolute top-[10%] left-[5%] w-[250px] h-[250px] bg-amber-500/10 dark:bg-amber-500/5 rounded-full animate-float pointer-events-none filter blur-[80px] -z-10"></div>
    <div class="absolute bottom-[20%] right-[5%] w-[300px] h-[300px] bg-purple-500/10 dark:bg-purple-900/5 rounded-full animate-float pointer-events-none filter blur-[80px] -z-10" style="animation-delay: 2s;"></div>

    <!-- Breadcrumb & Back -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 reveal">
        <nav>
            <ol class="flex items-center space-x-2 text-xs font-mono uppercase tracking-wider text-slate-400 dark:text-zinc-500">
                <li><a href="{{ route('home') }}" class="hover:text-amber-500 transition-colors">Home</a></li>
                <li><span class="mx-2">/</span></li>
                <li><a href="{{ route('orders.index') }}" class="hover:text-amber-500 transition-colors">My Orders</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-amber-500 font-semibold">#{{ $order->order_number }}</li>
            </ol>
        </nav>
        <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-1.5 text-xs font-mono uppercase tracking-wider text-slate-500 hover:text-amber-500 transition-colors">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesanan
        </a>
    </div>

    <!-- Header Section -->
    <div class="mb-10 reveal">
        <span class="text-[10px] sm:text-xs font-mono text-amber-600 dark:text-amber-400 uppercase tracking-widest font-semibold block">Rincian Transaksi</span>
        <h1 class="text-3xl sm:text-4xl font-serif mt-2 text-slate-950 dark:text-white">Detail Pesanan <span class="italic text-amber-500 font-normal">#{{ $order->order_number }}</span></h1>
        <p class="text-xs text-slate-400 dark:text-zinc-500 mt-2 font-mono">Dibuat pada: {{ $order->created_at->format('d M Y, H:i') }} WIB</p>
    </div>

    <!-- STATUS TRACKER / TIMELINE (Interactive) -->
    <div class="glass-card bg-white/60 dark:bg-darkcard/60 rounded-3xl border border-slate-200 dark:border-white/5 p-6 sm:p-8 mb-8 shadow-xl reveal">
        <h3 class="text-sm font-mono uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-6 font-semibold">Status Pengiriman</h3>
        
        @if($order->status === 'Cancelled')
            <!-- Tampilan khusus jika dibatalkan -->
            <div class="flex items-center gap-4 p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl text-rose-600 dark:text-rose-400">
                <div class="w-10 h-10 rounded-full bg-rose-500/20 flex items-center justify-center shrink-0">
                    <i class="fas fa-times-circle text-lg"></i>
                </div>
                <div>
                    <h5 class="font-bold text-sm">Pesanan Dibatalkan</h5>
                    <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">Pesanan ini telah dibatalkan oleh sistem atau atas permintaan Anda.</p>
                </div>
            </div>
        @else
            <!-- Progress Line Tracker -->
            @php
                $steps = ['Pending', 'Processing', 'Shipped', 'Completed'];
                $currentStepIndex = array_search($order->status, $steps);
            @endphp
            <div class="relative flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 sm:gap-4">
                <!-- Garis Penghubung (Desktop Only) -->
                <div class="absolute left-6 top-1/2 -translate-y-1/2 w-4/5 h-[2px] bg-slate-200 dark:bg-zinc-800 -z-10 hidden sm:block"></div>
                <div class="absolute left-6 top-1/2 -translate-y-1/2 h-[2px] bg-amber-500 -z-10 hidden sm:block transition-all duration-500" 
                     style="width: {{ $currentStepIndex > 0 ? ($currentStepIndex / 3) * 80 : 0 }}%;"></div>

                <!-- Step 1: Pending -->
                <div class="flex sm:flex-col items-center gap-3 sm:gap-2 text-left sm:text-center flex-1">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold border-2 z-10 transition-colors duration-300
                        {{ $currentStepIndex >= 0 ? 'bg-amber-500 border-amber-500 text-black' : 'bg-white dark:bg-zinc-900 border-slate-200 dark:border-zinc-800 text-slate-400' }}">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold {{ $currentStepIndex >= 0 ? 'text-slate-900 dark:text-white' : 'text-slate-400' }}">Pending</p>
                        <p class="text-[10px] text-slate-400 dark:text-zinc-500 hidden sm:block mt-0.5">Menunggu Pembayaran</p>
                    </div>
                </div>

                <!-- Step 2: Processing -->
                <div class="flex sm:flex-col items-center gap-3 sm:gap-2 text-left sm:text-center flex-1">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold border-2 z-10 transition-colors duration-300
                        {{ $currentStepIndex >= 1 ? 'bg-amber-500 border-amber-500 text-black' : 'bg-white dark:bg-zinc-900 border-slate-200 dark:border-zinc-800 text-slate-400' }}">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold {{ $currentStepIndex >= 1 ? 'text-slate-900 dark:text-white' : 'text-slate-400' }}">Processing</p>
                        <p class="text-[10px] text-slate-400 dark:text-zinc-500 hidden sm:block mt-0.5">Sedang Dikemas</p>
                    </div>
                </div>

                <!-- Step 3: Shipped -->
                <div class="flex sm:flex-col items-center gap-3 sm:gap-2 text-left sm:text-center flex-1">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold border-2 z-10 transition-colors duration-300
                        {{ $currentStepIndex >= 2 ? 'bg-amber-500 border-amber-500 text-black' : 'bg-white dark:bg-zinc-900 border-slate-200 dark:border-zinc-800 text-slate-400' }}">
                        <i class="fas fa-truck-fast"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold {{ $currentStepIndex >= 2 ? 'text-slate-900 dark:text-white' : 'text-slate-400' }}">Shipped</p>
                        <p class="text-[10px] text-slate-400 dark:text-zinc-500 hidden sm:block mt-0.5">Dalam Pengiriman</p>
                    </div>
                </div>

                <!-- Step 4: Completed -->
                <div class="flex sm:flex-col items-center gap-3 sm:gap-2 text-left sm:text-center flex-1">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold border-2 z-10 transition-colors duration-300
                        {{ $currentStepIndex >= 3 ? 'bg-emerald-500 border-emerald-500 text-white' : 'bg-white dark:bg-zinc-900 border-slate-200 dark:border-zinc-800 text-slate-400' }}">
                        <i class="fas fa-check"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold {{ $currentStepIndex >= 3 ? 'text-emerald-500' : 'text-slate-400' }}">Completed</p>
                        <p class="text-[10px] text-slate-400 dark:text-zinc-500 hidden sm:block mt-0.5">Paket Diterima</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- MAIN TWO-COLUMN CONTENT GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- LEFT COLUMN: Items purchased & Shipping Address (Col 8) -->
        <div class="lg:col-span-8 space-y-6 reveal">
            <!-- Card: Item Yang Dibeli -->
            <div class="glass-card bg-white/60 dark:bg-darkcard/60 rounded-3xl border border-slate-200 dark:border-white/5 p-6 sm:p-8 shadow-xl">
                <h3 class="text-lg font-serif font-bold text-slate-950 dark:text-white mb-6 flex items-center gap-2 border-b border-slate-100 dark:border-white/5 pb-4">
                    <i class="fas fa-shopping-bag text-amber-500 text-sm"></i> Item yang Dibeli
                </h3>

                <div class="divide-y divide-slate-100 dark:divide-white/5 space-y-4">
                   @foreach($order->items as $item)
                        @php
                            $variant     = $item->variant;
                            $product     = $variant?->product;
                            $productName = $product?->name ?? 'Produk Tidak Tersedia';
                            $brandName   = $product?->brand?->name ?? 'Unknown Brand';
                            $imgRaw      = $product?->image_url;
                            $imgSrc      = $imgRaw
                                ? (str_starts_with($imgRaw, 'http') ? $imgRaw : asset('product_image/' . $imgRaw))
                                : 'https://placehold.co/200x200?text=Scentify';
                        @endphp
                        <div class="flex items-center gap-4 sm:gap-6 pt-4 first:pt-0">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl overflow-hidden bg-slate-100 dark:bg-zinc-800 shrink-0 border border-slate-200 dark:border-white/5">
                                <img src="{{ $imgSrc }}" alt="{{ $productName }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-grow">
                                <small class="text-[9px] font-mono text-amber-600 dark:text-amber-400 uppercase tracking-widest font-semibold block">
                                    {{ $brandName }}
                                </small>
                                <h4 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white mt-0.5 line-clamp-1">
                                    {{ $productName }}
                                </h4>
                                <p class="text-[10px] sm:text-xs text-slate-500 dark:text-zinc-400 mt-1">
                                    Ukuran: <span class="font-semibold">{{ $variant?->size ?? '-' }}</span> 
                                    | Kuantitas: <span class="font-semibold">{{ $item->quantity }}x</span>
                                </p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-xs text-slate-400 dark:text-zinc-500 font-mono">Satuan</p>
                                <p class="text-xs text-slate-600 dark:text-zinc-400 mt-0.5">
                                    Rp {{ number_format($item->price_at_purchase, 0, ',', '.') }}
                                </p>
                                <p class="text-sm font-bold text-slate-900 dark:text-white mt-1">
                                    Rp {{ number_format($item->price_at_purchase * $item->quantity, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- SECTION RATING — hanya tampil jika order Completed --}}
            @if ($order->status === 'Completed')
            @php
                $unreviewedItems = $order->items->filter(function($item) {
                    return $item->variant?->product && !$item->review;
                });
                $allReviewed = $unreviewedItems->isEmpty();
            @endphp

            <div class="glass-card bg-white/60 dark:bg-darkcard/60 rounded-3xl border border-slate-200 dark:border-white/5 p-6 sm:p-8 shadow-xl">
                <h3 class="text-lg font-serif font-bold text-slate-950 dark:text-white mb-2 flex items-center gap-2 border-b border-slate-100 dark:border-white/5 pb-4">
                    <i class="fas fa-star text-amber-500 text-sm"></i> Beri Ulasan Produk
                </h3>
                <p class="text-xs text-slate-400 dark:text-zinc-500 mb-6">Bagikan pengalaman Anda untuk membantu pelanggan lain memilih parfum terbaik.</p>

                @if ($allReviewed)
                    {{-- Semua sudah diulas --}}
                    <div class="text-center py-6">
                        <div class="w-14 h-14 rounded-full bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-check-circle text-emerald-500 text-xl"></i>
                        </div>
                        <p class="text-sm font-bold text-slate-700 dark:text-zinc-300">Semua produk sudah diulas</p>
                        <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1">Terima kasih atas ulasan Anda!</p>
                    </div>

                    {{-- Tampilkan ulasan yang sudah dikirim --}}
                    <div class="space-y-4 mt-4">
                        @foreach ($order->items as $item)
                            @php
                                $product  = $item->variant?->product;
                                $reviewed = $item->review;
                                if (!$product || !$reviewed) continue;
                                $imgRaw = $product->image_url;
                                $imgSrc = $imgRaw
                                    ? (str_starts_with($imgRaw, 'http') ? $imgRaw : asset('product_image/' . $imgRaw))
                                    : 'https://placehold.co/80x80?text=Scentify';
                            @endphp
                            <div class="flex flex-col sm:flex-row sm:items-start gap-4 p-4 rounded-2xl border border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-zinc-900/30">
                                <div class="flex items-center gap-3 sm:w-48 shrink-0">
                                    <img src="{{ $imgSrc }}" class="w-10 h-10 rounded-xl object-cover border border-slate-200 dark:border-white/10 shrink-0" alt="{{ $product->name }}">
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-slate-900 dark:text-white line-clamp-1">{{ $product->name }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $item->variant?->size ?? '-' }}ml</p>
                                    </div>
                                </div>
                                <div class="flex-1 space-y-1">
                                    <div class="flex items-center gap-1">
                                        @for ($s = 1; $s <= 5; $s++)
                                            <i class="fas fa-star text-sm {{ $s <= $reviewed->rating ? 'text-amber-400' : 'text-slate-200 dark:text-zinc-700' }}"></i>
                                        @endfor
                                        <span class="text-xs font-bold text-slate-500 ml-1">{{ $reviewed->rating }}/5</span>
                                    </div>
                                    @if ($reviewed->title)
                                        <p class="text-sm font-semibold text-slate-700 dark:text-zinc-300">{{ $reviewed->title }}</p>
                                    @endif
                                    @if ($reviewed->comment)
                                        <p class="text-xs text-slate-500 dark:text-zinc-400 leading-relaxed">{{ $reviewed->comment }}</p>
                                    @endif
                                    <span class="text-[10px] text-emerald-500 font-semibold flex items-center gap-1">
                                        <i class="fas fa-check-circle"></i> Ulasan terkirim
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                @else
                    {{-- Masih ada yang belum diulas --}}

                    {{-- Tampilkan yang sudah diulas dulu (jika ada) --}}
                    @php $alreadyReviewed = $order->items->filter(fn($i) => $i->review && $i->variant?->product); @endphp
                    @if ($alreadyReviewed->isNotEmpty())
                        <div class="space-y-3 mb-6">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Sudah Diulas</p>
                            @foreach ($alreadyReviewed as $item)
                                @php
                                    $product  = $item->variant->product;
                                    $reviewed = $item->review;
                                    $imgRaw   = $product->image_url;
                                    $imgSrc   = $imgRaw
                                        ? (str_starts_with($imgRaw, 'http') ? $imgRaw : asset('product_image/' . $imgRaw))
                                        : 'https://placehold.co/80x80?text=Scentify';
                                @endphp
                                <div class="flex items-center gap-3 p-3 rounded-xl border border-emerald-100 dark:border-emerald-500/10 bg-emerald-50/50 dark:bg-emerald-500/5">
                                    <img src="{{ $imgSrc }}" class="w-9 h-9 rounded-lg object-cover shrink-0" alt="{{ $product->name }}">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-slate-800 dark:text-zinc-200 truncate">{{ $product->name }}</p>
                                        <div class="flex items-center gap-0.5 mt-0.5">
                                            @for ($s = 1; $s <= 5; $s++)
                                                <i class="fas fa-star text-[10px] {{ $s <= $reviewed->rating ? 'text-amber-400' : 'text-slate-200' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <span class="text-[10px] text-emerald-500 font-semibold shrink-0">
                                        <i class="fas fa-check-circle mr-1"></i>Terkirim
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        <div class="border-t border-slate-100 dark:border-white/5 pt-6 mb-4">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Belum Diulas</p>
                        </div>
                    @endif

                    {{-- Form untuk item yang belum diulas — semua dalam 1 form, 1 tombol --}}
                    <div class="space-y-5" id="reviewItemsContainer">
                        @foreach ($unreviewedItems as $item)
                            @php
                                $product = $item->variant->product;
                                $uid     = 'item_' . $item->id;
                                $imgRaw  = $product->image_url;
                                $imgSrc  = $imgRaw
                                    ? (str_starts_with($imgRaw, 'http') ? $imgRaw : asset('product_image/' . $imgRaw))
                                    : 'https://placehold.co/80x80?text=Scentify';
                            @endphp
                            <div class="flex flex-col sm:flex-row sm:items-start gap-4 p-4 rounded-2xl border border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-zinc-900/30"
                                data-uid="{{ $uid }}"
                                data-product-id="{{ $product->id }}"
                                data-item-id="{{ $item->id }}"
                                data-order-id="{{ $order->id }}">

                                {{-- Info Produk --}}
                                <div class="flex items-center gap-3 sm:w-48 shrink-0">
                                    <img src="{{ $imgSrc }}" class="w-12 h-12 rounded-xl object-cover border border-slate-200 dark:border-white/10 shrink-0" alt="{{ $product->name }}">
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-slate-900 dark:text-white line-clamp-1">{{ $product->name }}</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5">{{ $item->variant?->size ?? '-' }}ml · {{ $item->quantity }}x</p>
                                    </div>
                                </div>

                                {{-- Input Rating --}}
                                <div class="flex-1">
                                    {{-- Bintang --}}
                                    <div class="flex items-center gap-1 mb-3" id="stars{{ $uid }}">
                                        @for ($s = 1; $s <= 5; $s++)
                                            <button type="button"
                                                    onclick="setRating('{{ $uid }}', {{ $s }})"
                                                    onmouseover="hoverRating('{{ $uid }}', {{ $s }})"
                                                    onmouseout="resetHover('{{ $uid }}')"
                                                    class="star-btn text-2xl transition-transform duration-100 hover:scale-110 focus:outline-none">
                                                <i class="far fa-star text-slate-300 dark:text-zinc-600 transition-colors duration-150"></i>
                                            </button>
                                        @endfor
                                        <span id="ratingLabel{{ $uid }}" class="text-xs text-slate-400 ml-2 font-medium">Pilih bintang</span>
                                    </div>

                                    <input type="text"
                                        id="reviewTitle{{ $uid }}"
                                        placeholder="Judul ulasan (opsional)"
                                        maxlength="100"
                                        class="w-full bg-white dark:bg-zinc-800 border border-slate-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm text-slate-700 dark:text-zinc-300 placeholder-slate-300 dark:placeholder-zinc-600 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all mb-2">

                                    <textarea id="reviewComment{{ $uid }}"
                                            placeholder="Ceritakan pengalaman Anda dengan parfum ini..."
                                            maxlength="1000" rows="2"
                                            class="w-full bg-white dark:bg-zinc-800 border border-slate-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm text-slate-700 dark:text-zinc-300 placeholder-slate-300 dark:placeholder-zinc-600 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all resize-none"></textarea>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- 1 Tombol Kirim untuk semua --}}
                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-white/5">
                        <button type="button"
                                onclick="submitAllReviews()"
                                id="submitAllReviewsBtn"
                                class="w-full py-3.5 bg-slate-900 dark:bg-amber-400 text-white dark:text-black text-xs font-bold uppercase tracking-wider rounded-xl hover:bg-amber-500 dark:hover:bg-amber-300 active:scale-95 transition-all shadow-md flex items-center justify-center gap-2">
                            <i class="fas fa-paper-plane"></i>
                            Kirim {{ $unreviewedItems->count() > 1 ? $unreviewedItems->count() . ' Ulasan Sekaligus' : 'Ulasan' }}
                        </button>
                        @if ($unreviewedItems->count() > 1)
                            <p class="text-center text-[11px] text-slate-400 mt-2">
                                Semua ulasan akan dikirim sekaligus dalam satu klik
                            </p>
                        @endif
                    </div>
                @endif
            </div>
            @endif

            <!-- Card: Alamat Tujuan Pengiriman -->
            <div class="glass-card bg-white/60 dark:bg-darkcard/60 rounded-3xl border border-slate-200 dark:border-white/5 p-6 sm:p-8 shadow-xl">
                <h3 class="text-lg font-serif font-bold text-slate-950 dark:text-white mb-6 flex items-center gap-2 border-b border-slate-100 dark:border-white/5 pb-4">
                    <i class="fas fa-map-marker-alt text-amber-500 text-sm"></i> Tujuan Pengiriman
                </h3>

                <div class="space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 dark:border-white/5 pb-4">
                        <div>
                            <p class="text-[10px] font-mono uppercase text-slate-400 dark:text-zinc-500 tracking-wider mb-0.5">Nama Penerima</p>
                            <p class="text-sm sm:text-base font-bold text-slate-900 dark:text-white">{{ $order->user->name ?? 'Pelanggan Scentify' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-mono uppercase text-slate-400 dark:text-zinc-500 tracking-wider mb-0.5 sm:text-right">Nomor Telepon</p>
                            <p class="text-sm sm:text-base font-bold text-slate-900 dark:text-white font-mono sm:text-right">{{ $order->phone_number }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-[10px] font-mono uppercase text-slate-400 dark:text-zinc-500 tracking-wider mb-2">Alamat Lengkap Pengiriman</p>
                        <div class="bg-slate-100/50 dark:bg-zinc-900/50 p-4 rounded-xl border border-slate-200 dark:border-white/5 text-xs sm:text-sm text-slate-700 dark:text-zinc-300 leading-relaxed select-all">
                            {{ $order->shipping_address }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Billing summary & Shipping Info (Col 4) -->
        <div class="lg:col-span-4 space-y-6 reveal">
            <!-- Card: Ringkasan Biaya -->
            <!-- Card: Ringkasan Biaya -->
            <div class="glass-card bg-white/60 dark:bg-darkcard/60 rounded-3xl border border-slate-200 dark:border-white/5 p-6 sm:p-8 shadow-xl">
                <h3 class="text-base font-serif font-bold text-slate-950 dark:text-white mb-6 border-b border-slate-100 dark:border-white/5 pb-4">Rincian Pembayaran</h3>

                @php
                    $subtotal = $order->items->sum(fn($item) => $item->price_at_purchase * $item->quantity);
                    $shipping = 50000;
                    $tax      = round($subtotal * 0.11);
                    $total    = $subtotal + $shipping + $tax;
                @endphp

                <div class="space-y-4 text-xs sm:text-sm">
                    <div class="flex justify-between items-center text-slate-500 dark:text-zinc-400">
                        <span>Subtotal Item</span>
                        <span class="font-medium text-slate-800 dark:text-zinc-200">
                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center text-slate-500 dark:text-zinc-400">
                        <span>Ongkos Pengiriman</span>
                        <span class="font-medium text-slate-800 dark:text-zinc-200">
                            Rp {{ number_format($shipping, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center text-slate-500 dark:text-zinc-400 border-b border-slate-100 dark:border-white/5 pb-4">
                        <span>Pajak (PPN 11%)</span>
                        <span class="font-medium text-slate-800 dark:text-zinc-200">
                            Rp {{ number_format($tax, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center pt-2">
                        <span class="font-serif text-sm sm:text-base font-bold text-slate-950 dark:text-white">Total Akhir</span>
                        <span class="text-lg sm:text-xl font-black text-amber-500">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                @if($order->status === 'Pending')
                    <div class="mt-6 pt-6 border-t border-slate-100 dark:border-white/5">
                        <button onclick="payNow('{{ $order->order_number }}')" 
                                class="w-full text-center py-3.5 font-semibold text-xs tracking-widest uppercase bg-amber-400 hover:bg-amber-300 text-black rounded-xl shadow-lg shadow-amber-500/10 active:scale-95 transition-all">
                            Bayar Sekarang
                        </button>
                    </div>
                @endif
            </div>

            <!-- Card: Informasi Logistik / Resi -->
            <div class="glass-card bg-white/60 dark:bg-darkcard/60 rounded-3xl border border-slate-200 dark:border-white/5 p-6 sm:p-8 shadow-xl">
                <h3 class="text-base font-serif font-bold text-slate-950 dark:text-white mb-4">Informasi Kurir</h3>
                <hr class="border-slate-100 dark:border-white/5 mb-4">

                @if($order->tracking_number)
                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] font-mono uppercase text-slate-400 dark:text-zinc-500 tracking-wider mb-1">Nomor Resi Pengiriman</p>
                            <div class="bg-slate-100/50 dark:bg-zinc-900/50 p-3 rounded-xl font-mono text-base font-bold text-center tracking-widest text-slate-900 dark:text-white border border-slate-200 dark:border-white/5 relative group">
                                <span>{{ $order->tracking_number }}</span>
                                <button onclick="copyToClipboard('{{ $order->tracking_number }}')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-amber-500 transition-colors focus:outline-none" title="Salin Resi">
                                    <i class="far fa-copy text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <button onclick="trackOrder('{{ $order->tracking_number }}')" class="w-full text-center py-3 font-semibold text-xs tracking-wider uppercase border border-indigo-500/20 text-indigo-500 hover:bg-indigo-500/5 rounded-xl transition-all">
                            Lacak Status Kurir
                        </button>
                    </div>
                @else
                    <div class="text-center py-4">
                        <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-zinc-900 flex items-center justify-center text-slate-300 dark:text-zinc-700 mx-auto mb-3">
                            <i class="fas fa-truck-ramp-box text-xl"></i>
                        </div>
                        <p class="text-xs text-slate-400 dark:text-zinc-500 leading-relaxed">Pihak Scentify sedang memproses produk Anda. Nomor resi pengiriman akan terbit otomatis saat diserahkan ke kurir.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

<script>
    function handleStatusChange(val) {
        const trackingField = document.getElementById('trackingField');
        const cancelWarning = document.getElementById('cancelWarning');
        const submitBtn     = document.getElementById('submitBtn');

        trackingField.classList.add('hidden');
        cancelWarning.classList.add('hidden');

        if (val === 'Shipped') {
            trackingField.classList.remove('hidden');
        }

        if (val === 'Cancelled') {
            cancelWarning.classList.remove('hidden');
        }

        submitBtn.disabled = (val === '');
    }

    function confirmUpdate() {
        const statusSelect  = document.getElementById('statusSelect');
        const trackingField = document.getElementById('trackingField');
        const trackingInput = document.getElementById('trackingInput');
        const status        = statusSelect ? statusSelect.value : '';

        if (!status) {
            return;
        }

        // Validasi client-side: resi wajib untuk Shipped
        if (status === 'Shipped') {
            if (!trackingInput || !trackingInput.value.trim()) {
                if (trackingInput) {
                    trackingInput.focus();
                    trackingInput.style.borderColor = '#f87171';
                    setTimeout(() => { trackingInput.style.borderColor = ''; }, 2000);
                }
                return;
            }
        }

        const labelMap = {
            'Processing': 'Processing — Sedang Diproses',
            'Shipped':    'Shipped — Dalam Pengiriman',
            'Completed':  'Completed — Selesai',
            'Cancelled':  'Cancelled — Batalkan Pesanan',
        };

        const isCancelled = status === 'Cancelled';

        // Cek apakah SweetAlert2 tersedia
        if (typeof Swal === 'undefined') {
            // Fallback: langsung submit tanpa konfirmasi
            document.getElementById('statusForm').submit();
            return;
        }

        Swal.fire({
            title: isCancelled ? 'Batalkan Pesanan?' : 'Konfirmasi Perubahan Status',
            html: isCancelled
                ? `<p class="text-sm text-slate-500">Pesanan ini akan dibatalkan secara permanen dan <strong>tidak dapat dipulihkan</strong>.</p>`
                : `<p class="text-sm text-slate-500">Ubah status pesanan menjadi <strong>${labelMap[status] ?? status}</strong>?</p>`,
            icon: isCancelled ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonText: isCancelled ? 'Ya, Batalkan' : 'Ya, Perbarui',
            cancelButtonText: 'Batal',
            confirmButtonColor: isCancelled ? '#f43f5e' : '#f59e0b',
            cancelButtonColor: '#64748b',
            reverseButtons: true,
            customClass: { popup: 'rounded-2xl' }
        }).then(result => {
            if (result.isConfirmed) {
                document.getElementById('statusForm').submit();
            }
        });
    }

    // ===== RATING SYSTEM =====
    // ===== RATING SYSTEM =====
    const ratingValues = {};

    const ratingLabels = {
        1: 'Sangat Buruk',
        2: 'Kurang Baik',
        3: 'Cukup Baik',
        4: 'Bagus',
        5: 'Luar Biasa!'
    };

    function setRating(uid, value) {
        ratingValues[uid] = value;
        renderStars(uid, value, true);
        const label = document.getElementById('ratingLabel' + uid);
        label.textContent = ratingLabels[value];
        label.className = 'text-xs ml-2 font-semibold text-amber-500';
    }

    function hoverRating(uid, value) {
        renderStars(uid, value, false);
    }

    function resetHover(uid) {
        renderStars(uid, ratingValues[uid] || 0, true);
    }

    function renderStars(uid, value, isSet) {
        const container = document.getElementById('stars' + uid);
        if (!container) return;
        container.querySelectorAll('.star-btn').forEach((btn, i) => {
            const icon = btn.querySelector('i');
            icon.className = i < value
                ? 'fas fa-star text-amber-400 transition-colors duration-150'
                : (isSet
                    ? 'far fa-star text-slate-300 dark:text-zinc-600 transition-colors duration-150'
                    : 'far fa-star text-amber-200 transition-colors duration-150');
        });
    }

    async function submitAllReviews() {
        const btn       = document.getElementById('submitAllReviewsBtn');
        const isDark    = document.documentElement.classList.contains('dark');
        const items     = document.querySelectorAll('#reviewItemsContainer [data-uid]');

        // Validasi semua item harus ada ratingnya
        let allValid = true;
        items.forEach(card => {
            const uid = card.dataset.uid;
            if (!ratingValues[uid]) {
                allValid = false;
                // Highlight stars yang belum dipilih
                const starsEl = document.getElementById('stars' + uid);
                if (starsEl) {
                    starsEl.classList.add('ring-2', 'ring-rose-400', 'rounded-lg', 'px-1');
                    setTimeout(() => starsEl.classList.remove('ring-2', 'ring-rose-400', 'rounded-lg', 'px-1'), 2500);
                }
            }
        });

        if (!allValid) {
            Swal.fire({
                toast: true, position: 'bottom-end', icon: 'warning',
                title: 'Pilih rating bintang untuk semua produk.',
                showConfirmButton: false, timer: 3000,
                customClass: { popup: isDark ? 'dark-swal rounded-xl' : 'rounded-xl' }
            });
            return;
        }

        // Loading state
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i> Mengirim semua ulasan...';

        // Kirim semua review secara paralel
        const promises = Array.from(items).map(card => {
            const uid       = card.dataset.uid;
            const productId = parseInt(card.dataset.productId);
            const itemId    = parseInt(card.dataset.itemId);
            const orderId   = parseInt(card.dataset.orderId);
            const title     = document.getElementById('reviewTitle' + uid)?.value?.trim() || null;
            const comment   = document.getElementById('reviewComment' + uid)?.value?.trim() || null;

            return fetch('{{ route("reviews.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept':       'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    order_id:      orderId,
                    order_item_id: itemId,
                    product_id:    productId,
                    rating:        ratingValues[uid],
                    title:         title,
                    comment:       comment,
                })
            }).then(res => res.json().then(data => ({ status: res.status, data, uid, rating: ratingValues[uid], title, comment })));
        });

        try {
            const results = await Promise.all(promises);
            const allSuccess = results.every(r => r.status === 200 || r.status === 201);

            if (allSuccess) {
                // Ganti seluruh form dengan tampilan sukses
                const container = document.getElementById('reviewItemsContainer');
                container.innerHTML = results.map(r => `
                    <div class="flex items-center gap-3 p-3 rounded-xl border border-emerald-100 dark:border-emerald-500/10 bg-emerald-50/50 dark:bg-emerald-500/5">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center shrink-0">
                            <i class="fas fa-check text-emerald-500 text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-0.5">
                                ${Array.from({length: 5}, (_, i) =>
                                    `<i class="fas fa-star text-[11px] ${i < r.rating ? 'text-amber-400' : 'text-slate-200'}"></i>`
                                ).join('')}
                            </div>
                            ${r.title ? `<p class="text-xs font-semibold text-slate-700 dark:text-zinc-300 mt-0.5">${r.title}</p>` : ''}
                            ${r.comment ? `<p class="text-[11px] text-slate-400 dark:text-zinc-500 mt-0.5 line-clamp-1">${r.comment}</p>` : ''}
                        </div>
                        <span class="text-[10px] text-emerald-500 font-bold shrink-0">Terkirim</span>
                    </div>
                `).join('');

                // Sembunyikan tombol kirim
                btn.closest('.mt-6').remove();

                Swal.fire({
                    icon: 'success',
                    title: 'Terima kasih!',
                    text: `${results.length} ulasan berhasil dikirim.`,
                    confirmButtonColor: '#f59e0b',
                    customClass: { popup: isDark ? 'dark-swal rounded-2xl' : 'rounded-2xl' }
                });
            } else {
                throw new Error('Sebagian ulasan gagal dikirim.');
            }

        } catch (err) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Kirim Ulasan';
            Swal.fire({
                toast: true, position: 'bottom-end', icon: 'error',
                title: err.message || 'Gagal mengirim ulasan.',
                showConfirmButton: false, timer: 3000,
                customClass: { popup: isDark ? 'dark-swal rounded-xl' : 'rounded-xl' }
            });
        }
    }
// ===== END RATING SYSTEM =====
    // ===== END RATING SYSTEM =====
</script>
@endsection