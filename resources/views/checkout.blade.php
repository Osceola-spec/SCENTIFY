@extends('base.base')

@section('content')
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
        <h1 class="text-3xl md:text-5xl font-serif mt-2 text-slate-950 dark:text-white">Penyelesaian <span class="text-amber-500 font-normal">Pesanan</span></h1>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 text-rose-500 rounded-xl text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm" class="reveal">
        @csrf
        <input type="hidden" name="address_id" id="address_id" value="">
        <input type="hidden" name="shipping_cost" id="shipping_cost_input" value="0">
        <input type="hidden" name="shipping_service" id="shipping_service_input">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">

            {{-- KIRI: Alamat + Ongkir --}}
            <div class="lg:col-span-7 space-y-6">

                {{-- Pilih Alamat --}}
                <div class="glass-card bg-white/60 dark:bg-darkcard/60 rounded-[2rem] border border-slate-200 dark:border-white/5 p-6 sm:p-8 shadow-xl">
                    <h3 class="text-xl font-serif font-bold text-slate-950 dark:text-white mb-5 flex items-center gap-3 border-b border-slate-100 dark:border-white/5 pb-4">
                        <span class="w-8 h-8 rounded-full bg-amber-500 text-black flex items-center justify-center text-sm font-bold">1</span>
                        Alamat Pengiriman
                    </h3>

                    @php $addresses = auth()->user()->addresses()->orderBy('is_default','desc')->get(); @endphp

                    @if($addresses->isEmpty())
                        <div class="p-5 rounded-2xl bg-amber-500/5 border border-amber-500/20 text-center">
                            <i class="fas fa-map-marker-alt text-amber-400 text-2xl mb-3 block"></i>
                            <p class="text-sm text-slate-600 dark:text-zinc-300 mb-3">Belum ada alamat tersimpan.</p>
                            <a href="{{ route('addresses.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 text-black rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-amber-400 transition-all">
                                <i class="fas fa-plus"></i> Tambah Alamat
                            </a>
                        </div>
                    @else
                        <div class="space-y-3" id="addressList">
                            @foreach($addresses as $addr)
                            <label class="flex items-start gap-4 p-4 border rounded-2xl cursor-pointer transition-all hover:border-amber-500 has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50 dark:has-[:checked]:bg-amber-500/10 {{ $addr->is_default ? 'border-amber-500/40' : 'border-slate-200 dark:border-white/10' }}">
                                <input type="radio" name="_addr_radio" value="{{ $addr->id }}"
                                       data-city-id="{{ $addr->city_id }}"
                                       data-city-name="{{ $addr->city }}"
                                       {{ $addr->is_default ? 'checked' : '' }}
                                       class="mt-1 accent-amber-500">
                                <div class="flex-grow min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap mb-0.5">
                                        <span class="text-[10px] font-mono uppercase tracking-widest text-amber-600 dark:text-amber-400 font-bold">{{ $addr->label ?? 'Alamat' }}</span>
                                        @if($addr->is_default)
                                            <span class="text-[9px] font-mono uppercase bg-amber-500 text-black px-2 py-0.5 rounded-full">Utama</span>
                                        @endif
                                    </div>
                                    <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $addr->first_name }} {{ $addr->last_name }}</p>
                                    <p class="text-xs text-slate-500 dark:text-zinc-400">{{ $addr->phone }}</p>
                                    <p class="text-xs text-slate-600 dark:text-zinc-300 mt-0.5">{{ $addr->address }}, {{ $addr->city }} {{ $addr->postal_code }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('addresses.index') }}" class="text-xs text-amber-500 hover:text-amber-400 font-medium transition-colors">
                                <i class="fas fa-plus mr-1"></i> Kelola / Tambah Alamat
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Layanan Pengiriman --}}
                <div id="shipping_section" class="hidden glass-card bg-white/60 dark:bg-darkcard/60 rounded-[2rem] border border-slate-200 dark:border-white/5 p-6 sm:p-8 shadow-xl">
                    <h3 class="text-xl font-serif font-bold text-slate-950 dark:text-white mb-5 flex items-center gap-3 border-b border-slate-100 dark:border-white/5 pb-4">
                        <span class="w-8 h-8 rounded-full bg-amber-500 text-black flex items-center justify-center text-sm font-bold">2</span>
                        Layanan Pengiriman
                    </h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2 text-slate-900 dark:text-white">Pilih Kurir:</label>
                        <select id="courier_select" class="w-full p-3 border rounded-xl bg-white dark:bg-zinc-800 border-slate-200 dark:border-white/10 text-slate-900 dark:text-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-all">
                            <option value="jne">JNE (Jalur Nugraha Ekakurir)</option>
                            <option value="pos">POS Indonesia</option>
                            <option value="tiki">TIKI (Citra Van Titipan Kilat)</option>
                        </select>
                    </div>
                    <div id="service_list" class="space-y-2">
                        <p class="text-xs text-slate-400">Pilih alamat untuk memuat ongkos kirim.</p>
                    </div>
                </div>

            </div>

            {{-- KANAN: Ringkasan --}}
            <div class="lg:col-span-5">
                <div class="glass-card bg-slate-50/80 dark:bg-darkcard/80 rounded-[2rem] border border-slate-200 dark:border-white/5 p-6 sm:p-8 shadow-2xl lg:sticky lg:top-28">
                    <h3 class="text-xl font-serif font-bold text-slate-950 dark:text-white mb-6 border-b border-slate-200 dark:border-white/5 pb-4">
                        Ringkasan Pesanan
                    </h3>

                    <div class="space-y-4 mb-6 max-h-[300px] overflow-y-auto pr-1 custom-scrollbar">
                        @foreach ($cart as $item)
                        <div class="flex items-center gap-4 group">
                            <div class="w-14 h-14 rounded-xl bg-white dark:bg-zinc-900 border border-slate-200 dark:border-white/5 overflow-hidden shrink-0">
                                <img src="{{ strpos($item['image_url'], 'http') === 0 ? $item['image_url'] : asset('product_image/' . $item['image_url']) }}"
                                     alt="{{ $item['product_name'] }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="flex-grow min-w-0">
                                <p class="text-sm font-bold text-slate-900 dark:text-white line-clamp-1">{{ $item['product_name'] }}</p>
                                <p class="text-[10px] text-slate-500 dark:text-zinc-400 mt-0.5">
                                    {{ $item['size'] }} · Qty {{ $item['quantity'] }}
                                </p>
                            </div>
                            <span class="text-sm font-bold text-slate-900 dark:text-white shrink-0">
                                Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                            </span>
                        </div>
                        @endforeach
                    </div>

                    <div class="space-y-3 pt-4 border-t border-slate-200 dark:border-white/5 text-sm">
                        <div class="flex justify-between text-slate-500 dark:text-zinc-400">
                            <span>Subtotal</span>
                            <span class="font-semibold text-slate-800 dark:text-zinc-200">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-500 dark:text-zinc-400">
                            <span>Biaya Pengiriman</span>
                            <span id="shipping_display" class="font-semibold text-slate-800 dark:text-zinc-200">-</span>
                        </div>
                        <div class="flex justify-between text-slate-500 dark:text-zinc-400 pb-4 border-b border-slate-200 dark:border-white/5">
                            <span>Pajak (11%)</span>
                            <span class="font-semibold text-slate-800 dark:text-zinc-200">Rp {{ number_format($taxAmount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 mb-6">
                            <h5 class="text-base font-serif font-bold text-slate-900 dark:text-white">Total Pembayaran</h5>
                            <h4 id="total_display" class="text-xl sm:text-2xl font-black text-amber-600 dark:text-amber-400">Rp {{ number_format($totalAmount, 0, ',', '.') }}</h4>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 font-semibold text-xs tracking-widest uppercase bg-slate-950 dark:bg-amber-400 text-white dark:text-black rounded-xl hover:bg-amber-500 dark:hover:bg-amber-300 shadow-xl active:scale-95 transition-all flex items-center justify-center gap-2">
                        Lanjutkan Pembayaran <i class="fas fa-lock ml-1"></i>
                    </button>
                    <p class="text-center text-[10px] text-slate-400 dark:text-zinc-500 mt-4 flex items-center justify-center gap-1.5">
                        <i class="fas fa-shield-check text-emerald-500"></i> Transaksi diamankan dengan Enkripsi SSL.
                    </p>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
const subtotal  = {{ $subtotal }};
const taxAmount = {{ $taxAmount }};
const totalWeight = {{ $totalWeight ?? 1000 }};

function fmt(n) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(n);
}

function updateTotals(shippingCost) {
    if (!shippingCost) {
        document.getElementById('shipping_display').textContent = '-';
        document.getElementById('total_display').textContent = fmt(subtotal + taxAmount);
        document.getElementById('shipping_cost_input').value = 0;
    } else {
        document.getElementById('shipping_display').textContent = fmt(shippingCost);
        document.getElementById('total_display').textContent = fmt(subtotal + taxAmount + parseInt(shippingCost));
        document.getElementById('shipping_cost_input').value = shippingCost;
    }
}

function fetchOngkir(cityId) {
    const serviceList = document.getElementById('service_list');
    const shippingSection = document.getElementById('shipping_section');
    const courier = document.getElementById('courier_select').value;
    
    shippingSection.classList.remove('hidden');
    serviceList.innerHTML = `<p class="text-xs text-amber-500 animate-pulse"><i class="fas fa-spinner fa-spin mr-1"></i> Mengambil ongkir ${courier.toUpperCase()}...</p>`;

    fetch("{{ route('api.ongkir') }}", {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ destination: cityId, courier: courier, weight: totalWeight })
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success || !data.costs?.length) {
            serviceList.innerHTML = `<p class="text-xs text-rose-400">Layanan ${courier.toUpperCase()} tidak tersedia untuk wilayah ini.</p>`;
            updateTotals(null); return;
        }
        serviceList.innerHTML = '';
        data.costs.forEach((s, idx) => {
            const cost = s.cost[0]?.value ?? 0;
            const etd  = s.cost[0]?.etd ?? '-';
            const label = document.createElement('label');
            label.className = 'flex items-center justify-between p-3 border border-slate-200 dark:border-white/10 rounded-xl cursor-pointer hover:border-amber-500 transition-all has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50 dark:has-[:checked]:bg-amber-500/10';
            label.innerHTML = `
                <div class="flex items-center gap-3">
                    <input type="radio" name="_service_radio" value="${cost}" ${idx === 0 ? 'checked' : ''} class="accent-amber-500">
                    <div>
                        <span class="text-sm font-bold text-slate-800 dark:text-zinc-200">${courier.toUpperCase()} ${s.service}</span>
                        <p class="text-[10px] text-slate-400 mt-0.5">Estimasi ${etd} Hari</p>
                    </div>
                </div>
                <span class="text-sm font-bold text-amber-600">${fmt(cost)}</span>
            `;
            label.querySelector('input').addEventListener('change', () => {
                document.getElementById('shipping_service_input').value = `${courier.toUpperCase()} ${s.service}`;
                updateTotals(cost);
            });
            serviceList.appendChild(label);
            if (idx === 0) {
                document.getElementById('shipping_service_input').value = `${courier.toUpperCase()} ${s.service}`;
                updateTotals(cost);
            }
        });
    })
    .catch(() => {
        serviceList.innerHTML = '<p class="text-xs text-rose-400">Gagal terhubung ke API RajaOngkir.</p>';
        updateTotals(null);
    });
}

document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    if (!document.getElementById('address_id').value) {
        e.preventDefault();
        Swal.fire({ icon: 'warning', title: 'Pilih Alamat', text: 'Silakan pilih alamat pengiriman terlebih dahulu.', confirmButtonColor: '#f59e0b' });
        return;
    }
    const cost = parseInt(document.getElementById('shipping_cost_input').value);
    if (!cost) {
        e.preventDefault();
        Swal.fire({ icon: 'warning', title: 'Pilih Layanan Pengiriman', text: 'Silakan pilih layanan pengiriman terlebih dahulu.', confirmButtonColor: '#f59e0b' });
    }
});

// Init: pilih alamat default
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[name="_addr_radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('address_id').value = this.value;
            const cityId = this.dataset.cityId || this.dataset.cityName;
            if (cityId) fetchOngkir(cityId);
            else {
                document.getElementById('shipping_section').classList.add('hidden');
                updateTotals(null);
            }
        });
    });

    const checked = document.querySelector('[name="_addr_radio"]:checked');
    if (checked) checked.dispatchEvent(new Event('change'));

    // Re-fetch ongkir if courier is changed
    document.getElementById('courier_select').addEventListener('change', function() {
        const checkedAddr = document.querySelector('[name="_addr_radio"]:checked');
        if (checkedAddr) {
            const cityId = checkedAddr.dataset.cityId || checkedAddr.dataset.cityName;
            if (cityId) fetchOngkir(cityId);
        }
    });
});
</script>
@endsection
