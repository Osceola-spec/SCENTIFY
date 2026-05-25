@extends('base.base')

@section('content')
@php
    $nameParts = explode(' ', auth()->user()->name ?? '', 2);
    $autoFirstName = $nameParts[0] ?? '';
    $autoLastName = $nameParts[1] ?? '';
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 relative z-10">
    <div class="absolute top-[10%] left-[5%] w-[250px] h-[250px] bg-amber-500/10 dark:bg-amber-500/5 rounded-full animate-float pointer-events-none filter blur-[80px] -z-10"></div>
    <div class="absolute bottom-[20%] right-[5%] w-[300px] h-[300px] bg-purple-500/10 dark:bg-purple-900/5 rounded-full animate-float pointer-events-none filter blur-[80px] -z-10" style="animation-delay: 2s;"></div>

    <nav class="mb-8 reveal">
        <ol class="flex items-center space-x-2 text-xs font-mono uppercase tracking-wider text-slate-400 dark:text-zinc-500">
            <li><a href="{{ route('home') }}" class="hover:text-amber-500 transition-colors">Home</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ route('shop') }}" class="hover:text-amber-500 transition-colors">Shop</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-amber-500 font-semibold">Checkout</li>
        </ol>
    </nav>

    <div class="mb-10 reveal">
        <span class="text-[10px] sm:text-xs font-mono text-amber-600 dark:text-amber-400 uppercase tracking-widest font-semibold block">Selesaikan Transaksi</span>
        <h1 class="text-3xl md:text-5xl font-serif mt-2 text-slate-950 dark:text-white">Penyelesaian <span class="italic text-amber-500 font-normal">Pesanan</span></h1>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm" class="reveal">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">

            <div class="lg:col-span-7 space-y-8">
                <div class="glass-card bg-white/60 dark:bg-darkcard/60 rounded-[2rem] border border-slate-200 dark:border-white/5 p-6 sm:p-10 shadow-xl relative overflow-hidden">
                    
                    <h3 class="text-xl font-serif font-bold text-slate-950 dark:text-white mb-6 flex items-center gap-3 border-b border-slate-100 dark:border-white/5 pb-4">
                        <span class="w-8 h-8 rounded-full bg-amber-500 text-black flex items-center justify-center text-sm font-bold">1</span> 
                        Informasi Pengiriman
                    </h3>

                    @auth
                        <div class="mb-8 p-5 rounded-2xl bg-amber-500/5 border border-amber-500/20 transition-all hover:bg-amber-500/10">
                            <label class="block text-[10px] font-mono uppercase tracking-widest text-amber-700 dark:text-amber-500 mb-2 font-bold">
                                <i class="fas fa-address-book mr-1"></i> Pilih Alamat Tersimpan
                            </label>
                            <div class="relative">
                                <select id="savedAddressSelect" class="w-full appearance-none bg-white dark:bg-zinc-900 border border-amber-500/30 rounded-xl px-4 py-3 text-sm text-slate-700 dark:text-zinc-300 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all cursor-pointer font-medium shadow-sm">
                                    <option value="new" selected>+ Tambah alamat pengiriman baru...</option>
                                    @foreach(auth()->user()->addresses as $addr)
                                        <option value="{{ $addr->id }}">
                                            {{ $addr->first_name }} {{ $addr->last_name ? $addr->last_name . ' - ' : '' }}{{ $addr->address }}, {{ $addr->city }} {{ $addr->postal_code }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-amber-500">
                                    <i class="fas fa-chevron-down text-[10px]"></i>
                                </div>
                            </div>
                        </div>
                    @endauth

                    <div class="space-y-6">
                        <input type="hidden" name="address_id" id="address_id" value="new">
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-2 font-bold">Nama Depan <span class="text-rose-500">*</span></label>
                                <input type="text" name="first_name" id="first_name" value="{{ $autoFirstName }}" required
                                       class="w-full px-4 py-3.5 bg-white dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-slate-700 dark:text-zinc-300 text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all read-only:bg-slate-50 read-only:dark:bg-zinc-800/80 read-only:text-slate-400 read-only:cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-2 font-bold">Nama Belakang <span class="text-rose-500">*</span></label>
                                <input type="text" name="last_name" id="last_name" value="{{ $autoLastName }}" required
                                       class="w-full px-4 py-3.5 bg-white dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-slate-700 dark:text-zinc-300 text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all read-only:bg-slate-50 read-only:dark:bg-zinc-800/80 read-only:text-slate-400 read-only:cursor-not-allowed">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-2 font-bold">Email <span class="text-rose-500">*</span></label>
                                <input type="email" name="email" id="email" value="{{ auth()->user()->email ?? '' }}" required
                                       class="w-full px-4 py-3.5 bg-white dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-slate-700 dark:text-zinc-300 text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all read-only:bg-slate-50 read-only:dark:bg-zinc-800/80 read-only:text-slate-400 read-only:cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-2 font-bold">Nomor WhatsApp <span class="text-rose-500">*</span></label>
                                <input type="text" name="phone" id="phone" required placeholder="08123456789"
                                       class="w-full px-4 py-3.5 bg-white dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-slate-700 dark:text-zinc-300 text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all read-only:bg-slate-50 read-only:dark:bg-zinc-800/80 read-only:text-slate-400 read-only:cursor-not-allowed">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-2 font-bold">Alamat Lengkap <span class="text-rose-500">*</span></label>
                            <textarea name="address" id="address" rows="3" required placeholder="Nama Jalan, Gedung, No. Rumah, RT/RW"
                                      class="w-full px-4 py-3.5 bg-white dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-slate-700 dark:text-zinc-300 text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all resize-none placeholder-slate-300 read-only:bg-slate-50 read-only:dark:bg-zinc-800/80 read-only:text-slate-400 read-only:cursor-not-allowed"></textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-2 font-bold">Kota / Kabupaten <span class="text-rose-500">*</span></label>
                                <input type="text" name="city" id="city" required placeholder="Contoh: Jakarta Selatan"
                                       class="w-full px-4 py-3.5 bg-white dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-slate-700 dark:text-zinc-300 text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all read-only:bg-slate-50 read-only:dark:bg-zinc-800/80 read-only:text-slate-400 read-only:cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-2 font-bold">Kode Pos <span class="text-rose-500">*</span></label>
                                <input type="text" name="postal_code" id="postal_code" required placeholder="12345"
                                       class="w-full px-4 py-3.5 bg-white dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-slate-700 dark:text-zinc-300 text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all read-only:bg-slate-50 read-only:dark:bg-zinc-800/80 read-only:text-slate-400 read-only:cursor-not-allowed">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="glass-card bg-slate-50/80 dark:bg-darkcard/80 rounded-[2rem] border border-slate-200 dark:border-white/5 p-6 sm:p-8 shadow-2xl lg:sticky lg:top-28 relative overflow-hidden">
                    
                    <h3 class="text-xl font-serif font-bold text-slate-950 dark:text-white mb-6 border-b border-slate-200 dark:border-white/5 pb-4">
                        Ringkasan Pesanan
                    </h3>

                    <div class="space-y-4 mb-6 max-h-[350px] overflow-y-auto pr-2 custom-scrollbar">
                        @foreach ($cart as $item)
                            <div class="flex items-center gap-4 group">
                                <div class="w-16 h-16 rounded-xl bg-white dark:bg-zinc-900 border border-slate-200 dark:border-white/5 overflow-hidden shrink-0">
                                    <img src="{{ strpos($item['image_url'], 'http') === 0 ? $item['image_url'] : asset('product_image/' . $item['image_url']) }}" 
                                         alt="{{ $item['product_name'] }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                </div>
                                <div class="flex-grow">
                                    <h6 class="text-sm font-bold text-slate-900 dark:text-white line-clamp-1">{{ $item['product_name'] }}</h6>
                                    <p class="text-[10px] text-slate-500 dark:text-zinc-400 mt-0.5">
                                        Ukuran: <span class="font-medium">{{ $item['size'] }}</span> <span class="mx-1">|</span> Qty: <span class="font-medium">{{ $item['quantity'] }}</span>
                                    </p>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="text-sm font-bold text-slate-900 dark:text-white">
                                        Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="space-y-3 pt-4 border-t border-slate-200 dark:border-white/5 text-sm">
                        <div class="flex justify-between items-center text-slate-500 dark:text-zinc-400">
                            <span>Subtotal</span>
                            <span class="font-semibold text-slate-800 dark:text-zinc-200">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-500 dark:text-zinc-400">
                            <span>Biaya Pengiriman</span>
                            <span class="font-semibold text-slate-800 dark:text-zinc-200">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-500 dark:text-zinc-400 pb-4 border-b border-slate-200 dark:border-white/5">
                            <span>Pajak Transaksi (11%)</span>
                            <span class="font-semibold text-slate-800 dark:text-zinc-200">Rp {{ number_format($taxAmount, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center pt-2 mb-6">
                            <h5 class="text-base font-serif font-bold text-slate-900 dark:text-white">Total Pembayaran</h5>
                            <h4 class="text-xl sm:text-2xl font-black text-amber-600 dark:text-amber-400">Rp {{ number_format($totalAmount, 0, ',', '.') }}</h4>
                        </div>
                    </div>

                    <button type="submit" class="w-full text-center py-4 font-semibold text-xs tracking-widest uppercase bg-slate-950 dark:bg-amber-400 text-white dark:text-black rounded-xl hover:bg-amber-500 dark:hover:bg-amber-300 shadow-xl shadow-slate-900/10 dark:shadow-amber-500/10 active:scale-95 transition-all flex items-center justify-center gap-2">
                        Lanjutkan Pembayaran <i class="fas fa-lock ml-1"></i>
                    </button>

                    <p class="text-center text-[10px] text-slate-400 dark:text-zinc-500 mt-4 flex items-center justify-center gap-1.5">
                        <i class="fas fa-shield-check text-emerald-500"></i> Transaksi Anda diamankan oleh Enkripsi SSL.
                    </p>
                </div>
            </div>

        </div>
    </form>
</div>

@section('scripts')
<script>
    (function(){
        const savedSelect = document.getElementById('savedAddressSelect');
        if (!savedSelect) return;

        // Data variabel nama otomatis yang diinjeksi dari PHP
        const defaultFirstName = '{{ addslashes($autoFirstName) }}';
        const defaultLastName = '{{ addslashes($autoLastName) }}';
        const defaultEmail = '{{ auth()->user()->email ?? '' }}';

        // Data array alamat dari backend Laravel
        const addresses = {
            @foreach(auth()->user()->addresses as $addr)
                '{{ $addr->id }}': {
                    id: '{{ $addr->id }}',
                    first_name: '{{ addslashes($addr->first_name) }}',
                    last_name: '{{ addslashes($addr->last_name) }}',
                    phone: '{{ addslashes($addr->phone) }}',
                    address: '{{ addslashes($addr->address) }}',
                    city: '{{ addslashes($addr->city) }}',
                    postal_code: '{{ addslashes($addr->postal_code) }}'
                },
            @endforeach
        };

        // Fungsi memanipulasi pengisian field & readonly state
        function fillAddress(addr) {
            // Jika addr kosong (Pilih Alamat Baru), isi dengan Nama & Email User yang sedang Login
            document.getElementById('address_id').value = addr ? addr.id : 'new';
            document.getElementById('first_name').value = addr ? addr.first_name : defaultFirstName;
            document.getElementById('last_name').value  = addr ? addr.last_name : defaultLastName;
            document.getElementById('email').value      = defaultEmail; // Email tidak berubah
            document.getElementById('phone').value      = addr ? addr.phone : '';
            document.getElementById('address').value    = addr ? addr.address : '';
            document.getElementById('city').value       = addr ? addr.city : '';
            document.getElementById('postal_code').value= addr ? addr.postal_code : '';

            // Kunci input field jika menggunakan alamat yang sudah tersimpan
            const disabled = !!addr;
            ['first_name','last_name','phone','address','city','postal_code'].forEach(id => {
                document.getElementById(id).readOnly = disabled;
            });
        }

        // Listener jika terjadi perubahan pada Select Box
        savedSelect.addEventListener('change', function(){
            const val = this.value;
            if (val === 'new') {
                fillAddress(null);
            } else if (addresses[val]) {
                fillAddress(addresses[val]);
            }
        });

        // Initialize: Pilih otomatis alamat default jika ada, jika tidak ada biarkan kosong (new)
        (function(){
            @php $default = auth()->user()->addresses->firstWhere('is_default', true); @endphp
            @if($default)
                const opt = Array.from(savedSelect.options).find(o => o.value == '{{ $default->id }}');
                if (opt) { 
                    savedSelect.value = '{{ $default->id }}'; 
                    savedSelect.dispatchEvent(new Event('change')); 
                }
            @else
                // Eksekusi untuk pre-fill nama jika user belum pernah punya alamat
                fillAddress(null);
            @endif
        })();
    })();
</script>
@endsection
@endsection