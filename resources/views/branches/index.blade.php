@extends('base.base')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <div class="lg:col-span-5 flex flex-col order-2 lg:order-1">
            <div class="mb-6">
                <h1 class="text-2xl sm:text-3xl font-serif font-bold text-gradient">Toko Scentify</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Temukan butik offline premium kami terdekat dari lokasi Anda.
                </p>
            </div>

            <div class="space-y-4 lg:max-h-[calc(100vh-240px)] lg:overflow-y-auto lg:pr-3 native-scrollbar pb-10">
                @forelse($branches as $index => $branch)
                    @php
                        $cleanName = e($branch->name);
                        $cleanAddress = e($branch->address);
                        $cleanCity = $branch->city ? e($branch->city) : '';
                    @endphp
                    
                    <div class="tilt-card glass-card bg-white dark:bg-darkcard rounded-xl p-5 border border-slate-100 dark:border-white/5 shadow-sm hover:border-amber-500/40 dark:hover:border-amber-500/30 transition-all duration-300 group/card cursor-pointer"
                         onclick="changeActiveMap('{{ $cleanName }}', '{{ $cleanAddress }}', '{{ $cleanCity }}', this)">
                        
                        <div class="flex items-start gap-4">
                            <div class="w-20 h-20 rounded-lg bg-slate-50 dark:bg-zinc-900 overflow-hidden flex-shrink-0 border border-slate-100 dark:border-white/5 shadow-inner">
                                <img src="{{ $branch->image_url ? (strpos($branch->image_url, 'http') === 0 ? $branch->image_url : asset('product_image/' . $branch->image_url)) : 'https://placehold.co/200x200?text=Shop' }}" 
                                     alt="{{ $branch->name }}" 
                                     class="w-full h-full object-cover group-hover/card:scale-110 transition-transform duration-500">
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base font-bold text-slate-900 dark:text-zinc-50 group-hover/card:text-amber-500 transition-colors duration-300 truncate">
                                    {{ $branch->name }}
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 line-clamp-2">
                                    {{ $branch->address }}{{ $branch->city ? ', '.$branch->city : '' }}
                                </p>
                                
                                <div class="mt-3 text-[11px] text-slate-600 dark:text-slate-400 flex flex-wrap items-center gap-x-3 gap-y-1">
                                    @if($branch->phone)
                                        <span class="flex items-center gap-1"><i class="fas fa-phone text-amber-500 text-[9px]"></i> {{ $branch->phone }}</span>
                                    @endif
                                    @if($branch->email)
                                        <span class="flex items-center gap-1"><i class="fas fa-envelope text-amber-500 text-[9px]"></i> {{ $branch->email }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($branch->opening_hours)
                            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-white/5 text-xs text-slate-600 dark:text-slate-400">
                                <span class="font-medium text-slate-700 dark:text-zinc-300 flex items-center gap-1.5 mb-1">
                                    <i class="far fa-clock text-amber-500"></i> Jam Operasional:
                                </span>
                                <div class="pl-4 leading-relaxed text-[11px]">{!! nl2br(e($branch->opening_hours)) !!}</div>
                            </div>
                        @endif

                        <div class="mt-3 flex justify-end">
                            <span class="text-[11px] font-semibold text-amber-500 group-hover/card:translate-x-1 transition-transform flex items-center gap-1">
                                Fokus Peta <i class="fas fa-chevron-right text-[9px]"></i>
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center p-8 glass-card bg-white dark:bg-darkcard rounded-xl shadow-sm border border-slate-100 dark:border-white/5">
                        <h4 class="text-lg font-bold text-slate-900 dark:text-zinc-50">Belum ada toko terdaftar</h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Silakan cek kembali nanti.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="lg:col-span-7 sticky top-28 order-1 lg:order-2 mb-6 lg:mb-0">
            <div id="map-container" class="glass-card w-full h-[300px] sm:h-[400px] lg:h-[calc(100vh-160px)] rounded-2xl overflow-hidden shadow-2xl border border-slate-200 dark:border-white/5 relative group">
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent pointer-events-none z-10"></div>
                
                <iframe 
                    id="map-iframe"
                    class="w-full h-full border-0 opacity-90 dark:opacity-75 dark:invert dark:grayscale dark:contrast-125 transition-all duration-700 ease-out z-0"
                    src="about:blank" 
                    allowfullscreen 
                    loading="lazy">
                </iframe>

                <div id="map-overlay" class="absolute bottom-4 left-4 right-4 glass-card bg-white/90 dark:bg-darkcard/90 p-4 rounded-xl border border-slate-200 dark:border-white/10 shadow-xl z-20 transform translate-y-2 opacity-0 pointer-events-none transition-all duration-500 flex items-center justify-between gap-4">
                    <div class="min-w-0">
                        <span class="text-[10px] uppercase tracking-wider text-amber-500 font-bold">Lokasi Terpilih</span>
                        <h4 id="overlay-title" class="text-sm font-bold text-slate-900 dark:text-zinc-50 truncate mt-0.5">Nama Toko</h4>
                        <p id="overlay-address" class="text-xs text-slate-500 dark:text-slate-400 truncate mt-0.5">Alamat Lengkap Toko</p>
                    </div>
                    <a id="overlay-route-btn" href="#" target="_blank" class="flex-shrink-0 bg-gradient-to-r from-amber-500 to-amber-600 text-white text-xs font-semibold px-4 py-2.5 rounded-lg shadow-md hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
                        <i class="fas fa-directions"></i> Rute
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function changeActiveMap(name, address, city, element) {
        const mapIframe = document.getElementById('map-iframe');
        const mapContainer = document.getElementById('map-container');
        const overlay = document.getElementById('map-overlay');
        const overlayTitle = document.getElementById('overlay-title');
        const overlayAddress = document.getElementById('overlay-address');
        const overlayRouteBtn = document.getElementById('overlay-route-btn');

        document.querySelectorAll('.tilt-card').forEach(card => {
            card.classList.remove('border-amber-500', 'dark:border-amber-500/50', 'ring-2', 'ring-amber-500/10');
        });

        if(element) {
            element.classList.add('border-amber-500', 'dark:border-amber-500/50', 'ring-2', 'ring-amber-500/10');
        }

        const fullAddress = address + (city ? ', ' + city : '');
        const searchQuery = encodeURIComponent(name + ' ' + fullAddress);

        if (window.gsap) {
            gsap.fromTo(mapContainer, { opacity: 0.7, scale: 0.995 }, { opacity: 1, scale: 1, duration: 0.5, ease: "power2.out" });
        }

        mapIframe.src = `https://maps.google.com/maps?q=${searchQuery}&t=&z=16&ie=UTF8&iwloc=&output=embed`;

        overlayTitle.innerText = name;
        overlayAddress.innerText = fullAddress;
        overlayRouteBtn.href = `https://www.google.com/maps/search/?api=1&query=${searchQuery}`;
        
        overlay.classList.remove('opacity-0', 'translate-y-2', 'pointer-events-none');
    }

    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            const firstCard = document.querySelector('.tilt-card');
            if (firstCard) {
                firstCard.click();
                firstCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }, 300);
    });
</script>
@endsection