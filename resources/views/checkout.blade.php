@extends('base.base')

@section('content')
@php
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

    @if ($errors->any())
        <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 text-rose-500 rounded-xl text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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
                                            [{{ $addr->label ?? 'Alamat' }}] {{ $addr->first_name }} {{ $addr->last_name ? $addr->last_name . ' - ' : '' }}{{ $addr->address }}, {{ $addr->city }} {{ $addr->postal_code }}
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
                                       class="w-full px-4 py-3.5 bg-white dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-slate-700 dark:text-zinc-300 text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all disabled:bg-slate-50 disabled:dark:bg-zinc-800/80 disabled:text-slate-400 disabled:cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-2 font-bold">Nama Belakang (Penerima)</label>
                                <input type="text" name="last_name" id="last_name" value="{{ $autoLastName }}"
                                       class="w-full px-4 py-3.5 bg-white dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-slate-700 dark:text-zinc-300 text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all disabled:bg-slate-50 disabled:dark:bg-zinc-800/80 disabled:text-slate-400 disabled:cursor-not-allowed">
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
                                       class="w-full px-4 py-3.5 bg-white dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-slate-700 dark:text-zinc-300 text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all disabled:bg-slate-50 disabled:dark:bg-zinc-800/80 disabled:text-slate-400 disabled:cursor-not-allowed">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-2 font-bold">Alamat Lengkap <span class="text-rose-500">*</span></label>
                            <textarea name="address" id="address" rows="3" required placeholder="Nama Jalan, Gedung, No. Rumah, RT/RW"
                                      class="w-full px-4 py-3.5 bg-white dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-slate-700 dark:text-zinc-300 text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all resize-none placeholder-slate-300 disabled:bg-slate-50 disabled:dark:bg-zinc-800/80 disabled:text-slate-400 disabled:cursor-not-allowed"></textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-2 font-bold">Provinsi <span class="text-rose-500">*</span></label>
                                <select name="province_id" id="province_id" required
                                        class="w-full px-4 py-3.5 bg-white dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-slate-700 dark:text-zinc-300 text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all disabled:bg-slate-50 disabled:dark:bg-zinc-800/80 disabled:text-slate-400 disabled:cursor-not-allowed">
                                    <option value="">-- Pilih Provinsi --</option>
                                    @if(isset($provinces))
                                        @foreach($provinces as $prov)
                                            <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-2 font-bold">Kota / Kabupaten <span class="text-rose-500">*</span></label>
                                <select name="city_id" id="city_id" required disabled
                                        class="w-full px-4 py-3.5 bg-white dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-slate-700 dark:text-zinc-300 text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all disabled:bg-slate-50 disabled:dark:bg-zinc-800/80 disabled:text-slate-400 disabled:cursor-not-allowed">
                                    <option value="">-- Pilih Kota/Kabupaten --</option>
                                </select>
                                <input type="hidden" name="city" id="city_name">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-2 font-bold">Kode Pos <span class="text-rose-500">*</span></label>
                            <input type="text" name="postal_code" id="postal_code" required placeholder="12345"
                                   class="w-full px-4 py-3.5 bg-white dark:bg-zinc-900/50 border border-slate-200 dark:border-white/10 rounded-xl text-slate-700 dark:text-zinc-300 text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all disabled:bg-slate-50 disabled:dark:bg-zinc-800/80 disabled:text-slate-400 disabled:cursor-not-allowed">
                        </div>

                        <div id="shipping_section" class="hidden space-y-2 pt-4 border-t border-slate-100 dark:border-white/5">
                            <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 mb-2 font-bold">Layanan JNE (Real-time)</label>
                            <div id="service_list" class="space-y-2">
                                <p class="text-xs text-slate-400 italic">Pilih alamat/kota untuk memuat ongkos kirim.</p>
                            </div>
                            <input type="hidden" name="shipping_cost" id="shipping_cost_input" value="0">
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
                            <span id="shipping_display" class="font-semibold text-slate-800 dark:text-zinc-200">-</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-500 dark:text-zinc-400 pb-4 border-b border-slate-200 dark:border-white/5">
                            <span>Pajak Transaksi (11%)</span>
                            <span class="font-semibold text-slate-800 dark:text-zinc-200">Rp {{ number_format($taxAmount, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center pt-2 mb-6">
                            <h5 class="text-base font-serif font-bold text-slate-900 dark:text-white">Total Pembayaran</h5>
                            <h4 id="total_display" class="text-xl sm:text-2xl font-black text-amber-600 dark:text-amber-400">Rp {{ number_format($totalAmount, 0, ',', '.') }}</h4>
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
    const subtotal = {{ $subtotal }};
    const totalWeight = {{ $totalWeight ?? 1000 }}; // Ambil data berat terhitung dari server
    const taxRate  = 0.11;

    function fmt(n) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(n);
    }

    function updateTotals(shippingCost) {
        const tax = Math.round(subtotal * taxRate);
        let total;

        if (shippingCost === null || shippingCost === undefined || shippingCost === 0) {
            document.getElementById('shipping_display').textContent = '-';
            total = subtotal + tax;
            document.getElementById('shipping_cost_input').value = 0;
        } else {
            total = subtotal + parseInt(shippingCost) + tax;
            document.getElementById('shipping_display').textContent = fmt(shippingCost);
            document.getElementById('shipping_cost_input').value = shippingCost;
        }

        document.getElementById('total_display').textContent = fmt(total);
    }

    // Mengambil data tarif kurir JNE dari RajaOngkir
    function fetchJneOngkir(cityId) {
        if (!cityId) return;

        const serviceList = document.getElementById('service_list');
        const shippingSection = document.getElementById('shipping_section');

        shippingSection.classList.remove('hidden');
        serviceList.innerHTML = '<p class="text-xs text-amber-500 animate-pulse"><i class="fas fa-spinner fa-spin mr-1"></i> Mengambil data ongkir JNE real-time...</p>';

        fetch('/api/ongkir', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ destination: cityId, courier: 'jne', weight: totalWeight })
        })
        .then(r => r.json())
        .then(data => {
            // FIX: Sekarang membaca pattern object { success: true, costs: [...] }
            if (!data.success || !data.costs || data.costs.length === 0) {
                serviceList.innerHTML = '<p class="text-xs text-rose-400">Layanan JNE tidak tersedia untuk wilayah ini.</p>';
                updateTotals(null);
                return;
            }
            
            serviceList.innerHTML = '';
            data.costs.forEach((s, idx) => {
                const cost = s.cost[0]?.value ?? 0;
                const etd  = s.cost[0]?.etd ?? '-';
                const label = document.createElement('label');
                label.className = 'flex items-center justify-between p-3 border border-slate-200 dark:border-white/10 rounded-xl cursor-pointer hover:border-amber-500 transition-all has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50 dark:has-[:checked]:bg-amber-500/10';
                label.innerHTML = `
                    <div class="flex items-center gap-3">
                        <input type="radio" name="_service_radio" value="${cost}" ${idx === 0 ? 'checked' : ''} class="text-amber-500 focus:ring-amber-500">
                        <div>
                            <span class="text-sm font-bold text-slate-800 dark:text-zinc-200">JNE ${s.service}</span>
                            <span class="text-xs text-slate-400 ml-2">(${s.description})</span>
                            <p class="text-[10px] text-slate-400 mt-0.5">Estimasi pengiriman: ${etd} Hari</p>
                        </div>
                    </div>
                    <span class="text-sm font-bold text-amber-600">${fmt(cost)}</span>
                `;
                
                label.querySelector('input').addEventListener('change', () => {
                    document.getElementById('shipping_service_input').value = `JNE ${s.service}`;
                    updateTotals(cost);
                });
                serviceList.appendChild(label);

                // Set default pilihan pertama otomatis
                if (idx === 0) {
                    document.getElementById('shipping_service_input').value = `JNE ${s.service}`;
                    updateTotals(cost);
                }
            });
        })
        .catch(err => {
            console.error(err);
            serviceList.innerHTML = '<p class="text-xs text-rose-400">Gagal terhubung ke API RajaOngkir.</p>';
            updateTotals(null);
        });
    }

    // Mengambil data kota otomatis ketika Provinsi berubah
    document.getElementById('province_id').addEventListener('change', function() {
        const provinceId = this.value;
        const citySelect = document.getElementById('city_id');
        
        citySelect.innerHTML = '<option value="">-- Loading Data Kota... --</option>';
        citySelect.disabled = true;
        document.getElementById('shipping_section').classList.add('hidden');
        updateTotals(null);

        if (provinceId) {
            fetch(`/api/cities/${provinceId}`)
                .then(r => r.json())
                .then(cities => {
                    citySelect.innerHTML = '<option value="">-- Pilih Kota/Kabupaten --</option>';
                    cities.forEach(city => {
                        citySelect.innerHTML += `<option value="${city.city_id || city.id}" data-name="${city.type} ${city.name}" data-postal="${city.postal_code}">${city.type} ${city.name}</option>`;
                    });
                    citySelect.disabled = false;
                })
                .catch(() => {
                    citySelect.innerHTML = '<option value="">-- Gagal memuat data kota --</option>';
                });
        } else {
            citySelect.innerHTML = '<option value="">-- Pilih Kota/Kabupaten --</option>';
        }
    });

    // Menembak hitungan ongkir saat Kota dipilih
    document.getElementById('city_id').addEventListener('change', function() {
        const cityId = this.value;
        if(cityId) {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('city_name').value = selectedOption.getAttribute('data-name');
            document.getElementById('postal_code').value = selectedOption.getAttribute('data-postal');
            fetchJneOngkir(cityId);
        }
    });

    // Handler Switch Alamat Baru vs Alamat Lama Terdaftar
    function initializeAddressSelector() {
        const savedSelect = document.getElementById('savedAddressSelect');
        if (!savedSelect) return;

        const defaultFirstName = @json($autoFirstName);
        const defaultLastName = @json($autoLastName);
        const defaultEmail = @json(auth()->user()->email ?? '');
        const addresses = @json(auth()->user()->addresses->keyBy('id'));

        function fillAddress(addr) {
            const fields = {
                address_id: document.getElementById('address_id'),
                first_name: document.getElementById('first_name'),
                last_name: document.getElementById('last_name'),
                email: document.getElementById('email'),
                phone: document.getElementById('phone'),
                address: document.getElementById('address'),
                postal_code: document.getElementById('postal_code'),
                province_id: document.getElementById('province_id'),
                city_id: document.getElementById('city_id'),
                city_name: document.getElementById('city_name')
            };

            fields.address_id.value = addr ? addr.id : 'new';
            fields.first_name.value = addr ? addr.first_name : defaultFirstName;
            fields.last_name.value = addr ? addr.last_name : defaultLastName;
            fields.email.value = defaultEmail;
            fields.phone.value = addr ? addr.phone : '';
            fields.address.value = addr ? addr.address : '';
            fields.postal_code.value = addr ? addr.postal_code : '';
            
            if (addr && addr.province_id) {
                fields.province_id.value = addr.province_id;
                
                // Rakit kota sementara untuk alamat tersimpan agar ID-nya terbaca sistem
                const citySelect = fields.city_id;
                citySelect.innerHTML = `<option value="${addr.city_id || addr.city}" data-name="${addr.city}" data-postal="${addr.postal_code}" selected>${addr.city}</option>`;
                fields.city_name.value = addr.city;
                
                // Tembakkan API JNE langsung dari ID Kota tersimpan di alamat lama!
                fetchJneOngkir(addr.city_id || addr.city);
            } else {
                fields.province_id.value = '';
                fields.city_id.innerHTML = '<option value="">-- Pilih Kota/Kabupaten --</option>';
                fields.city_id.disabled = true;
                document.getElementById('shipping_section').classList.add('hidden');
                updateTotals(null);
            }

            // Ganti disabled menjadi properti biasa agar tidak bermasalah saat dibaca form CSS Tailwind
            const isReadonly = !!addr;
            const targetFields = ['first_name', 'last_name', 'phone', 'address', 'postal_code', 'province_id', 'city_id'];
            targetFields.forEach(key => {
                if(fields[key]) fields[key].disabled = isReadonly;
            });
        }

        savedSelect.addEventListener('change', function() {
            const val = this.value;
            if (val === 'new') fillAddress(null);
            else if (addresses[val]) fillAddress(addresses[val]);
        });

        // Load alamat default pas pertama kali halaman dibuka
        const defaultAddrObj = @json(auth()->user()->addresses->firstWhere('is_default', true));
        if(defaultAddrObj) {
            savedSelect.value = defaultAddrObj.id;
            fillAddress(defaultAddrObj);
        } else {
            fillAddress(null);
        }
    }

    // Buka proteksi field disabled sebelum form dikirim ke controller agar tidak bernilai NULL
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        const fieldsToEnable = ['first_name', 'last_name', 'phone', 'address', 'postal_code', 'province_id', 'city_id'];
        fieldsToEnable.forEach(key => {
            const el = document.getElementById(key);
            if (el) el.disabled = false;
        });
        
        const costInput = document.getElementById('shipping_cost_input').value;
        if(parseInt(costInput) === 0 || costInput === "") {
            e.preventDefault();
            alert('Silakan pilih salah satu layanan pengiriman JNE terlebih dahulu!');
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        initializeAddressSelector();
    });

    document.getElementById('province_select').addEventListener('change', function() {
        var provinceId = this.value;
        var citySelect = document.getElementById('city_select');
        
        // Kosongkan dulu dropdown kota
        citySelect.innerHTML = '<option value="">-- Pilih Kota/Kabupaten --</option>';
        
        if (provinceId) {
            // Ambil data ke route getCities
            fetch('/get-cities/' + provinceId) // Sesuaikan URL ini dengan URL Route kamu
                .then(response => response.json())
                .then(data => {
                    data.forEach(function(city) {
                        var option = document.createElement('option');
                        option.value = city.id; // Ini akan mengirimkan ID Kota
                        option.text = city.name; // Ini yang tampil di layar user
                        citySelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching cities:', error);
                    alert('Gagal memuat data kota. Silakan coba lagi.');
                });
        }
    });
</script>
@endsection
@endsection