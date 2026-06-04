@extends('base.base')

@section('content')
<form id="hiddenBulkDeleteForm" action="" method="POST" class="hidden">
    @csrf
    @method('DELETE')
    <input type="hidden" name="cart_ids" id="hiddenCartIds">
</form>

<form id="hiddenCheckoutForm" action="{{ route('checkout') }}" method="POST" class="hidden">
    @csrf
</form>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32">
    <nav class="mb-8 reveal">
        <ol class="flex items-center space-x-2 text-xs font-mono uppercase tracking-wider text-slate-400 dark:text-zinc-500">
            <li><a href="{{ route('home') }}" class="hover:text-amber-500 transition-colors">Home</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ route('shop') }}" class="hover:text-amber-500 transition-colors">Shop</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-amber-500 font-semibold">Cart</li>
        </ol>
    </nav>

    <h1 class="text-3xl md:text-5xl font-serif mb-8 text-slate-950 dark:text-white reveal">Your Shopping Cart</h1>

    @if (count($cart) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <div class="lg:col-span-8 space-y-6 reveal">
                <div class="glass-card p-4 rounded-2xl flex items-center justify-between border border-slate-200 dark:border-white/5">
                        <label class="flex items-center group cursor-pointer text-sm font-semibold text-slate-700 dark:text-zinc-300">
                        <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)" checked
                               class="rounded border-slate-300 dark:border-zinc-700 text-amber-500 focus:ring-amber-500 bg-transparent mr-3 w-5 h-5 transition-colors cursor-pointer">
                        <span>Select All (<span id="total-items-count">{{ count($cart) }}</span> items)</span>
                    </label>
                    <button onclick="confirmBulkDelete()" class="text-xs font-mono uppercase text-slate-400 hover:text-rose-500 transition-colors flex items-center gap-1.5">
                        <i class="fas fa-trash-alt text-xs"></i> Remove Selected
                    </button>
                </div>

                <div class="glass-card rounded-3xl border border-slate-200 dark:border-white/5 shadow-lg overflow-hidden">
                    
                    <div class="hidden sm:grid grid-cols-12 gap-4 px-6 py-4 bg-slate-50 dark:bg-zinc-800/50 border-b border-slate-200 dark:border-white/5 text-[10px] font-mono uppercase tracking-widest text-slate-500 dark:text-zinc-400 font-bold items-center">
                        <div class="col-span-5 pl-2">Product</div>
                        <div class="col-span-2 text-center">Unit Price</div>
                        <div class="col-span-2 text-center">Quantity</div>
                        <div class="col-span-2 text-right">Total</div>
                        <div class="col-span-1 text-right">Actions</div>
                    </div>

                    <div class="divide-y divide-slate-200 dark:divide-white/5">
                        @foreach ($cart as $id => $item)
                            <div class="p-4 sm:px-6 sm:py-5 flex flex-col sm:grid sm:grid-cols-12 sm:gap-4 sm:items-center relative hover:bg-slate-50/50 dark:hover:bg-zinc-800/30 transition-colors duration-300">
                                
                                <div class="col-span-5 flex items-center w-full">
                                    <input type="checkbox" name="selected_items[]" value="{{ $id }}" checked
                                           data-base-price="{{ $item['price'] }}"
                                           data-price="{{ $item['price'] * $item['quantity'] }}"
                                           onchange="updateOrderSummary()"
                                           class="item-checkbox rounded border-slate-300 dark:border-zinc-700 text-amber-500 focus:ring-amber-500 bg-transparent w-5 h-5 transition-colors cursor-pointer mr-4 flex-shrink-0">

                                    <div class="flex items-center gap-4 w-full">
                                        <div class="w-16 h-16 sm:w-20 sm:h-20 overflow-hidden rounded-xl bg-slate-100 dark:bg-zinc-900 flex-shrink-0 border border-slate-200 dark:border-white/5">
                                            <img src="{{ strpos($item['image_url'], 'http') === 0 ? $item['image_url'] : asset('product_image/' . $item['image_url']) }}" 
                                                 alt="{{ $item['product_name'] }}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <small class="text-[9px] font-mono text-amber-600 dark:text-amber-400 uppercase tracking-widest font-semibold block truncate">
                                                {{ $item['brand_name'] }}
                                            </small>
                                            <h4 class="text-sm font-serif font-bold text-slate-900 dark:text-white mt-0.5 truncate">
                                                {{ $item['product_name'] }}
                                            </h4>
                                            <p class="text-[11px] text-slate-500 dark:text-zinc-400 mt-1">Size: <span class="font-semibold">{{ $item['size'] }}</span></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex sm:hidden items-center justify-between mt-4 pl-9">
                                    <div class="flex items-center bg-slate-100 dark:bg-zinc-800 rounded-lg p-0.5 border border-slate-200 dark:border-white/5">
                                        <button type="button" onclick="updateCartQuantity('{{ $id }}', -1)" class="w-7 h-7 flex items-center justify-center text-slate-500 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-white dark:hover:bg-zinc-700 rounded transition-all focus:outline-none">
                                            <i class="fas fa-minus text-[10px]"></i>
                                        </button>
                                        <input type="number" id="qty-mobile-{{ $id }}" value="{{ $item['quantity'] }}" min="1" readonly class="w-8 text-center bg-transparent text-xs font-bold text-slate-900 dark:text-white focus:outline-none appearance-none">
                                        <button type="button" onclick="updateCartQuantity('{{ $id }}', 1)" class="w-7 h-7 flex items-center justify-center text-slate-500 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-white dark:hover:bg-zinc-700 rounded transition-all focus:outline-none">
                                            <i class="fas fa-plus text-[10px]"></i>
                                        </button>
                                    </div>
                                    
                                    <div class="text-right flex-1 px-4">
                                        <span class="text-sm font-bold text-slate-900 dark:text-white" id="price-mobile-{{ $id }}">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                    </div>

                                    <form id="delete-form-mobile-{{ $id }}" action="{{ route('cart.remove', $id) }}" method="POST" class="flex-shrink-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmItemDelete('{{ $id }}', '{{ $item['product_name'] }}')" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-zinc-800 text-slate-400 hover:text-rose-500 hover:bg-rose-500/10 transition-colors flex items-center justify-center focus:outline-none">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>

                                <div class="col-span-2 hidden sm:block text-center text-sm font-medium text-slate-600 dark:text-zinc-400">
                                    @if(isset($item['original_price']) && $item['original_price'] > $item['price'])
                                        <span class="text-xs text-slate-400 line-through block">Rp {{ number_format($item['original_price'], 0, ',', '.') }}</span>
                                        <span class="text-amber-600 font-bold">Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                                    @else
                                        Rp {{ number_format($item['price'], 0, ',', '.') }}
                                    @endif
                                </div>

                                <div class="col-span-2 hidden sm:flex justify-center">
                                    <div class="flex items-center bg-slate-100 dark:bg-zinc-800 rounded-lg p-1 border border-slate-200 dark:border-white/5">
                                        <button type="button" onclick="updateCartQuantity('{{ $id }}', -1)" class="w-7 h-7 flex items-center justify-center text-slate-500 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-white dark:hover:bg-zinc-700 rounded-md transition-all focus:outline-none">
                                            <i class="fas fa-minus text-[10px]"></i>
                                        </button>
                                        <input type="number" id="qty-desktop-{{ $id }}" value="{{ $item['quantity'] }}" min="1" readonly class="w-10 text-center bg-transparent text-sm font-bold text-slate-900 dark:text-white focus:outline-none appearance-none">
                                        <button type="button" onclick="updateCartQuantity('{{ $id }}', 1)" class="w-7 h-7 flex items-center justify-center text-slate-500 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-white dark:hover:bg-zinc-700 rounded-md transition-all focus:outline-none">
                                            <i class="fas fa-plus text-[10px]"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="col-span-2 hidden sm:block text-right">
                                    <span class="text-sm font-bold text-slate-900 dark:text-white" id="total-desktop-{{ $id }}">
                                        Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                    </span>
                                </div>

                                <div class="col-span-1 hidden sm:flex justify-end">
                                    <form id="delete-form-desktop-{{ $id }}" action="{{ route('cart.remove', $id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmItemDelete('{{ $id }}', '{{ $item['product_name'] }}')" class="w-8 h-8 rounded-full text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors flex items-center justify-center focus:outline-none">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                                
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4 reveal">
                <div class="glass-card p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-white/5 shadow-2xl lg:sticky lg:top-28">
                    <h3 class="font-serif text-xl font-semibold tracking-wide mb-6 pb-4 border-b border-slate-200 dark:border-white/10">Order Summary</h3>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500 dark:text-zinc-400">Selected (<span id="selected-count">0</span> items)</span>
                            <span id="selected-subtotal" class="font-bold text-slate-900 dark:text-white">Rp 0</span>
                        </div>

                        @if($totalDiscount > 0)
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5">
                                <i class="fas fa-tag text-xs"></i> Discount
                            </span>
                            <span id="discount-amount" class="font-bold text-emerald-600 dark:text-emerald-400">- Rp {{ number_format($totalDiscount, 0, ',', '.') }}</span>
                        </div>
                        @endif

                        <div class="text-[11px] text-slate-400 dark:text-zinc-500 pb-6 border-b border-slate-200 dark:border-white/10 leading-relaxed">
                            * Shipping and taxes will be calculated in detail on the checkout page.
                        </div>

                        <div class="flex justify-between items-center pt-4">
                            <h5 class="font-serif text-lg font-bold text-slate-900 dark:text-white">Estimated Total</h5>
                            <h4 id="estimated-total" class="text-2xl font-black text-amber-600 dark:text-amber-400">Rp 0</h4>
                        </div>
                    </div>

                    <div class="mt-8 space-y-3 relative z-50">
                        <button type="button" id="checkoutBtn" onclick="submitCheckout()"
                           class="block w-full text-center py-4 font-semibold text-xs tracking-widest uppercase bg-slate-900 dark:bg-amber-400 text-white dark:text-black rounded-xl hover:bg-amber-500 dark:hover:bg-amber-300 shadow-lg active:scale-95 transition-all cursor-pointer">
                            Proceed to Checkout
                        </button>
                        
                        <a href="{{ route('shop') }}"
                           class="block w-full text-center py-4 font-semibold text-xs tracking-widest uppercase border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-slate-700 dark:text-zinc-300 rounded-xl hover:bg-white dark:hover:bg-zinc-900 hover:text-amber-500 dark:hover:text-amber-400 hover:border-amber-500 dark:hover:border-amber-400 transition-all cursor-pointer shadow-sm">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-24 reveal">
            <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-darkcard border border-slate-200 dark:border-white/5 flex items-center justify-center mx-auto mb-6 text-slate-400 shadow-inner">
                <i class="fas fa-shopping-bag text-2xl"></i>
            </div>
            <h3 class="font-serif text-xl sm:text-2xl font-bold">Your cart is empty</h3>
            <p class="text-xs sm:text-sm text-slate-400 dark:text-zinc-500 mt-2 max-w-sm mx-auto leading-relaxed">Discover your signature scent across our curated catalog.</p>
            <a href="{{ route('shop') }}" class="inline-block mt-8 px-8 py-4 font-semibold text-xs tracking-widest uppercase bg-slate-900 dark:bg-amber-400 text-white dark:text-black rounded-xl hover:bg-amber-500 dark:hover:bg-amber-300 transition-all shadow-lg shadow-amber-500/15">
                Start Shopping
            </a>
        </div>
    @endif
</div>

<style>
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>

<script>
    function updateOrderSummary() {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        let selectedCount = 0;
        let totalUnits = 0;   
        let selectedTotal = 0;

        checkboxes.forEach(chk => {
            if (chk.checked) {
                selectedCount++;
                const qtyId = chk.value;
                const qtyInput = document.getElementById('qty-desktop-' + qtyId) || document.getElementById('qty-mobile-' + qtyId);
                if (qtyInput) {
                    totalUnits += parseInt(qtyInput.value);
                }
                selectedTotal += Number(chk.dataset.price);
            }
        });

        const formattedTotal = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        }).format(selectedTotal);

        const selectedCountEl = document.getElementById('selected-count');
        const selectedSubtotalEl = document.getElementById('selected-subtotal');
        const estimatedTotalEl = document.getElementById('estimated-total');
        const checkoutBtn = document.getElementById('checkoutBtn');
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');

        if (selectedCountEl) selectedCountEl.innerText = totalUnits;
        if (selectedSubtotalEl) selectedSubtotalEl.innerText = formattedTotal;
        if (estimatedTotalEl) estimatedTotalEl.innerText = formattedTotal;

        if (selectAllCheckbox) {
            selectAllCheckbox.checked = (selectedCount > 0 && selectedCount === checkboxes.length);
        }

        if (checkoutBtn) {
            if (selectedCount === 0) {
                checkoutBtn.classList.add('opacity-50', 'pointer-events-none');
                checkoutBtn.disabled = true;
            } else {
                checkoutBtn.classList.remove('opacity-50', 'pointer-events-none');
                checkoutBtn.disabled = false;
            }
        }
    }

    function updateCartQuantity(id, change) {
        const desktopInput = document.getElementById('qty-desktop-' + id);
        const mobileInput = document.getElementById('qty-mobile-' + id);
        
        let currentQty = 1;
        if(desktopInput) currentQty = parseInt(desktopInput.value);
        else if (mobileInput) currentQty = parseInt(mobileInput.value);
        
        let newQty = currentQty + change;
        if (newQty < 1) newQty = 1;
        
        if(desktopInput) desktopInput.value = newQty;
        if(mobileInput) mobileInput.value = newQty;
        
        const checkbox = document.querySelector(`.item-checkbox[value="${id}"]`);
        if(checkbox) {
            const basePrice = Number(checkbox.dataset.basePrice);
            const newTotal = basePrice * newQty;
            
            checkbox.dataset.price = newTotal;
            
            const formattedTotal = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            }).format(newTotal);
            
            const totalDesktop = document.getElementById('total-desktop-' + id);
            const priceMobile = document.getElementById('price-mobile-' + id);
            
            if(totalDesktop) totalDesktop.innerText = formattedTotal;
            if(priceMobile) priceMobile.innerText = formattedTotal;
            
            updateOrderSummary();
        }
    }

    function toggleSelectAll(selectAll) {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        checkboxes.forEach(chk => {
            chk.checked = selectAll.checked;
        });
        updateOrderSummary();
    }

    function confirmItemDelete(cartId, productName) {
        Swal.fire({
            title: 'Remove from Cart?',
            text: `Are you sure you want to remove '${productName}' from your cart?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff2a5f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash mr-2"></i>Remove',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-[1.5rem] dark-swal shadow-2xl',
                confirmButton: 'rounded-full px-4',
                cancelButton: 'rounded-full px-4'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('delete-form-desktop-' + cartId) || document.getElementById('delete-form-mobile-' + cartId);
                if (form) form.submit();
            }
        });
    }

    function confirmBulkDelete() {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const selectedIds = [];

        checkboxes.forEach(chk => {
            if (chk.checked) {
                selectedIds.push(chk.value);
            }
        });

        if (selectedIds.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No items selected',
                text: 'Please select at least one product using the checkboxes first.',
                confirmButtonColor: '#f59e0b',
                customClass: { popup: 'rounded-[1.5rem] dark-swal shadow-2xl' }
            });
            return;
        }

        Swal.fire({
            title: 'Remove Selected Items?',
            text: `You will remove ${selectedIds.length} item(s) from your cart.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff2a5f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash mr-2"></i>Yes, Remove All!',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-[1.5rem] dark-swal shadow-2xl',
                confirmButton: 'rounded-full px-4',
                cancelButton: 'rounded-full px-4'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('hiddenBulkDeleteForm');
                document.getElementById('hiddenCartIds').value = JSON.stringify(selectedIds);
                form.action = `/cart/bulk-remove`;
                form.submit();
            }
        });
    }

    function submitCheckout() {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const checkoutForm = document.getElementById('hiddenCheckoutForm');
        
        checkoutForm.querySelectorAll('input:not([name="_token"])').forEach(el => el.remove());

        checkboxes.forEach(chk => {
            if (chk.checked) {
                const id = chk.value;
                const qtyInput = document.getElementById('qty-desktop-' + id) || document.getElementById('qty-mobile-' + id);
                const qty = qtyInput ? qtyInput.value : 1;

                const inputId = document.createElement('input');
                inputId.type = 'hidden';
                inputId.name = 'checkout_items[]';
                inputId.value = id;
                
                const inputQty = document.createElement('input');
                inputQty.type = 'hidden';
                inputQty.name = `quantities[${id}]`;
                inputQty.value = qty;

                checkoutForm.appendChild(inputId);
                checkoutForm.appendChild(inputQty);
            }
        });
        
        checkoutForm.submit();
    }

    document.addEventListener('DOMContentLoaded', updateOrderSummary);
</script>
@endsection