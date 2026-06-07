    <div id="variantModal"
        class="fixed top-[72px] bottom-0 left-0 right-0 z-[1000] flex items-center justify-center p-4 bg-black/60 backdrop-blur-md opacity-0 pointer-events-none transition-opacity duration-300">
        
        <div
            class="bg-white dark:bg-darkcard border border-slate-200 dark:border-white/5 rounded-3xl w-full max-w-3xl overflow-hidden shadow-2xl transform scale-95 transition-transform duration-300 max-h-[80vh] overflow-y-auto">
            <div class="p-6 md:p-8">
                <div class="flex justify-end mb-2">
                    <button onclick="closeVariantModal()"
                        class="w-8 h-8 rounded-full border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors focus:outline-none">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
                    <div class="md:col-span-5">
                        <div class="w-full h-64 md:h-72 overflow-hidden rounded-2xl bg-slate-100 dark:bg-zinc-900">
                            <img id="modalProductImage" src="" alt="Product"
                                class="w-full h-full object-cover transition-all duration-300">
                        </div>
                        <div id="modalGallery" class="flex gap-2 mt-3 overflow-x-auto pb-1"></div>
                    </div>

                    <div class="md:col-span-7 flex flex-col justify-between h-full">
                        <div>
                            <small id="modalProductBrand"
                                class="text-[10px] font-mono text-amber-600 dark:text-amber-400 uppercase tracking-widest font-semibold block"></small>
                            <h4 id="modalProductName"
                                class="text-2xl font-serif font-bold text-slate-950 dark:text-white mt-1"></h4>
                            <div id="modalProductPrice" class="text-xl font-bold text-amber-600 dark:text-amber-400 mt-3">
                                Rp 0</div>
                            <p id="modalProductDescription"
                                class="text-xs text-slate-500 dark:text-zinc-400 leading-relaxed mt-4 line-clamp-3"></p>

                            <h6 class="text-xs font-mono uppercase text-slate-400 tracking-wider mt-6 mb-3 font-semibold">
                                Available Sizes:</h6>
                            <div id="variantsList" class="flex flex-wrap gap-2"></div>

                            <h6 class="text-xs font-mono uppercase text-slate-400 tracking-wider mt-5 mb-3 font-semibold">
                                Quantity:</h6>
                            <div class="flex items-center gap-4">
                                <div
                                    class="flex items-center bg-slate-100 dark:bg-zinc-800/50 rounded-xl p-1 border border-slate-200 dark:border-white/5">
                                    <button type="button" onclick="decrementQuantity()"
                                        class="w-8 h-8 flex items-center justify-center text-slate-500 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-white dark:hover:bg-zinc-700 rounded-lg transition-all focus:outline-none">
                                        <i class="fas fa-minus text-[10px]"></i>
                                    </button>
                                    <input type="number" id="modalQuantity" value="1" min="1" readonly
                                        class="w-10 text-center bg-transparent text-sm font-bold text-slate-900 dark:text-white focus:outline-none appearance-none">
                                    <button type="button" onclick="incrementQuantity()"
                                        class="w-8 h-8 flex items-center justify-center text-slate-500 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-white dark:hover:bg-zinc-700 rounded-lg transition-all focus:outline-none">
                                        <i class="fas fa-plus text-[10px]"></i>
                                    </button>
                                </div>
                                <span id="modalStockStatus" class="text-xs text-slate-500 font-medium hidden"></span>
                            </div>

                            <div id="variantNotice"
                                class="mt-4 text-xs text-rose-500 flex items-center gap-1.5 font-medium">
                                <i class="fas fa-exclamation-circle"></i> Please select a variant first.
                            </div>
                        </div>

                        <div class="border-t border-slate-200 dark:border-white/5 mt-8 pt-6">
                            <h5
                                class="text-sm font-serif font-bold text-slate-950 dark:text-white mb-4 flex items-center gap-2">
                                <i class="fas fa-star text-amber-500 text-xs"></i> Customer Reviews (<span
                                    id="modalReviewCount">0</span>)
                            </h5>
                            <div id="modalReviewsList" class="space-y-3 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-8">
                            <button type="button" id="addToCartBtn" onclick="submitVariantSelection()" disabled
                                class="py-3.5 font-semibold text-[10px] sm:text-xs tracking-wider uppercase bg-slate-900 dark:bg-amber-400 text-white dark:text-black rounded-xl hover:bg-amber-500 dark:hover:bg-amber-300 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                                Add to Cart
                            </button>
                            <button type="button" id="buyNowBtn" onclick="submitBuyNowSelection()" disabled
                                class="py-3.5 font-semibold text-[10px] sm:text-xs tracking-wider uppercase bg-amber-500 text-black rounded-xl hover:bg-amber-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-md shadow-amber-500/20">
                                Buy Now
                            </button>
                            <button type="button" onclick="closeVariantModal()"
                                class="py-3.5 font-semibold text-[10px] sm:text-xs tracking-wider uppercase border border-slate-200 dark:border-white/10 text-slate-700 dark:text-zinc-300 rounded-xl hover:bg-slate-50 dark:hover:bg-zinc-800 transition-all focus:outline-none">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="hiddenCartForm" action="" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="variant_id" id="hiddenVariantId" value="">
        <input type="hidden" name="quantity" id="hiddenQuantity" value="1">
    </form>

<script>
        @php
            $promosArray = [];
            if (isset($activePromotions) && $activePromotions->isNotEmpty()) {
                foreach ($activePromotions as $p) {
                    $promosArray[] = [
                        'id' => $p->id,
                        'product_id' => $p->product_id,
                        'applies_to_all' => $p->applies_to_all,
                        'discount_type' => $p->discount_type,
                        'discount_value' => (float) $p->discount_value,
                    ];
                }
            }
        @endphp
        const activePromotionsArray = {!! json_encode($promosArray) !!};

        function getAppliedPromo(productId) {
            if (!activePromotionsArray || activePromotionsArray.length === 0) return null;
            for (let i = 0; i < activePromotionsArray.length; i++) {
                let p = activePromotionsArray[i];
                if (p.applies_to_all || (p.product_id && p.product_id == productId)) {
                    return p;
                }
            }
            return null;
        }

        function calcDiscountedPrice(originalPrice, productId) {
            const appliedPromo = getAppliedPromo(productId);
            if (!appliedPromo) return null;

            let dv = parseFloat(appliedPromo.discount_value);
            if (appliedPromo.discount_type === 'percent') {
                return Math.max(0, Math.round(originalPrice * (1 - dv / 100)));
            }
            return Math.max(0, Math.round(originalPrice - dv));
        }

        let selectedVariantId = null;
        let maxVariantStock = 0;

        document.addEventListener('click', (e) => {
            const btn = e.target.closest('.variant-selector-btn');
            if (btn) {
                e.preventDefault();
                openVariantModal(btn);
            }
        });

        function openVariantModal(btn) {
            const id = btn.getAttribute('data-product-id');
            const name = btn.getAttribute('data-product-name');
            const brand = btn.getAttribute('data-product-brand');
            const image = btn.getAttribute('data-product-image');
            const desc = btn.getAttribute('data-product-description');
            const images = JSON.parse(btn.getAttribute('data-product-images') || '[]');
            const variants = JSON.parse(btn.getAttribute('data-variants') || '[]');
            const reviews = JSON.parse(btn.getAttribute('data-reviews') || '[]');

            document.getElementById('modalProductName').textContent = name;
            document.getElementById('modalProductBrand').textContent = brand;
            document.getElementById('modalProductDescription').textContent = desc || 'No description.';
            document.getElementById('modalProductImage').src = image;
            document.getElementById('modalQuantity').value = 1;

            selectedVariantId = null;
            maxVariantStock = 0;
            document.getElementById('addToCartBtn').disabled = true;
            if(document.getElementById('buyNowBtn')) document.getElementById('buyNowBtn').disabled = true;
            document.getElementById('variantNotice').classList.remove('hidden');
            document.getElementById('modalStockStatus').classList.add('hidden');
            document.getElementById('modalProductPrice').textContent = 'Rp 0';

            const galleryContainer = document.getElementById('modalGallery');
            galleryContainer.innerHTML = '';
            const allImages = [image, ...images].filter(Boolean);
            const uniqueImages = [...new Set(allImages)];

            uniqueImages.forEach((imgUrl, idx) => {
                const thumb = document.createElement('button');
                thumb.type = 'button';
                thumb.className =
                    `w-12 h-12 rounded-xl overflow-hidden border-2 ${idx === 0 ? 'border-amber-500' : 'border-transparent'} bg-slate-100 dark:bg-zinc-900 shrink-0 transition-all`;
                thumb.innerHTML = `<img src="${imgUrl}" class="w-full h-full object-cover">`;
                thumb.addEventListener('click', () => {
                    document.getElementById('modalProductImage').src = imgUrl;
                    galleryContainer.querySelectorAll('button').forEach(b => b.classList.remove(
                        'border-amber-500'));
                    thumb.classList.add('border-amber-500');
                });
                galleryContainer.appendChild(thumb);
            });

            const variantsContainer = document.getElementById('variantsList');
            variantsContainer.innerHTML = '';

            // Sort variants by price ascending to find the cheapest
            variants.sort((a, b) => parseFloat(a.price) - parseFloat(b.price));

            let firstAvailableBtn = null;

            variants.forEach(v => {
                const vBtn = document.createElement('button');
                vBtn.type = 'button';
                const hasStock = v.stock > 0;
                vBtn.className =
                    `px-4 py-2 text-xs font-semibold font-mono border rounded-xl transition-all ${hasStock ? 'border-slate-200 dark:border-white/10 text-slate-800 dark:text-zinc-300 hover:border-amber-500' : 'border-slate-100 dark:border-zinc-800 text-slate-300 dark:text-zinc-600 line-through cursor-not-allowed'}`;
                vBtn.textContent = v.name || `${v.size}ml`;

                if (hasStock) {
                    vBtn.addEventListener('click', () => {
                        selectedVariantId = v.id;
                        maxVariantStock = v.stock;

                        variantsContainer.querySelectorAll('button').forEach(b => {
                            b.classList.remove('bg-amber-500', 'text-white', 'border-amber-500',
                                'dark:text-black');
                            b.classList.add('border-slate-200', 'dark:border-white/10',
                                'text-slate-800', 'dark:text-zinc-300');
                        });
                        vBtn.classList.remove('border-slate-200', 'dark:border-white/10', 'text-slate-800',
                            'dark:text-zinc-300');
                        vBtn.classList.add('bg-amber-500', 'text-white', 'border-amber-500',
                            'dark:text-black');

                        const discountedPrice = calcDiscountedPrice(v.price, id);
                        const priceEl = document.getElementById('modalProductPrice');
                        if (discountedPrice !== null) {
                            const fmtOrig = new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                maximumFractionDigits: 0
                            }).format(v.price);
                            const fmtDisc = new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                maximumFractionDigits: 0
                            }).format(discountedPrice);
                            priceEl.innerHTML =
                                `<span class="text-sm text-slate-400 line-through mr-2">${fmtOrig}</span><span>${fmtDisc}</span>`;
                        } else {
                            priceEl.textContent = new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                maximumFractionDigits: 0
                            }).format(v.price);
                        }

                        const stockStatus = document.getElementById('modalStockStatus');
                        stockStatus.textContent = `Stock: ${v.stock} available`;
                        stockStatus.classList.remove('hidden');

                        document.getElementById('addToCartBtn').disabled = false;
                        if(document.getElementById('buyNowBtn')) document.getElementById('buyNowBtn').disabled = false;
                        document.getElementById('variantNotice').classList.add('hidden');
                        document.getElementById('modalQuantity').value = 1;
                    });
                }
                variantsContainer.appendChild(vBtn);
                
                if (hasStock && !firstAvailableBtn) {
                    firstAvailableBtn = vBtn;
                }
            });
            
            if (firstAvailableBtn) {
                firstAvailableBtn.click();
            }

            document.getElementById('modalReviewCount').textContent = reviews.length;
            const reviewsContainer = document.getElementById('modalReviewsList');
            reviewsContainer.innerHTML = '';

            if (reviews.length === 0) {
                reviewsContainer.innerHTML =
                    `<p class="text-xs text-slate-400 dark:text-zinc-500 py-2">No reviews yet for this product.</p>`;
            } else {
                reviews.forEach(r => {
                    const rDiv = document.createElement('div');
                    rDiv.className =
                        'p-3 bg-slate-50 dark:bg-zinc-800/40 rounded-xl border border-slate-100 dark:border-white/5';
                    let starsHtml = '';
                    for (let s = 1; s <= 5; s++) {
                        starsHtml +=
                            `<i class="fas fa-star text-[9px] ${s <= r.rating ? 'text-amber-400' : 'text-slate-200 dark:text-zinc-700'}"></i>`;
                    }
                    rDiv.innerHTML = `
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-semibold text-slate-800 dark:text-zinc-200">${r.user_name}</span>
                            <span class="text-[10px] font-mono text-slate-400">${r.date}</span>
                        </div>
                        <div class="flex items-center gap-0.5 mb-1.5">${starsHtml}</div>
                        <p class="text-xs text-slate-600 dark:text-zinc-400 leading-relaxed">${r.comment || 'No comments.'}</p>
                    `;
                    reviewsContainer.appendChild(rDiv);
                });
            }

            const modal = document.getElementById('variantModal');
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.firstElementChild.classList.remove('scale-95');
            modal.firstElementChild.classList.add('scale-100');
        }

        function closeVariantModal() {
            const modal = document.getElementById('variantModal');
            modal.classList.add('opacity-0', 'pointer-events-none');
            modal.firstElementChild.classList.remove('scale-100');
            modal.firstElementChild.classList.add('scale-95');
        }

        function incrementQuantity() {
            const qtyInput = document.getElementById('modalQuantity');
            let val = parseInt(qtyInput.value) || 1;
            if (selectedVariantId && val < maxVariantStock) {
                qtyInput.value = val + 1;
            } else if (!selectedVariantId) {
                document.getElementById('variantNotice').classList.remove('hidden');
            }
        }

        function decrementQuantity() {
            const qtyInput = document.getElementById('modalQuantity');
            let val = parseInt(qtyInput.value) || 1;
            if (val > 1) {
                qtyInput.value = val - 1;
            }
        }

        function submitVariantSelection() {
            if (!selectedVariantId) {
                document.getElementById('variantNotice').classList.remove('hidden');
                return;
            }
            document.getElementById('hiddenQuantity').value = document.getElementById('modalQuantity').value;
            document.getElementById('hiddenVariantId').value = selectedVariantId;
            const hiddenForm = document.getElementById('hiddenCartForm');
            hiddenForm.action = `/cart/add/${selectedVariantId}`;
            hiddenForm.submit();
        }

        function submitBuyNowSelection() {
            if (!selectedVariantId) {
                document.getElementById('variantNotice').classList.remove('hidden');
                return;
            }
            document.getElementById('hiddenQuantity').value = document.getElementById('modalQuantity').value;
            document.getElementById('hiddenVariantId').value = selectedVariantId;
            const hiddenForm = document.getElementById('hiddenCartForm');
            hiddenForm.action = `/cart/add/${selectedVariantId}?buy_now=1`;
            hiddenForm.submit();
        }
</script>
