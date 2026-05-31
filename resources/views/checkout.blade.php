@extends('base.base')

@section('content')
@php
    // Langsung ambil dari kolom database yang baru
    $autoFirstName = auth()->user()->first_name ?? '';
    $autoLastName = auth()->user()->last_name ?? '';
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
                                <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-2 font-bold">Nama Depan (Penerima) <span class="text-rose-500">*</span></label>
                                <input type="text" name="first_name" id="first_name" value="{{ $autoFirstName }}" required
                                       class="w-full px-4 py-3.5 bg-white dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-slate-700 dark:text-zinc-300 text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all read-only:bg-slate-50 read-only:dark:bg-zinc-800/80 read-only:text-slate-400 read-only:cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-2 font-bold">Nama Belakang (Penerima)</label>
                                <input type="text" name="last_name" id="last_name" value="{{ $autoLastName }}"
                                       class="w-full px-4 py-3.5 bg-white dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-slate-700 dark:text-zinc-300 text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all read-only:bg-slate-50 read-only:dark:bg-zinc-800/80 read-only:text-slate-400 read-only:cursor-not-allowed">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-2 font-bold">Email <span class="text-rose-500">*</span></label>
                                <input type="email" name="email" id="email" value="{{ auth()->user()->email ?? '' }}" required readonly
                                       class="w-full px-4 py-3.5 bg-slate-50 dark:bg-zinc-800/80 border border-slate-200 dark:border-white/10 rounded-xl text-slate-400 text-sm cursor-not-allowed">
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

                        {{-- Layanan JNE --}}
                        <div id="shipping_section" class="hidden space-y-2">
                            <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-2 font-bold">Layanan JNE</label>
                            <div id="service_list" class="space-y-2">
                                <p class="text-xs text-slate-400 italic">Pilih alamat tersimpan untuk melihat ongkir.</p>
                            </div>
                            <input type="hidden" name="shipping_cost" id="shipping_cost_input" value="{{ $shippingCost }}">
                            <input type="hidden" name="shipping_service" id="shipping_service_input">
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
                            <span id="shipping_display" class="font-semibold text-slate-800 dark:text-zinc-200">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
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
    // ==========================================
    // RAJAONGKIR: Auto-fetch JNE ongkir
    // ==========================================
    const subtotal = {{ $subtotal }};
    const taxRate  = 0.11;

    function fmt(n) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(n);
    }

    function updateTotals(shippingCost) {
        const tax   = Math.round(subtotal * taxRate);
        const total = subtotal + shippingCost + tax;
        document.getElementById('shipping_display').textContent = fmt(shippingCost);
        document.getElementById('shipping_cost_input').value = shippingCost;
    }

    function fetchJneOngkir(cityName) {
        const serviceList    = document.getElementById('service_list');
        const shippingSection = document.getElementById('shipping_section');

        shippingSection.classList.remove('hidden');
        serviceList.innerHTML = '<p class="text-xs text-slate-400 animate-pulse">Mengambil data ongkir JNE...</p>';

        // Step 1: cari city_id berdasarkan nama kota
        fetch(`/api/cities?q=${encodeURIComponent(cityName)}`)
            .then(r => r.json())
            .then(cities => {
                if (!cities.length) {
                    serviceList.innerHTML = '<p class="text-xs text-rose-400">Kota tidak ditemukan di RajaOngkir.</p>';
                    return;
                }
                const cityId = cities[0].id;

                // Step 2: fetch ongkir JNE
                return fetch('/api/ongkir', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ destination: cityId, courier: 'jne', weight: 1000 })
                }).then(r => r.json());
            })
            .then(services => {
                if (!services) return;
                serviceList.innerHTML = '';
                if (!services.length) {
                    serviceList.innerHTML = '<p class="text-xs text-rose-400">Layanan JNE tidak tersedia untuk kota ini.</p>';
                    return;
                }
                services.forEach((s, idx) => {
                    const cost = s.cost[0]?.value ?? 0;
                    const etd  = s.cost[0]?.etd ?? '-';
                    const label = document.createElement('label');
                    label.className = 'flex items-center justify-between p-3 border border-slate-200 dark:border-white/10 rounded-xl cursor-pointer hover:border-amber-500 transition-all has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50 dark:has-[:checked]:bg-amber-500/10';
                    label.innerHTML = `
                        <div class="flex items-center gap-3">
                            <input type="radio" name="_service_radio" value="${cost}" ${idx === 0 ? 'checked' : ''} class="text-amber-500 focus:ring-amber-500">
                            <div>
                                <span class="text-sm font-bold text-slate-800 dark:text-zinc-200">${s.service}</span>
                                <span class="text-xs text-slate-400 ml-2">${s.description}</span>
                                <p class="text-[10px] text-slate-400 mt-0.5">Estimasi: ${etd} hari</p>
                            </div>
                        </div>
                        <span class="text-sm font-bold text-amber-600">${fmt(cost)}</span>
                    `;
                    label.querySelector('input').addEventListener('change', () => {
                        document.getElementById('shipping_service_input').value = `JNE ${s.service}`;
                        updateTotals(cost);
                    });
                    serviceList.appendChild(label);

                    // Auto-select first service
                    if (idx === 0) {
                        document.getElementById('shipping_service_input').value = `JNE ${s.service}`;
                        updateTotals(cost);
                    }
                });
            })
            .catch(() => {
                serviceList.innerHTML = '<p class="text-xs text-rose-400">Gagal mengambil data ongkir.</p>';
            });
    }

    console.log('=== CHECKOUT ADDRESS SELECTOR INIT ===');

    function initializeAddressSelector() {
        const savedSelect = document.getElementById('savedAddressSelect');
        if (!savedSelect) {
            console.error('savedAddressSelect element tidak ditemukan!');
            return;
        }

        // Data dari Laravel

        const defaultFirstName = @json($autoFirstName);
        const defaultLastName = @json($autoLastName);
        const defaultEmail = @json(auth()->user()->email ?? '');
        const addresses = @json(auth()->user()->addresses->keyBy('id'));
        console.log('Default values:', { defaultFirstName, defaultLastName, defaultEmail });
        console.log('Available addresses:', addresses);

        function fillAddress(addr) {
            console.log('=== FILLING ADDRESS ===');
            console.log('Address data:', addr);
            
            try {
                // Get all form fields
                const fields = {
                    address_id: document.getElementById('address_id'),
                    first_name: document.getElementById('first_name'),
                    last_name: document.getElementById('last_name'),
                    email: document.getElementById('email'),
                    phone: document.getElementById('phone'),
                    address: document.getElementById('address'),
                    city: document.getElementById('city'),
                    postal_code: document.getElementById('postal_code')
                };

                // Verify all fields exist
                for (let key in fields) {
                    if (!fields[key]) {
                        console.error(`Field not found: ${key}`);
                        return;
                    }
                }

                // Set values
                fields.address_id.value = addr ? addr.id : 'new';
                fields.first_name.value = addr ? addr.first_name : defaultFirstName;
                fields.last_name.value = addr ? addr.last_name : defaultLastName;
                fields.email.value = defaultEmail;
                fields.phone.value = addr ? addr.phone : '';
                fields.address.value = addr ? addr.address : '';
                fields.city.value = addr ? addr.city : '';
                fields.postal_code.value = addr ? addr.postal_code : '';

                // Auto-fetch JNE ongkir jika kota tersedia
                if (addr && addr.city) {
                    fetchJneOngkir(addr.city);
                } else {
                    document.getElementById('shipping_section').classList.add('hidden');
                }

                console.log('Values set. New values:');
                console.log({
                    address_id: fields.address_id.value,
                    first_name: fields.first_name.value,
                    last_name: fields.last_name.value,
                    email: fields.email.value,
                    phone: fields.phone.value,
                    address: fields.address.value,
                    city: fields.city.value,
                    postal_code: fields.postal_code.value
                });

                // Set readonly state
                const disabled = !!addr;
                const editableFields = ['first_name', 'last_name', 'phone', 'address', 'city', 'postal_code'];
                
                editableFields.forEach(key => {
                    fields[key].readOnly = disabled;
                    console.log(`Field ${key} readonly: ${disabled}`);
                });

            } catch (error) {
                console.error('Error in fillAddress:', error);
            }
        }

        // Add change listener
        savedSelect.addEventListener('change', function(e) {
            const val = this.value;
            console.log('=== DROPDOWN CHANGED ===');
            console.log('Selected value:', val);
            
            if (val === 'new') {
                console.log('Filling with new address (empty)');
                fillAddress(null);
            } else if (addresses[val]) {
                console.log('Filling with address:', addresses[val]);
                fillAddress(addresses[val]);
            } else {
                console.warn('Address ID not found in addresses object:', val);
                console.warn('Available IDs:', Object.keys(addresses));
            }
        });

        // Initialize with default address
        try {
            @php $default = auth()->user()->addresses->firstWhere('is_default', true); @endphp
            @if($default)
                const defaultAddressId = '{{ $default->id }}';
                console.log('Setting default address ID:', defaultAddressId);
                savedSelect.value = defaultAddressId;
                
                if (addresses[defaultAddressId]) {
                    console.log('Found default address in object');
                    fillAddress(addresses[defaultAddressId]);
                } else {
                    console.warn('Default address not in addresses object');
                }
            @else
                console.log('No default address, initializing empty');
                fillAddress(null);
            @endif
        } catch (err) {
            console.error('Error in initialization:', err);
        }

        console.log('=== INITIALIZATION COMPLETE ===');
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        console.log('DOM still loading, waiting for DOMContentLoaded');
        document.addEventListener('DOMContentLoaded', initializeAddressSelector);
    } else {
        console.log('DOM already loaded, initializing now');
        initializeAddressSelector();
    }
</script>
@endsection
@endsection