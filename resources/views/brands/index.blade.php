@extends('base.base')
@section('title', 'Koleksi Brand Resmi Scentify')

@section('content')
<div class="w-full bg-transparent pb-24 px-4 max-w-7xl mx-auto min-h-[80vh]" style="margin-top: 140px; padding-top: 20px;">
    
    <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
        <span class="text-xs font-bold tracking-widest text-amber-500 uppercase block">Scentify Collection</span>
        <h2 class="text-3xl md:text-4xl font-light tracking-tight text-slate-900 dark:text-white block">Our Official Brands</h2>
        <p class="text-sm md:text-base text-slate-500 dark:text-slate-400 font-light leading-relaxed block">
            Jelajahi berbagai pilihan brand parfum ternama dan eksklusif yang dikurasi khusus untuk melengkapi karakter dan keharuman harian Anda.
        </p>
        <div class="bg-amber-400 mx-auto rounded-full w-16 h-1 mt-4"></div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8 w-full">
        @forelse($brands as $brand)
            <div class="group relative flex flex-col items-center bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm border border-slate-100 dark:border-slate-800 rounded-2xl p-6 text-center shadow-sm transition-all duration-300 hover:-translate-y-1.5 hover:shadow-md dark:hover:shadow-slate-950/50 hover:border-slate-200 dark:hover:border-slate-700">
                
                <div class="w-24 h-24 md:w-28 md:h-28 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-full p-3 flex items-center justify-center overflow-hidden mb-4 shadow-sm transition-transform duration-300 group-hover:scale-105 mx-auto">
                    @if ($brand->logo_url)
                        <img src="{{ asset('storage/' . $brand->logo_url) }}" 
                             alt="{{ $brand->name }}" 
                             class="max-w-[80%] max-h-[80%] object-contain">
                    @else
                        <span class="text-2xl font-bold text-slate-400 dark:text-slate-500">
                            {{ strtoupper(substr($brand->name, 0, 1)) }}
                        </span>
                    @endif
                </div>

                <div class="space-y-1 mb-5 flex-grow w-full text-center">
                    <h5 class="font-semibold text-slate-800 dark:text-white text-base group-hover:text-amber-600 dark:group-hover:text-amber-400 transition block">
                        {{ $brand->name }}
                    </h5>
                    <span class="block text-[11px] text-slate-400 dark:text-slate-500 font-medium tracking-wide">
                        Official Merchant
                    </span>
                </div>

                <a href="{{ route('shop', ['brand' => $brand->id]) }}"
                   class="w-full flex justify-center items-center text-center text-sm font-semibold tracking-wide py-3.5 rounded-xl transition-all duration-300 shadow-lg shadow-amber-500/5 mt-auto
                          bg-slate-900 dark:bg-amber-400 text-white dark:text-black 
                          hover:bg-amber-500 dark:hover:bg-amber-300 
                          active:scale-95 active:bg-amber-600 dark:active:bg-amber-500 active:text-white">
                    Lihat Produk
                </a>
            </div>
        @empty
            <div class="col-span-full py-20 text-center text-slate-400 dark:text-slate-600">
                <div class="flex flex-col items-center justify-center">
                    <i class="fas fa-tags text-4xl mb-3 text-slate-200 dark:text-slate-800"></i>
                    <p class="text-sm font-medium">Belum ada brand resmi yang tersedia saat ini.</p>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection