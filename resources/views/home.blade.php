@extends('base.base')

@section('content')
    <!-- Hero Section -->
    <section id="home" class="min-h-screen flex items-center justify-center relative px-4 sm:px-6 overflow-hidden pt-28 lg:pt-16">
        <!-- Interactive Fluid Glowing Orbs -->
        <div id="glow-orb-1" class="absolute top-[15%] left-[10%] w-[250px] h-[250px] sm:w-[350px] sm:h-[350px] bg-amber-500/20 dark:bg-amber-500/10 rounded-full ambient-glow-orb pointer-events-none"></div>
        <div id="glow-orb-2" class="absolute bottom-[15%] right-[10%] w-[300px] h-[300px] sm:w-[400px] sm:h-[400px] bg-purple-500/10 dark:bg-purple-900/5 rounded-full ambient-glow-orb pointer-events-none"></div>

        <!-- Garis Grid Latar Belakang -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:30px_30px] pointer-events-none opacity-60"></div>

        <div class="max-w-7xl w-full flex flex-col lg:flex-row items-center gap-12 lg:gap-16 z-10">
            <!-- Left Side Text (GSAP Animated) -->
            <div class="flex-1 text-left sm:text-center lg:text-left hero-text-container will-animate w-full">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/20 mb-4 sm:mb-6">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 pulse-ring"></span>
                    <span class="text-[9px] sm:text-[10px] font-mono uppercase tracking-widest text-amber-600 dark:text-amber-400 font-semibold">Luxury Olfactory Experience</span>
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-7xl font-serif mb-4 sm:mb-6 leading-[1.15] tracking-tight text-slate-950 dark:text-white">
                    Seni Ekspresi <br>
                    <span class="text-gradient font-bold">Aroma Mewah</span>
                </h1>
                <p class="text-slate-600 dark:text-zinc-400 text-sm sm:text-base lg:text-lg mb-8 sm:mb-10 leading-relaxed max-w-lg mx-auto lg:mx-0">
                    Memperkenalkan kurasi parfum desainer dunia, aroma niche yang kompleks, dan racikan lokal berkualitas premium demi aura unik Anda.
                </p>
                <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-3 sm:gap-4 w-full sm:w-auto">
                    <a href="#scent-explorer" class="btn-glow px-6 sm:px-8 py-3.5 sm:py-4 text-sm sm:text-base text-center rounded-xl font-semibold tracking-wide bg-slate-950 dark:bg-amber-500 text-white dark:text-black shadow-lg hover:shadow-amber-500/20 transition-all duration-300 transform hover:-translate-y-1">
                        Cari Aroma Anda ✨
                    </a>
                    <a href="#koleksi" class="px-6 sm:px-8 py-3.5 sm:py-4 text-sm sm:text-base text-center rounded-xl font-semibold border border-slate-200 dark:border-zinc-800 text-slate-800 dark:text-zinc-300 hover:border-amber-500 dark:hover:border-amber-400 hover:text-amber-600 dark:hover:text-amber-400 transition-all duration-300 transform hover:-translate-y-1">
                        Lihat Koleksi
                    </a>
                </div>
            </div>
            
            <!-- Right Side Interactive 3D CSS Perfume Bottle Frame -->
            <div class="flex-1 flex justify-center relative hero-bottle-container will-animate w-full">
                <!-- Ukuran div botol disesuaikan agar tidak meluap di HP -->
                <div class="w-64 h-64 sm:w-80 sm:h-80 lg:w-96 lg:h-96 relative group tilt-container">
                    <div class="absolute inset-0 rounded-full bg-gradient-to-tr from-amber-500/20 to-purple-600/20 blur-3xl opacity-40"></div>
                    
                    <div class="tilt-card absolute inset-4 glass-card rounded-[2rem] flex flex-col items-center justify-center border border-white/10 overflow-hidden shadow-2xl p-6 sm:p-8 cursor-pointer">
                        <!-- Luxury SVG 3D-Bending Bottle -->
                        <div id="perfume-3d-visual" class="relative transition-transform duration-300 ease-out transform group-hover:scale-105 group-hover:rotate-1">
                            <svg class="w-40 h-48 sm:w-48 sm:h-56 text-amber-500/80 transition-all duration-500" viewBox="0 0 100 120" fill="currentColor">
                                <!-- Cap -->
                                <rect x="42" y="10" width="16" height="15" rx="3" stroke="currentColor" stroke-width="2" fill="none" class="text-amber-600 dark:text-amber-400"/>
                                <line x1="50" y1="25" x2="50" y2="35" stroke="currentColor" stroke-width="2" class="opacity-50"/>
                                <!-- Collar -->
                                <rect x="36" y="32" width="28" height="6" rx="1.5" fill="currentColor"/>
                                <!-- Bottle -->
                                <path d="M22 42 C22 39, 25 36, 29 36 L71 36 C75 36, 78 39, 78 42 L78 105 C78 109, 75 112, 71 112 L29 112 C25 112, 22 109, 22 105 Z" stroke="currentColor" stroke-width="2.5" fill="none" class="text-slate-900 dark:text-white"/>
                                <!-- Interactive Liquid level -->
                                <path id="liquid-wave" d="M24 60 Q50 63, 76 60 L75 109 C75 110.5, 73.5 110.5, 71 110.5 L29 110.5 C26.5 110.5, 25 110.5, 25 109 Z" fill="currentColor" class="text-amber-500/40 transition-all duration-1000"/>
                                <!-- Label Plate -->
                                <rect x="34" y="62" width="32" height="24" rx="3" fill="none" stroke="currentColor" stroke-width="1.5" class="opacity-80"/>
                                <text x="50" y="76" font-size="7" font-family="Playfair Display" font-weight="bold" text-anchor="middle" fill="currentColor" class="text-slate-900 dark:text-white">SCENTIFY</text>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- NEW INTERACTIVE MODULE: Interactive Scent Wheel & Finder Quiz -->
    <section id="scent-explorer" class="py-24 sm:py-32 bg-slate-50 dark:bg-[#08080a] transition-colors duration-500 relative overflow-hidden">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 relative z-10">
            <div class="text-center mb-12 sm:mb-20">
                <span class="text-[10px] sm:text-xs font-mono text-amber-600 dark:text-amber-400 uppercase tracking-widest font-semibold">Interactive Scent Finder</span>
                <h2 class="text-3xl md:text-5xl font-serif mt-2 text-slate-950 dark:text-white">Temukan <span class="italic text-amber-500 font-normal">Karakter Aroma</span> Anda</h2>
                <div class="w-12 sm:w-16 h-[2px] bg-amber-500 mx-auto mt-4 sm:mt-6 rounded-full"></div>
            </div>

            <!-- Quiz & Interactive Note Selector Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                <!-- Scent Profiler Interactive Options -->
                <div class="lg:col-span-5 flex flex-col justify-between gap-6">
                    <div class="glass-card p-5 sm:p-6 rounded-2xl sm:rounded-3xl">
                        <h3 class="text-base sm:text-lg font-bold mb-3 sm:mb-4 flex items-center gap-2">
                            <i class="fas fa-sliders text-amber-500"></i> Pilih Mood Utama Anda:
                        </h3>
                        <p class="text-[11px] sm:text-xs text-slate-500 dark:text-zinc-400 mb-5 sm:mb-6 leading-relaxed">Pilih salah satu mood di bawah ini untuk melihat adaptasi warna suasana dan aroma rekomendasi kami.</p>
                        
                        <div class="space-y-2 sm:space-y-3">
                            <button onclick="setScentMood('woody')" id="mood-woody" class="w-full text-left p-3.5 sm:p-4 rounded-xl border border-amber-500/30 bg-amber-500/10 text-amber-500 transition-all duration-300 font-medium text-[11px] sm:text-sm flex justify-between items-center group">
                                <span>🌲 Woody & Earthy (Hangat, Maskulin)</span>
                                <i class="fas fa-chevron-right text-xs group-hover:translate-x-1.5 transition-transform"></i>
                            </button>
                            <button onclick="setScentMood('floral')" id="mood-floral" class="w-full text-left p-3.5 sm:p-4 rounded-xl border border-slate-200 dark:border-white/5 hover:border-pink-400/50 hover:bg-pink-500/5 text-slate-700 dark:text-zinc-300 transition-all duration-300 font-medium text-[11px] sm:text-sm flex justify-between items-center group">
                                <span>🌸 Floral & Powdery (Manis, Feminin)</span>
                                <i class="fas fa-chevron-right text-xs group-hover:translate-x-1.5 transition-transform"></i>
                            </button>
                            <button onclick="setScentMood('citrus')" id="mood-citrus" class="w-full text-left p-3.5 sm:p-4 rounded-xl border border-slate-200 dark:border-white/5 hover:border-emerald-400/50 hover:bg-emerald-500/5 text-slate-700 dark:text-zinc-300 transition-all duration-300 font-medium text-[11px] sm:text-sm flex justify-between items-center group">
                                <span>🍋 Citrus & Fresh (Enerjik, Sporty)</span>
                                <i class="fas fa-chevron-right text-xs group-hover:translate-x-1.5 transition-transform"></i>
                            </button>
                            <button onclick="setScentMood('oriental')" id="mood-oriental" class="w-full text-left p-3.5 sm:p-4 rounded-xl border border-slate-200 dark:border-white/5 hover:border-purple-400/50 hover:bg-purple-500/5 text-slate-700 dark:text-zinc-300 transition-all duration-300 font-medium text-[11px] sm:text-sm flex justify-between items-center group">
                                <span>🔮 Oriental & Spice (Misterius, Eksotis)</span>
                                <i class="fas fa-chevron-right text-xs group-hover:translate-x-1.5 transition-transform"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Results Area (GSAP Interchanged) -->
                <div class="lg:col-span-7">
                    <div id="scent-result-card" class="glass-card p-6 sm:p-8 rounded-2xl sm:rounded-3xl h-full flex flex-col justify-between border border-amber-500/20 shadow-xl shadow-amber-500/5 relative overflow-hidden" style="z-index: 1;">
                        <!-- Sparkles Ambient Background inside card -->
                        <div id="scent-card-ambient" class="absolute -top-10 -right-10 w-32 h-32 sm:w-48 sm:h-48 bg-amber-500/20 rounded-full blur-3xl pointer-events-none transition-all duration-500" style="z-index: 0;"></div>
                        
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-4 sm:mb-6">
                                <span class="text-[9px] sm:text-xs font-mono uppercase text-amber-500 font-bold" id="scent-badge">Woody Recommendation</span>
                                <span class="text-[10px] sm:text-xs text-slate-500 font-semibold" id="scent-compatibility">Match: 98%</span>
                            </div>

                            <h3 class="text-2xl sm:text-3xl font-serif mb-2 sm:mb-4 text-slate-950 dark:text-white transition-colors" id="scent-title">Golden Amber</h3>
                            <p class="text-xs sm:text-sm text-slate-600 dark:text-zinc-400 leading-relaxed mb-6" id="scent-desc">
                                Sempurna untuk mereka yang menyukai aroma alam yang dalam. Memancarkan aura kebijaksanaan, kehangatan yang bersahaja, serta impresi ketenangan berkelas berkat racikan cedarwood, vetiver, dan premium amber.
                            </p>

                            <!-- Interactive pyramid chart -->
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
                                <span class="text-[10px] sm:text-xs text-slate-500 block">Mulai Dari</span>
                                <span class="text-xl sm:text-2xl font-bold" id="scent-price">Rp 425.000</span>
                            </div>
                            <button onclick="addToCart()" class="w-full sm:w-auto px-6 py-3 sm:py-3.5 bg-slate-950 dark:bg-amber-400 text-white dark:text-black rounded-xl text-sm font-semibold shadow-lg hover:scale-105 active:scale-95 transition-all duration-300">
                                <i class="fas fa-cart-plus mr-2"></i>Tambah
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section id="koleksi" class="py-24 sm:py-32 bg-white dark:bg-darkbg transition-colors duration-500 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 sm:mb-20 reveal">
                <span class="text-[10px] sm:text-xs font-mono text-amber-600 dark:text-amber-400 uppercase tracking-widest font-semibold">Kategori Premium</span>
                <h2 class="text-3xl md:text-5xl font-serif mt-2 text-slate-950 dark:text-white transition-colors duration-500">Pilihan <span class="italic text-amber-500 font-normal">Koleksi</span></h2>
                <div class="w-12 sm:w-16 h-[2px] bg-amber-500 mx-auto mt-4 sm:mt-6 rounded-full"></div>
            </div>
            
            <!-- Grid menyesuaikan agar kartu tidak raksasa di HP -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-10">
                <!-- Designer Card -->
                <div class="tilt-container reveal max-w-sm mx-auto sm:max-w-none w-full">
                    <div class="tilt-card glass-card relative overflow-hidden rounded-2xl sm:rounded-3xl aspect-[4/5] sm:aspect-[3/4] shadow-xl sm:shadow-2xl group border border-slate-200 dark:border-white/5">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/40 to-transparent z-10 pointer-events-none"></div>
                        <img src="https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&q=80&w=600" alt="[Gambar Parfum Designer]" class="w-full h-full object-cover object-center transition-transform duration-[1.2s] cubic-bezier(0.25, 1, 0.5, 1) group-hover:scale-110">
                        
                        <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8 z-20">
                            <span class="text-[10px] sm:text-xs font-mono text-amber-400 tracking-wider uppercase">Signature Line</span>
                            <h3 class="text-2xl sm:text-3xl font-serif text-white mt-1 mb-3 sm:mb-4">Designer</h3>
                            <a href="#" onclick="showDemoAlert(event, 'Koleksi Designer')" class="inline-flex items-center text-xs sm:text-sm font-medium text-amber-300 hover:text-white transition-colors">
                                <span>Jelajahi</span>
                                <i class="fas fa-arrow-right ml-2 text-[10px] sm:text-xs transition-transform group-hover:translate-x-1.5"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Niche Card -->
                <div class="tilt-container reveal max-w-sm mx-auto sm:max-w-none w-full">
                    <div class="tilt-card glass-card relative overflow-hidden rounded-2xl sm:rounded-3xl aspect-[4/5] sm:aspect-[3/4] shadow-xl sm:shadow-2xl group border border-slate-200 dark:border-white/5">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/40 to-transparent z-10 pointer-events-none"></div>
                        <img src="https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&q=80&w=600" alt="[Gambar Parfum Niche]" class="w-full h-full object-cover object-center transition-transform duration-[1.2s] cubic-bezier(0.25, 1, 0.5, 1) group-hover:scale-110">
                        
                        <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8 z-20">
                            <span class="text-[10px] sm:text-xs font-mono text-amber-400 tracking-wider uppercase">Artisanal Blends</span>
                            <h3 class="text-2xl sm:text-3xl font-serif text-white mt-1 mb-3 sm:mb-4">Niche</h3>
                            <a href="#" onclick="showDemoAlert(event, 'Koleksi Niche')" class="inline-flex items-center text-xs sm:text-sm font-medium text-amber-300 hover:text-white transition-colors">
                                <span>Jelajahi</span>
                                <i class="fas fa-arrow-right ml-2 text-[10px] sm:text-xs transition-transform group-hover:translate-x-1.5"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Lokal Premium Card -->
                <div class="tilt-container reveal max-w-sm mx-auto sm:max-w-none w-full sm:col-span-2 lg:col-span-1">
                    <div class="tilt-card glass-card relative overflow-hidden rounded-2xl sm:rounded-3xl aspect-[4/5] sm:aspect-[3/4] shadow-xl sm:shadow-2xl group border border-slate-200 dark:border-white/5">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/40 to-transparent z-10 pointer-events-none"></div>
                        <img src="https://images.unsplash.com/photo-1592914610354-fd354d45e5b0?auto=format&fit=crop&q=80&w=600" alt="[Gambar Parfum Lokal Premium]" class="w-full h-full object-cover object-center transition-transform duration-[1.2s] cubic-bezier(0.25, 1, 0.5, 1) group-hover:scale-110">
                        
                        <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8 z-20">
                            <span class="text-[10px] sm:text-xs font-mono text-amber-400 tracking-wider uppercase">Pride of Origin</span>
                            <h3 class="text-2xl sm:text-3xl font-serif text-white mt-1 mb-3 sm:mb-4">Lokal Premium</h3>
                            <a href="#" onclick="showDemoAlert(event, 'Koleksi Lokal Premium')" class="inline-flex items-center text-xs sm:text-sm font-medium text-amber-300 hover:text-white transition-colors">
                                <span>Jelajahi</span>
                                <i class="fas fa-arrow-right ml-2 text-[10px] sm:text-xs transition-transform group-hover:translate-x-1.5"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section id="produk-terlaris" class="py-24 sm:py-32 bg-slate-50 dark:bg-darkbg transition-colors duration-500 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-12 sm:mb-20 reveal">
                <div>
                    <span class="text-[10px] sm:text-xs font-mono text-amber-600 dark:text-amber-400 uppercase tracking-widest font-semibold">Terlaris Musim Ini</span>
                    <h2 class="text-3xl md:text-5xl font-serif mt-1 sm:mt-2 text-slate-950 dark:text-white transition-colors duration-500">Produk <span class="italic text-amber-500 font-normal">Terlaris</span></h2>
                </div>
                <a href="#" onclick="showDemoAlert(event, 'Katalog Semua Produk')" class="mt-3 sm:mt-0 text-amber-600 dark:text-amber-400 text-xs sm:text-sm font-medium inline-flex items-center border-b border-amber-500/40 pb-1 hover:border-amber-500 transition-colors duration-300 group">
                    <span>Lihat Semua Produk</span>
                    <i class="fas fa-arrow-right ml-2 text-[10px] sm:text-xs transition-transform group-hover:translate-x-1.5"></i>
                </a>
            </div>
            
            <!-- Grid 2-Kolom di HP agar sejalan dengan Halaman Shop -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
                <!-- Produk Terlaris 1 -->
                <div class="tilt-container reveal">
                    <div class="tilt-card bg-white dark:bg-darkcard rounded-2xl sm:rounded-3xl p-3 sm:p-5 border border-slate-200 dark:border-white/5 shadow-md flex flex-col justify-between h-[290px] sm:h-[370px] transition-all duration-300 group relative">
                        <div class="w-full h-28 sm:h-36 overflow-hidden rounded-xl sm:rounded-2xl bg-slate-100 dark:bg-zinc-800 relative">
                            <img src="https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&q=80&w=600" alt="[Scentify Parfum Amber]" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <span class="absolute top-2 left-2 sm:top-3 sm:left-3 bg-amber-500 text-black text-[8px] sm:text-[10px] font-bold uppercase tracking-wider px-2 sm:px-3 py-1 sm:py-1.5 rounded-full shadow-lg">Terlaris</span>
                        </div>
                        <div class="mt-2.5 sm:mt-4 flex-grow flex flex-col justify-start">
                            <div>
                                <small class="text-[9px] sm:text-[10px] font-mono text-amber-600 dark:text-amber-400 uppercase tracking-widest font-semibold block line-clamp-1">Scentify Signature</small>
                                <h3 class="text-sm sm:text-base font-serif font-bold text-slate-950 dark:text-white mt-0.5 sm:mt-1 group-hover:text-amber-500 transition-colors duration-300 line-clamp-1">Golden Amber</h3>
                            </div>
                            <p class="text-[10px] sm:text-xs text-slate-500 dark:text-zinc-400 mt-1 sm:mt-2 line-clamp-2 leading-relaxed hidden sm:block">Perpaduan aroma kayu cedar hangat dengan kelembutan eksotis amber organik mewah.</p>
                        </div>
                        <div class="mt-2 sm:mt-4 pt-2 sm:pt-4 border-t border-slate-200 dark:border-white/10 flex items-center justify-between">
                            <span class="text-xs sm:text-sm font-bold text-slate-950 dark:text-white">Rp 425.000</span>
                            <button onclick="addToCart()" class="p-2 sm:p-2.5 bg-slate-900 dark:bg-amber-400 text-white dark:text-black rounded-lg sm:rounded-xl hover:scale-110 transition-transform shadow-md focus:outline-none">
                                <i class="fas fa-cart-plus text-[10px] sm:text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Produk Terlaris 2 -->
                <div class="tilt-container reveal">
                    <div class="tilt-card bg-white dark:bg-darkcard rounded-2xl sm:rounded-3xl p-3 sm:p-5 border border-slate-200 dark:border-white/5 shadow-md flex flex-col justify-between h-[290px] sm:h-[370px] transition-all duration-300 group relative">
                        <div class="w-full h-28 sm:h-36 overflow-hidden rounded-xl sm:rounded-2xl bg-slate-100 dark:bg-zinc-800 relative">
                            <img src="https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&q=80&w=600" alt="[Scentify Parfum Royale]" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        </div>
                        <div class="mt-2.5 sm:mt-4 flex-grow flex flex-col justify-start">
                            <div>
                                <small class="text-[9px] sm:text-[10px] font-mono text-amber-600 dark:text-amber-400 uppercase tracking-widest font-semibold block line-clamp-1">Niche Luxury</small>
                                <h3 class="text-sm sm:text-base font-serif font-bold text-slate-950 dark:text-white mt-0.5 sm:mt-1 group-hover:text-amber-500 transition-colors duration-300 line-clamp-1">Oud Royale</h3>
                            </div>
                            <p class="text-[10px] sm:text-xs text-slate-500 dark:text-zinc-400 mt-1 sm:mt-2 line-clamp-2 leading-relaxed hidden sm:block">Aroma gaharu Timur Tengah intens dipadukan lembutnya vanila madu alami.</p>
                        </div>
                        <div class="mt-2 sm:mt-4 pt-2 sm:pt-4 border-t border-slate-200 dark:border-white/10 flex items-center justify-between">
                            <span class="text-xs sm:text-sm font-bold text-slate-950 dark:text-white">Rp 850.000</span>
                            <button onclick="addToCart()" class="p-2 sm:p-2.5 bg-slate-900 dark:bg-amber-400 text-white dark:text-black rounded-lg sm:rounded-xl hover:scale-110 transition-transform shadow-md focus:outline-none">
                                <i class="fas fa-cart-plus text-[10px] sm:text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Produk Terlaris 3 -->
                <div class="tilt-container reveal">
                    <div class="tilt-card bg-white dark:bg-darkcard rounded-2xl sm:rounded-3xl p-3 sm:p-5 border border-slate-200 dark:border-white/5 shadow-md flex flex-col justify-between h-[290px] sm:h-[370px] transition-all duration-300 group relative">
                        <div class="w-full h-28 sm:h-36 overflow-hidden rounded-xl sm:rounded-2xl bg-slate-100 dark:bg-zinc-800 relative">
                            <img src="https://images.unsplash.com/photo-1592914610354-fd354d45e5b0?auto=format&fit=crop&q=80&w=600" alt="[Scentify Parfum Rose]" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        </div>
                        <div class="mt-2.5 sm:mt-4 flex-grow flex flex-col justify-start">
                            <div>
                                <small class="text-[9px] sm:text-[10px] font-mono text-amber-600 dark:text-amber-400 uppercase tracking-widest font-semibold block line-clamp-1">Lokal Premium</small>
                                <h3 class="text-sm sm:text-base font-serif font-bold text-slate-950 dark:text-white mt-0.5 sm:mt-1 group-hover:text-amber-500 transition-colors duration-300 line-clamp-1">Velvet Rose</h3>
                            </div>
                            <p class="text-[10px] sm:text-xs text-slate-500 dark:text-zinc-400 mt-1 sm:mt-2 line-clamp-2 leading-relaxed hidden sm:block">Sentuhan mawar segar pegunungan vulkanis berpadu kelembutan musk putih.</p>
                        </div>
                        <div class="mt-2 sm:mt-4 pt-2 sm:pt-4 border-t border-slate-200 dark:border-white/10 flex items-center justify-between">
                            <span class="text-xs sm:text-sm font-bold text-slate-950 dark:text-white">Rp 295.000</span>
                            <button onclick="addToCart()" class="p-2 sm:p-2.5 bg-slate-900 dark:bg-amber-400 text-white dark:text-black rounded-lg sm:rounded-xl hover:scale-110 transition-transform shadow-md focus:outline-none">
                                <i class="fas fa-cart-plus text-[10px] sm:text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Produk Terlaris 4 -->
                <div class="tilt-container reveal">
                    <div class="tilt-card bg-white dark:bg-darkcard rounded-2xl sm:rounded-3xl p-3 sm:p-5 border border-slate-200 dark:border-white/5 shadow-md flex flex-col justify-between h-[290px] sm:h-[370px] transition-all duration-300 group relative">
                        <div class="w-full h-28 sm:h-36 overflow-hidden rounded-xl sm:rounded-2xl bg-slate-100 dark:bg-zinc-800 relative">
                            <img src="https://images.unsplash.com/photo-1523293182086-7651a899d37f?auto=format&fit=crop&q=80&w=600" alt="[Scentify Parfum Ocean]" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        </div>
                        <div class="mt-2.5 sm:mt-4 flex-grow flex flex-col justify-start">
                            <div>
                                <small class="text-[9px] sm:text-[10px] font-mono text-amber-600 dark:text-amber-400 uppercase tracking-widest font-semibold block line-clamp-1">Scentify Signature</small>
                                <h3 class="text-sm sm:text-base font-serif font-bold text-slate-950 dark:text-white mt-0.5 sm:mt-1 group-hover:text-amber-500 transition-colors duration-300 line-clamp-1">Ocean Breeze</h3>
                            </div>
                            <p class="text-[10px] sm:text-xs text-slate-500 dark:text-zinc-400 mt-1 sm:mt-2 line-clamp-2 leading-relaxed hidden sm:block">Aroma kesegaran laut lepas dipadukan dengan citrus lemon yang menyegarkan.</p>
                        </div>
                        <div class="mt-2 sm:mt-4 pt-2 sm:pt-4 border-t border-slate-200 dark:border-white/10 flex items-center justify-between">
                            <span class="text-xs sm:text-sm font-bold text-slate-950 dark:text-white">Rp 375.000</span>
                            <button onclick="addToCart()" class="p-2 sm:p-2.5 bg-slate-900 dark:bg-amber-400 text-white dark:text-black rounded-lg sm:rounded-xl hover:scale-110 transition-transform shadow-md focus:outline-none">
                                <i class="fas fa-cart-plus text-[10px] sm:text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Hubungi / Chat Section -->
    <section id="contact" class="py-24 sm:py-32 bg-slate-50 dark:bg-zinc-900/40 transition-colors duration-500">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center reveal">
            <span class="text-[10px] sm:text-xs font-mono text-amber-600 dark:text-amber-400 uppercase tracking-widest font-semibold">Bespoke Consultations</span>
            <h2 class="text-3xl md:text-5xl font-serif mt-2 mb-4 sm:mb-6 text-slate-950 dark:text-white transition-colors duration-500">Konsultasikan Aroma <span class="italic text-amber-500 font-normal">Khas Anda</span></h2>
            <p class="text-slate-600 dark:text-zinc-400 mb-8 sm:mb-10 max-w-lg mx-auto text-sm sm:text-base leading-relaxed transition-colors duration-500">
                Hubungi tim kurator parfum kami untuk merumuskan aroma eksklusif pribadi Anda dan ciptakan sillage legendaris Anda sendiri.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4 sm:gap-6">
                <a href="#" onclick="showDemoAlert(event, 'Konsultasi WhatsApp')" class="w-full sm:w-auto px-8 py-3.5 sm:py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold transition-all duration-300 hover:-translate-y-1 inline-flex items-center justify-center gap-3 shadow-lg hover:shadow-emerald-600/20 text-sm">
                    <i class="fab fa-whatsapp text-lg"></i> Chat WhatsApp
                </a>
                <a href="#" onclick="showDemoAlert(event, 'Surat Resmi')" class="w-full sm:w-auto px-8 py-3.5 sm:py-4 bg-slate-900 dark:bg-amber-400 text-white dark:text-black rounded-xl font-semibold transition-all duration-300 hover:-translate-y-1 inline-flex items-center justify-center gap-3 shadow-lg hover:shadow-amber-500/20 text-sm">
                    <i class="far fa-envelope text-lg"></i> Hubungi via Email
                </a>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="relative py-24 sm:py-28 bg-slate-950 dark:bg-[#0c0c0e] text-white overflow-hidden transition-colors duration-500 border-t border-slate-800 dark:border-white/5">
        <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-full max-w-[600px] h-[300px] bg-amber-500/10 rounded-full blur-[120px] pointer-events-none"></div>

        <div class="relative z-10 max-w-4xl mx-auto px-4 text-center reveal">
            <h3 class="text-3xl md:text-4xl font-serif mb-3 sm:mb-4">Bergabunglah dalam <span class="text-amber-400 font-normal italic">Scentify Circle</span></h3>
            <p class="text-slate-300 dark:text-zinc-400 max-w-lg mx-auto mb-8 leading-relaxed text-sm sm:text-base transition-colors duration-500">
                Dapatkan penawaran istimewa, rilis parfum *limited edition*, dan diskon keanggotaan 10% untuk pesanan pertama Anda.
            </p>
            
            <div class="max-w-md mx-auto">
                <form onsubmit="subscribeNewsletter(event)" class="flex flex-col sm:flex-row gap-3 bg-white/5 p-2 rounded-2xl border border-white/10 backdrop-blur-md">
                    <input type="email" required placeholder="Alamat Email Anda" class="w-full px-4 sm:px-5 py-3 sm:py-4 bg-transparent text-white placeholder-gray-400 focus:outline-none transition-all duration-300 text-sm text-center sm:text-left">
                    <button type="submit" class="w-full sm:w-auto px-6 sm:px-8 py-3.5 sm:py-4 font-semibold text-slate-900 bg-amber-400 rounded-xl transition-all duration-300 hover:bg-amber-300 active:scale-95 whitespace-nowrap shadow-lg text-sm">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection