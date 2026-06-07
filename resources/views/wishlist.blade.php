@extends('base.base')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 min-h-screen">
    <div class="text-center mb-12 sm:mb-16 reveal">
        <div class="w-16 h-16 rounded-full bg-rose-500/10 text-rose-500 flex items-center justify-center mx-auto mb-4 border border-rose-500/20">
            <i class="fas fa-heart text-2xl"></i>
        </div>
        <span class="text-xs font-mono text-rose-500 uppercase tracking-widest font-semibold">Personal Collection</span>
        <h1 class="text-3xl md:text-5xl font-serif mt-2 text-slate-950 dark:text-white">My Wishlist</h1>
        <p class="text-xs sm:text-sm text-slate-500 dark:text-zinc-400 mt-3 max-w-lg mx-auto">
            Curated perfume selection waiting to become part of your story.
        </p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6" id="product-container">
        @forelse($wishlists as $wishlist)
            @php $product = $wishlist->product; @endphp
            @if($product)
            @php
                $basePrice = $product->variants->first()->price ?? 0;
                $appliedPromo = null;
                $discountedPrice = null;

                if (isset($activePromotions) && $activePromotions->isNotEmpty()) {
                    foreach($activePromotions as $promo) {
                        if ($promo->applies_to_all || ($promo->product_id && $promo->product_id == $product->id)) {
                            $appliedPromo = $promo;
                            break;
                        }
                    }
                    if ($appliedPromo) {
                        $dv = (float) $appliedPromo->discount_value;
                        if ($appliedPromo->discount_type === 'percent') {
                            $discountedPrice = max(0, round($basePrice * (1 - $dv / 100)));
                        } else {
                            $discountedPrice = max(0, round($basePrice - $dv));
                        }
                    }
                }
            @endphp
            <div class="perspective-1000 reveal relative" id="wishlist-item-{{ $product->id }}">
                <div class="tilt-card bg-white dark:bg-darkcard rounded-2xl sm:rounded-3xl p-3 sm:p-4 border border-slate-200 dark:border-white/5 shadow-md flex flex-col justify-between h-auto min-h-[300px] sm:min-h-[360px] transition-all duration-300 group">
                    
                    <div class="w-full h-32 sm:h-44 overflow-hidden rounded-xl sm:rounded-2xl bg-slate-100 dark:bg-zinc-900 relative">
                        <img src="{{ $product->image_url ? (strpos($product->image_url, 'http') === 0 ? $product->image_url : asset('product_image/' . $product->image_url)) : 'https://placehold.co/400x500?text=Scentify' }}"
                             alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                             
                        @if($appliedPromo)
                            <div class="absolute top-2 right-2 bg-rose-500 text-white text-[9px] sm:text-[10px] font-bold px-2 py-1 rounded-lg shadow-md z-10">
                                @if($appliedPromo->discount_type === 'percent')
                                    {{ (float) $appliedPromo->discount_value }}% OFF
                                @else
                                    - Rp {{ number_format((float) $appliedPromo->discount_value, 0, ',', '.') }}
                                @endif
                            </div>
                        @endif
                        
                    </div>

                    <div class="mt-3 flex-grow flex flex-col justify-start">
                        <div class="flex justify-between items-start gap-2">
                            <div class="flex-grow overflow-hidden">
                                <small class="text-[9px] sm:text-[10px] font-mono text-amber-600 dark:text-amber-400 uppercase tracking-widest font-semibold block">
                                    {{ $product->brand->name ?? 'Unknown Brand' }}
                                </small>
                                <h5 class="text-sm sm:text-base font-serif font-bold text-slate-900 dark:text-white mt-0.5 group-hover:text-amber-500 transition-colors line-clamp-1">
                                    {{ $product->name }}
                                </h5>
                            </div>
                            <button type="button" data-remove-wishlist="{{ $product->id }}" class="flex-shrink-0 w-7 h-7 rounded-full bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white dark:bg-rose-500/10 dark:text-rose-400 dark:hover:bg-rose-500 dark:hover:text-white flex items-center justify-center transition-all focus:outline-none mt-1" title="Remove from Wishlist">
                                <i class="fas fa-times text-[10px]"></i>
                            </button>
                        </div>
                        <p class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white mt-1">
                            @if ($discountedPrice !== null)
                                <span class="text-xs text-slate-400 line-through mr-2">Rp {{ number_format($basePrice, 0, ',', '.') }}</span>
                                <span class="text-xs text-amber-600">Rp {{ number_format($discountedPrice, 0, ',', '.') }}</span>
                            @else
                                Rp {{ number_format($basePrice, 0, ',', '.') }}
                            @endif
                        </p>
                    </div>

                    <div class="mt-3">
                        @if ($product->variants->isNotEmpty() && $product->variants->sum('stock') > 0)
                            <a href="{{ route('shop') }}" class="w-full py-2 text-[10px] sm:text-xs font-semibold tracking-wide bg-slate-900 dark:bg-amber-400 text-white dark:text-black rounded-full hover:bg-slate-800 dark:hover:bg-amber-300 transition-colors duration-300 shadow-md flex items-center justify-center gap-1.5">
                                <i class="fas fa-shopping-bag"></i> Buy Now
                            </a>
                        @else
                            <button class="w-full py-2 text-[10px] sm:text-xs font-semibold tracking-wide bg-slate-300 dark:bg-zinc-800 text-slate-500 dark:text-zinc-600 rounded-full cursor-not-allowed flex items-center justify-center gap-1.5" disabled>
                                <i class="fas fa-times-circle"></i> Stok Habis
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        @empty
            <div class="col-span-full text-center py-20 reveal">
                <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-darkcard border border-slate-200 dark:border-white/5 flex items-center justify-center mx-auto mb-4 text-slate-400">
                    <i class="far fa-heart text-xl"></i>
                </div>
                <h3 class="font-serif text-lg text-slate-900 dark:text-white">Your wishlist is empty.</h3>
                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-2">Explore our perfume collection and find your favorite scent.</p>
                <a href="{{ route('shop') }}" class="inline-block mt-6 px-6 py-3 bg-slate-900 dark:bg-amber-500 text-white dark:text-black text-xs font-bold uppercase tracking-wider rounded-xl hover:scale-105 transition-transform shadow-lg shadow-amber-500/10">
                    Start Shopping
                </a>
            </div>
        @endforelse
    </div>

    @if($wishlists->hasPages())
        <div class="mt-16 pt-8 border-t border-slate-200 dark:border-white/5 flex justify-center custom-pagination reveal">
            {{ $wishlists->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<script>
    // AJAX Logic Hapus Wishlist tanpa Reload
    function removeFromWishlist(productId) {
        const isDark = document.documentElement.classList.contains('dark');
        
        fetch(`/wishlist/toggle/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
                if (data.status === 'removed') {
                // Animasi GSAP Card menyusut dan hilang
                const card = document.getElementById(`wishlist-item-${productId}`);
                gsap.to(card, {
                    scale: 0.8,
                    opacity: 0,
                    duration: 0.3,
                    onComplete: () => {
                        card.remove(); // Buang HTML nya
                        
                        // Update angka di header
                        const badge = document.getElementById('wishlist-badge');
                        if (badge) {
                            badge.innerText = data.count;
                            if (data.count === 0) {
                                badge.classList.add('opacity-0');
                            } else {
                                badge.classList.remove('opacity-0');
                            }
                        }

                        // Jika kartu habis, refresh halaman agar gambar "Kosong" muncul
                        if (data.count === 0) {
                            window.location.reload();
                        }
                    }
                });

                // Tampilkan pesan
                Swal.fire({
                    toast: true, position: 'bottom-end', showConfirmButton: false, timer: 2000,
                    icon: 'info', title: 'Removed from Wishlist.',
                    customClass: { popup: isDark ? 'dark-swal rounded-xl' : 'rounded-xl' }
                });
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Delegate clicks to handle desktop cases where inline onclick may be blocked
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('[data-remove-wishlist]');
        if (!btn) return;
        e.preventDefault();
        const productId = btn.getAttribute('data-remove-wishlist');
        if (productId) removeFromWishlist(productId);
    });
</script>
@endsection