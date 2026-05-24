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
</script>
@endsection