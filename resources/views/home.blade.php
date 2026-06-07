@extends('base.base')

@section('content')
    <section id="home" class="min-h-screen flex items-center justify-center relative px-4 sm:px-6 overflow-hidden pt-28 lg:pt-16 bg-transparent">
        <div id="glow-orb-1" class="absolute top-[15%] left-[10%] w-[250px] h-[250px] sm:w-[350px] sm:h-[350px] bg-amber-500/20 dark:bg-amber-500/10 rounded-full ambient-glow-orb pointer-events-none"></div>
        <div id="glow-orb-2" class="absolute bottom-[15%] right-[10%] w-[300px] h-[300px] sm:w-[400px] sm:h-[400px] bg-purple-500/10 dark:bg-purple-900/5 rounded-full ambient-glow-orb pointer-events-none"></div>

        <div class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:30px_30px] pointer-events-none opacity-60"></div>

        <div class="max-w-7xl w-full flex flex-col lg:flex-row items-center gap-12 lg:gap-16 z-10">
            <div class="flex-1 text-left sm:text-center lg:text-left hero-text-container will-animate w-full">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/20 mb-4 sm:mb-6">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 pulse-ring"></span>
                    <span class="text-[9px] sm:text-[10px] font-mono uppercase tracking-widest text-amber-600 dark:text-amber-400 font-semibold">Luxury Olfactory Experience</span>
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-7xl font-serif mb-4 sm:mb-6 leading-[1.15] tracking-tight text-slate-950 dark:text-white">
                    <span class="hero-word inline-block">Expression</span> <span class="hero-word inline-block">Art</span> <br>
                    <span class="hero-word inline-block text-gradient font-bold">Luxurious</span> <span class="hero-word inline-block text-gradient font-bold">Scent</span>
                </h1>
                <p class="text-slate-600 dark:text-zinc-400 text-sm sm:text-base lg:text-lg mb-8 sm:mb-10 leading-relaxed max-w-lg mx-auto lg:mx-0">
                    Introducing a curation of world-class designer fragrances, complex niche scents, and premium local blends for your unique aura.                </p>
                <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-3 sm:gap-4 w-full sm:w-auto">
                    <a href="#scent-explorer" class="btn-glow px-6 sm:px-8 py-3.5 sm:py-4 text-sm sm:text-base text-center rounded-xl font-semibold tracking-wide bg-slate-950 dark:bg-amber-500 text-white dark:text-black shadow-lg hover:shadow-amber-500/20 transition-all duration-300 transform hover:-translate-y-1">
                        Find Your Scent ✨
                    </a>
                    <a href="#koleksi" class="px-6 sm:px-8 py-3.5 sm:py-4 text-sm sm:text-base text-center rounded-xl font-semibold border border-slate-200 dark:border-zinc-800 text-slate-800 dark:text-zinc-300 hover:border-amber-500 dark:hover:border-amber-400 hover:text-amber-600 dark:hover:text-amber-400 transition-all duration-300 transform hover:-translate-y-1">
                        View Collection
                    </a>
                </div>
            </div>
            
            <div class="flex-1 flex justify-center relative hero-bottle-container will-animate w-full">
                <div class="absolute inset-0 rounded-full bg-gradient-to-tr from-amber-500/20 to-purple-600/20 blur-3xl opacity-40"></div>
                
                <style>
                    .hero-swiper-container {
                        position: relative;
                        width: 100%;
                        max-width: 500px;
                        margin: 0 auto;
                        padding: 0 80px;
                    }
                    .hero-swiper {
                        width: 240px;
                        height: 340px;
                        margin: 0 auto;
                        overflow: visible;
                    }
                    .hero-swiper .swiper-slide {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        border-radius: 2rem;
                        background-color: transparent;
                        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
                    }
                    .hero-swiper .swiper-slide img {
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                        border-radius: 2rem;
                        border: 1px solid rgba(255,255,255,0.1);
                    }
                    @media (min-width: 640px) {
                        .hero-swiper { width: 280px; height: 380px; }
                    }
                    @media (min-width: 1024px) {
                        .hero-swiper { width: 320px; height: 440px; }
                    }
                    .hero-swiper-container .swiper-button-next,
                    .hero-swiper-container .swiper-button-prev {
                        color: #f59e0b;
                        background: rgba(0,0,0,0.5);
                        width: 44px;
                        height: 44px;
                        border-radius: 50%;
                        border: 1px solid rgba(245,158,11,0.3);
                        backdrop-filter: blur(4px);
                    }
                    .hero-swiper-container .swiper-button-prev {
                        left: 10px;
                    }
                    .hero-swiper-container .swiper-button-next {
                        right: 10px;
                    }
                    .hero-swiper-container .swiper-button-next:after,
                    .hero-swiper-container .swiper-button-prev:after {
                        font-size: 18px;
                        font-weight: bold;
                    }
                </style>

                <div class="hero-swiper-container z-10">
                    <div class="swiper hero-swiper cursor-grab active:cursor-grabbing">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <img src="{{ asset('images/hero-perfumes/bleu_de_chanel.jpg') }}" alt="Bleu de Chanel">
                            </div>
                            <div class="swiper-slide">
                                <img src="{{ asset('images/hero-perfumes/dior_sauvage.jpg') }}" alt="Dior Sauvage">
                            </div>
                            <div class="swiper-slide">
                                <img src="{{ asset('images/hero-perfumes/creed_aventus.jpg') }}" alt="Creed Aventus">
                            </div>
                            <div class="swiper-slide">
                                <img src="{{ asset('images/hero-perfumes/acqua_di_gio.jpg') }}" alt="Acqua Di Gio">
                            </div>
                            <div class="swiper-slide">
                                <img src="{{ asset('images/hero-perfumes/lv_imagination.jpg') }}" alt="LV Imagination">
                            </div>
                        </div>
                    </div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
            </div>
        </div>
    </section>

    <section id="scent-explorer" class="py-24 sm:py-32 bg-transparent transition-colors duration-500 relative overflow-hidden">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 relative z-10">
            <div class="text-center mb-12 sm:mb-20" data-aos="fade-up">
                <span class="text-[10px] sm:text-xs font-mono text-amber-600 dark:text-amber-400 uppercase tracking-widest font-semibold">Interactive Scent Finder</span>
                <h2 class="text-3xl md:text-5xl font-serif mt-2 text-slate-950 dark:text-white">Find Your <span class="text-amber-500 font-normal">Scent Character</span></h2>
                <div class="w-12 sm:w-16 h-[2px] bg-amber-500 mx-auto mt-4 sm:mt-6 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                <div class="lg:col-span-5 flex flex-col justify-between gap-6" data-aos="fade-right" data-aos-delay="100">
                    <div class="glass-card p-5 sm:p-6 rounded-2xl sm:rounded-3xl">
                        <h3 class="text-base sm:text-lg font-bold mb-3 sm:mb-4 flex items-center gap-2">
                            <i class="fas fa-sliders text-amber-500"></i> Choose Your Main Mood:
                        </h3>
                        <p class="text-[11px] sm:text-xs text-slate-500 dark:text-zinc-400 mb-5 sm:mb-6 leading-relaxed">Select one of the moods below to see our color and scent recommendations tailored to your vibe.</p>
                        
                        <div class="space-y-2 sm:space-y-3">
                            <button onclick="setScentMood('woody')" id="mood-woody" class="w-full text-left p-3.5 sm:p-4 rounded-xl border border-amber-500/30 bg-amber-500/10 text-amber-500 transition-all duration-300 font-medium text-[11px] sm:text-sm flex justify-between items-center group">
                                <span>🌲 Woody & Earthy (Hot, Masculine)</span>
                                <i class="fas fa-chevron-right text-xs group-hover:translate-x-1.5 transition-transform"></i>
                            </button>
                            <button onclick="setScentMood('floral')" id="mood-floral" class="w-full text-left p-3.5 sm:p-4 rounded-xl border border-slate-200 dark:border-white/5 hover:border-pink-400/50 hover:bg-pink-500/5 text-slate-700 dark:text-zinc-300 transition-all duration-300 font-medium text-[11px] sm:text-sm flex justify-between items-center group">
                                <span>🌸 Floral & Powdery (Sweet, Feminine)</span>
                                <i class="fas fa-chevron-right text-xs group-hover:translate-x-1.5 transition-transform"></i>
                            </button>
                            <button onclick="setScentMood('citrus')" id="mood-citrus" class="w-full text-left p-3.5 sm:p-4 rounded-xl border border-slate-200 dark:border-white/5 hover:border-emerald-400/50 hover:bg-emerald-500/5 text-slate-700 dark:text-zinc-300 transition-all duration-300 font-medium text-[11px] sm:text-sm flex justify-between items-center group">
                                <span>🍋 Citrus & Fresh (Energic, Sporty)</span>
                                <i class="fas fa-chevron-right text-xs group-hover:translate-x-1.5 transition-transform"></i>
                            </button>
                            <button onclick="setScentMood('oriental')" id="mood-oriental" class="w-full text-left p-3.5 sm:p-4 rounded-xl border border-slate-200 dark:border-white/5 hover:border-purple-400/50 hover:bg-purple-500/5 text-slate-700 dark:text-zinc-300 transition-all duration-300 font-medium text-[11px] sm:text-sm flex justify-between items-center group">
                                <span>🔮 Oriental & Spice (Mysterious, Exotic)</span>
                                <i class="fas fa-chevron-right text-xs group-hover:translate-x-1.5 transition-transform"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-7" data-aos="fade-left" data-aos-delay="200">
                    <div id="scent-result-card" class="glass-card p-6 sm:p-8 rounded-2xl sm:rounded-3xl h-full flex flex-col justify-between border border-amber-500/20 shadow-xl shadow-amber-500/5 relative overflow-hidden" style="z-index: 1;">
                        <div id="scent-card-ambient" class="absolute -top-10 -right-10 w-32 h-32 sm:w-48 sm:h-48 bg-amber-500/20 rounded-full blur-3xl pointer-events-none transition-all duration-500" style="z-index: 0;"></div>
                        
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-4 sm:mb-6">
                                <span class="text-[9px] sm:text-xs font-mono uppercase text-amber-500 font-bold" id="scent-badge">Woody Recommendation</span>
                                <span class="text-[10px] sm:text-xs text-slate-500 font-semibold" id="scent-compatibility">Match: 98%</span>
                            </div>

                            <h3 class="text-2xl sm:text-3xl font-serif mb-2 sm:mb-4 text-slate-950 dark:text-white transition-colors" id="scent-title">Golden Amber</h3>
                            <p class="text-xs sm:text-sm text-slate-600 dark:text-zinc-400 leading-relaxed mb-6" id="scent-desc">
                                Perfect for those who love deep, natural scents. It exudes an aura of wisdom, earthy warmth, and a sense of sophisticated serenity thanks to its blend of cedarwood, vetiver, and premium amber.
                            </p>

                            <div class="space-y-3 mb-6 sm:mb-8">
                                <h4 class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-slate-400">Olfactory Notes Pyramid:</h4>
                                <div class="space-y-2 text-[10px] sm:text-xs">
                                    <div class="flex items-start sm:items-center gap-2 sm:gap-3 flex-col sm:flex-row">
                                        <span class="w-20 font-mono text-slate-500">Top Note:</span>
                                        <span id="note-top" class="px-2.5 py-1 bg-slate-200 dark:bg-zinc-800 rounded">Lemon, Bergamot</span>
                                    </div>
                                    <div class="flex items-start sm:items-center gap-2 sm:gap-3 flex-col sm:flex-row">
                                        <span class="w-20 font-mono text-slate-500">Heart Note:</span>
                                        <span id="note-heart" class="px-2.5 py-1 bg-slate-200 dark:bg-zinc-800 rounded">Rosewood, Nutmeg</span>
                                    </div>
                                    <div class="flex items-start sm:items-center gap-2 sm:gap-3 flex-col sm:flex-row">
                                        <span class="w-20 font-mono text-slate-500">Base Note:</span>
                                        <span id="note-base" class="px-2.5 py-1 bg-amber-500/10 text-amber-500 rounded font-semibold">Amber, Cedarwood</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pt-5 sm:pt-6 border-t border-slate-200 dark:border-white/5 relative z-10">
                            <div>
                                <span class="text-[10px] sm:text-xs text-slate-500 block">Starts From</span>
                                <span class="text-xl sm:text-2xl font-bold" id="scent-price">Rp 425.000</span>
                            </div>
                            <button onclick="addToCart()" class="w-full sm:w-auto px-6 py-3 sm:py-3.5 bg-slate-950 dark:bg-amber-400 text-white dark:text-black rounded-xl text-sm font-semibold shadow-lg hover:scale-105 active:scale-95 transition-all duration-300">
                                <i class="fas fa-cart-plus mr-2"></i>Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="koleksi" class="py-24 sm:py-32 bg-transparent transition-colors duration-500 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 sm:mb-20 reveal" data-aos="fade-up">
                <span class="text-[10px] sm:text-xs font-mono text-amber-600 dark:text-amber-400 uppercase tracking-widest font-semibold">Premium Categories</span>
                <h2 class="text-3xl md:text-5xl font-serif mt-2 text-slate-950 dark:text-white transition-colors duration-500">Our <span class="text-amber-500 font-normal">Collections</span></h2>
                <div class="w-12 sm:w-16 h-[2px] bg-amber-500 mx-auto mt-4 sm:mt-6 rounded-full"></div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-10">
                <div class="tilt-container reveal max-w-sm mx-auto sm:max-w-none w-full" data-aos="fade-up" data-aos-delay="100">
                    <div class="tilt-card glass-card relative overflow-hidden rounded-2xl sm:rounded-3xl aspect-[4/5] sm:aspect-[3/4] shadow-xl sm:shadow-2xl group border border-amber-500/20 hover:border-amber-500/50 transition-colors duration-500 bg-slate-900/50 dark:bg-zinc-900/50">
                        <div class="absolute inset-0 z-0">
                            <img src="{{ asset('images/hero-perfumes/lv_imagination.jpg') }}" alt="Designer Collection" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 opacity-60">
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-br from-amber-500/10 via-transparent to-purple-900/40 z-10 pointer-events-none group-hover:opacity-80 transition-opacity duration-500"></div>
                        
                        <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8 z-20 bg-gradient-to-t from-slate-950/90 via-slate-950/50 to-transparent">
                            <span class="text-[10px] sm:text-xs font-mono text-amber-400 tracking-wider uppercase">Signature Line</span>
                            <h3 class="text-2xl sm:text-3xl font-serif text-white mt-1 mb-3 sm:mb-4">Designer</h3>
                            <a href="{{ route('shop') }}?category[]=Designer" class="inline-flex items-center text-xs sm:text-sm font-medium text-amber-300 hover:text-white transition-colors">
                                <span>Explore</span>
                                <i class="fas fa-arrow-right ml-2 text-[10px] sm:text-xs transition-transform group-hover:translate-x-1.5"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="tilt-container reveal max-w-sm mx-auto sm:max-w-none w-full" data-aos="fade-up" data-aos-delay="200">
                    <div class="tilt-card glass-card relative overflow-hidden rounded-2xl sm:rounded-3xl aspect-[4/5] sm:aspect-[3/4] shadow-xl sm:shadow-2xl group border border-rose-500/20 hover:border-rose-500/50 transition-colors duration-500 bg-slate-900/50 dark:bg-zinc-900/50">
                        <div class="absolute inset-0 z-0">
                            <img src="{{ asset('images/hero-perfumes/xerjoff_naxos.jpg') }}" alt="Niche Collection" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 opacity-60">
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-br from-rose-500/10 via-transparent to-orange-900/40 z-10 pointer-events-none group-hover:opacity-80 transition-opacity duration-500"></div>
                        
                        <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8 z-20 bg-gradient-to-t from-slate-950/90 via-slate-950/50 to-transparent">
                            <span class="text-[10px] sm:text-xs font-mono text-rose-400 tracking-wider uppercase">Artisanal Blends</span>
                            <h3 class="text-2xl sm:text-3xl font-serif text-white mt-1 mb-3 sm:mb-4">Niche</h3>
                            <a href="{{ route('shop') }}?category[]=Niche" class="inline-flex items-center text-xs sm:text-sm font-medium text-rose-300 hover:text-white transition-colors">
                                <span>Explore</span>
                                <i class="fas fa-arrow-right ml-2 text-[10px] sm:text-xs transition-transform group-hover:translate-x-1.5"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="tilt-container reveal max-w-sm mx-auto sm:max-w-none w-full sm:col-span-2 lg:col-span-1" data-aos="fade-up" data-aos-delay="300">
                    <div class="tilt-card glass-card relative overflow-hidden rounded-2xl sm:rounded-3xl aspect-[4/5] sm:aspect-[3/4] shadow-xl sm:shadow-2xl group border border-emerald-500/20 hover:border-emerald-500/50 transition-colors duration-500 bg-slate-900/50 dark:bg-zinc-900/50">
                        <div class="absolute inset-0 z-0">
                            <img src="{{ asset('images/hero-perfumes/hmns_orgasm.jpg') }}" alt="Local Premium Collection" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 opacity-60">
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 via-transparent to-teal-900/40 z-10 pointer-events-none group-hover:opacity-80 transition-opacity duration-500"></div>
                        
                        <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8 z-20 bg-gradient-to-t from-slate-950/90 via-slate-950/50 to-transparent">
                            <span class="text-[10px] sm:text-xs font-mono text-emerald-400 tracking-wider uppercase">Pride of Origin</span>
                            <h3 class="text-2xl sm:text-3xl font-serif text-white mt-1 mb-3 sm:mb-4">Local Premium</h3>
                            <a href="{{ route('shop') }}?category[]=Local" class="inline-flex items-center text-xs sm:text-sm font-medium text-emerald-300 hover:text-white transition-colors">
                                <span>Explore</span>
                                <i class="fas fa-arrow-right ml-2 text-[10px] sm:text-xs transition-transform group-hover:translate-x-1.5"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="produk-terlaris" class="py-24 sm:py-32 bg-transparent transition-colors duration-500 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-12 sm:mb-20 reveal" data-aos="fade-up">
                <div>
                    <span class="text-[10px] sm:text-xs font-mono text-amber-600 dark:text-amber-400 uppercase tracking-widest font-semibold">This Season's Best Sellers</span>
                    <h2 class="text-3xl md:text-5xl font-serif mt-1 sm:mt-2 text-slate-950 dark:text-white transition-colors duration-500">Best Selling <span class="text-amber-500 font-normal">Products</span></h2>
                </div>
                <a href="#" onclick="showDemoAlert(event, 'Katalog Semua Produk')" class="mt-3 sm:mt-0 text-amber-600 dark:text-amber-400 text-xs sm:text-sm font-medium inline-flex items-center border-b border-amber-500/40 pb-1 hover:border-amber-500 transition-colors duration-300 group">
                    <span>See All Products</span>
                    <i class="fas fa-arrow-right ml-2 text-[10px] sm:text-xs transition-transform group-hover:translate-x-1.5"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
                @forelse($bestSellers as $product)
                @php
                    $appliedPromo = null;
                    if (isset($activePromotions) && $activePromotions->isNotEmpty()) {
                        foreach($activePromotions as $promo) {
                            if ($promo->applies_to_all || ($promo->product_id && $promo->product_id == $product->id)) {
                                $appliedPromo = $promo;
                                break;
                            }
                        }
                    }
                @endphp
                <div class="tilt-container reveal" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                    <a href="{{ route('shop') }}" class="block tilt-card glass-card rounded-2xl sm:rounded-3xl p-3 sm:p-5 border border-slate-200 dark:border-white/5 shadow-md flex flex-col justify-between h-[290px] sm:h-[370px] transition-all duration-300 group relative hover:border-amber-500/30">
                        <div class="w-full h-28 sm:h-36 overflow-hidden rounded-xl sm:rounded-2xl bg-slate-100 dark:bg-zinc-800 relative">
                            @if($product->image_url)
                                <img src="{{ asset('product_image/' . $product->image_url) }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                    <i class="fas fa-image text-3xl"></i>
                                </div>
                            @endif
                            <span class="absolute top-2 left-2 sm:top-3 sm:left-3 bg-amber-500 text-black text-[8px] sm:text-[10px] font-bold uppercase tracking-wider px-2 sm:px-3 py-1 sm:py-1.5 rounded-full shadow-lg">Best Seller</span>
                            
                            @if($appliedPromo)
                                <div class="absolute top-2 right-2 sm:top-3 sm:right-3 bg-rose-500 text-white text-[8px] sm:text-[10px] font-bold px-2 py-1 sm:px-3 sm:py-1.5 rounded-full shadow-lg z-10">
                                    @if($appliedPromo->discount_type === 'percent')
                                        {{ (float) $appliedPromo->discount_value }}% OFF
                                    @else
                                        - Rp {{ number_format((float) $appliedPromo->discount_value, 0, ',', '.') }}
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="mt-2.5 sm:mt-4 flex-grow flex flex-col justify-start">
                            <div>
                                <small class="text-[9px] sm:text-[10px] font-mono text-amber-600 dark:text-amber-400 uppercase tracking-widest font-semibold block line-clamp-1">{{ $product->brand->name ?? 'Scentify' }}</small>
                                <h3 class="text-sm sm:text-base font-serif font-bold text-slate-950 dark:text-white mt-0.5 sm:mt-1 group-hover:text-amber-500 transition-colors duration-300 line-clamp-1">{{ $product->name }}</h3>
                            </div>
                            <p class="text-[10px] sm:text-xs text-slate-500 dark:text-zinc-400 mt-1 sm:mt-2 line-clamp-2 leading-relaxed hidden sm:block">{{ $product->description }}</p>
                        </div>
                        <div class="mt-2 sm:mt-4 pt-2 sm:pt-4 border-t border-slate-200 dark:border-white/10 flex items-center justify-between">
                            <span class="text-xs sm:text-sm font-bold text-slate-950 dark:text-white">
                                Rp {{ number_format(optional($product->variants->first())->price ?? 0, 0, ',', '.') }}
                            </span>
                            <div class="p-2 sm:p-2.5 bg-slate-900 dark:bg-amber-400 text-white dark:text-black rounded-lg sm:rounded-xl hover:scale-110 transition-transform shadow-md focus:outline-none">
                                <i class="fas fa-arrow-right text-[10px] sm:text-sm"></i>
                            </div>
                        </div>
                    </a>
                </div>
                @empty
                <div class="col-span-2 lg:col-span-4 text-center py-10 text-slate-500 dark:text-zinc-400">
                    <i class="fas fa-box-open text-4xl mb-3 opacity-50"></i>
                    <p>No products sold yet.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Swiper !== 'undefined') {
                new Swiper('.hero-swiper', {
                    effect: 'cards',
                    grabCursor: true,
                    loop: true,
                    autoplay: {
                        delay: 4000,
                        disableOnInteraction: false,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                });
            }
        });
    </script>
@endsection