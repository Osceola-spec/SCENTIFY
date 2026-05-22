@extends('base.base')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32">
    <!-- Breadcrumb -->
    <nav class="mb-8 reveal">
        <ol class="flex items-center space-x-2 text-xs font-mono uppercase tracking-wider text-slate-400 dark:text-zinc-500">
            <li><a href="{{ route('home') }}" class="hover:text-amber-500 transition-colors">Home</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ route('shop') }}" class="hover:text-amber-500 transition-colors">Shop</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-amber-500 font-semibold">Keranjang</li>
        </ol>
    </nav>

    <h1 class="text-3xl md:text-5xl font-serif mb-8 text-slate-950 dark:text-white reveal">Keranjang Belanja Anda</h1>

    @if (count($cart) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Daftar Item Keranjang (Col 8) -->
            <div class="lg:col-span-8 space-y-6 reveal">
                <!-- Checkbox Pilih Semua -->
                <div class="glass-card p-4 rounded-2xl flex items-center justify-between border border-slate-200 dark:border-white/5">
                    <label class="flex items-center group cursor-pointer text-sm font-semibold text-slate-700 dark:text-zinc-300">
                        <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)" checked
                               class="rounded border-slate-300 dark:border-zinc-700 text-amber-500 focus:ring-amber-500 bg-transparent mr-3 w-5 h-5 transition-colors cursor-pointer">
                        <span>Pilih Semua (<span id="total-items-count">{{ count($cart) }}</span> item)</span>
                    </label>
                    <button onclick="confirmBulkDelete()" class="text-xs font-mono uppercase text-slate-400 hover:text-rose-500 transition-colors flex items-center gap-1.5">
                        <i class="fas fa-trash-alt text-xs"></i> Hapus Terpilih
                    </button>
                </div>

                <!-- List Produk Keranjang -->
                <div class="space-y-4">
                    @foreach ($cart as $id => $item)
                        <div class="glass-card p-4 sm:p-6 rounded-3xl border border-slate-200 dark:border-white/5 shadow-lg flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 relative group hover:border-amber-500/30 transition-all duration-300">
                            
                            <div class="flex items-center w-full sm:w-auto">
                                <!-- Checkbox Samping Produk -->
                                <input type="checkbox" name="selected_items[]" value="{{ $id }}" checked
                                       data-base-price="{{ $item['price'] }}"
                                       data-price="{{ $item['price'] * $item['quantity'] }}"
                                       onchange="updateOrderSummary()"
                                       class="item-checkbox rounded border-slate-300 dark:border-zinc-700 text-amber-500 focus:ring-amber-500 bg-transparent w-5 h-5 transition-colors cursor-pointer mr-3 sm:mr-4 flex-shrink-0">

                                <!-- Detail Produk -->
                                <div class="flex-grow flex items-center gap-4">
                                    <div class="w-20 h-20 sm:w-24 sm:h-24 overflow-hidden rounded-2xl bg-slate-100 dark:bg-zinc-900 flex-shrink-0">
                                        <img src="{{ strpos($item['image_url'], 'http') === 0 ? $item['image_url'] : asset('product_image/' . $item['image_url']) }}" 
                                             alt="{{ $item['product_name'] }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <small class="text-[9px] sm:text-[10px] font-mono text-amber-600 dark:text-amber-400 uppercase tracking-widest font-semibold block">
                                            {{ $item['brand_name'] }}
                                        </small>
                                        <h4 class="text-sm sm:text-base font-serif font-bold text-slate-900 dark:text-white mt-0.5 line-clamp-1">
                                            {{ $item['product_name'] }}
                                        </h4>
                                        <p class="text-xs text-slate-400 dark:text-zinc-500 mt-1">Ukuran: <span class="font-semibold">{{ $item['size'] }}</span></p>
                                        
                                        <!-- Mobile Price and Qty Spinner -->
                                        <div class="flex sm:hidden items-center justify-between gap-4 mt-3">
                                            <span class="text-xs font-bold text-slate-900 dark:text-white" id="price-mobile-{{ $id }}">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                            
                                            <!-- Spinner Kuantitas Mobile -->
                                            <div class="flex items-center bg-slate-100 dark:bg-zinc-800 rounded-lg p-0.5 border border-slate-200 dark:border-white/5">
                                                <button type="button" onclick="updateCartQuantity('{{ $id }}', -1)" class="w-6 h-6 flex items-center justify-center text-slate-500 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-white dark:hover:bg-zinc-700 rounded transition-all focus:outline-none">
                                                    <i class="fas fa-minus text-[8px]"></i>
                                                </button>
                                                <input type="number" id="qty-mobile-{{ $id }}" value="{{ $item['quantity'] }}" min="1" readonly class="w-8 text-center bg-transparent text-xs font-bold text-slate-900 dark:text-white focus:outline-none appearance-none">
                                                <button type="button" onclick="updateCartQuantity('{{ $id }}', 1)" class="w-6 h-6 flex items-center justify-center text-slate-500 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-white dark:hover:bg-zinc-700 rounded transition-all focus:outline-none">
                                                    <i class="fas fa-plus text-[8px]"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Remove Button Mobile -->
                                <form id="delete-form-mobile-{{ $id }}" action="{{ route('cart.remove', $id) }}" method="POST" class="sm:hidden ml-auto flex-shrink-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmItemDelete('{{ $id }}', '{{ $item['product_name'] }}')"
                                            class="w-8 h-8 rounded-full bg-slate-100 dark:bg-zinc-800 text-slate-400 hover:text-rose-500 hover:bg-rose-500/10 transition-colors flex items-center justify-center" 
                                            title="Hapus dari Keranjang">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>

                            <!-- Desktop Price, Qty, Total -->
                            <div class="hidden sm:flex items-center justify-end flex-grow gap-8 text-right pl-4">
                                <div>
                                    <span class="text-[10px] font-mono text-slate-400 uppercase block mb-1">Harga</span>
                                    <span class="text-sm font-medium text-slate-600 dark:text-zinc-400">Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                                </div>
                                
                                <!-- Spinner Kuantitas Desktop -->
                                <div>
                                    <span class="text-[10px] font-mono text-slate-400 uppercase block mb-1">Kuantitas</span>
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

                                <div class="w-28">
                                    <span class="text-[10px] font-mono text-slate-400 uppercase block mb-1">Total</span>
                                    <span class="text-base font-bold text-slate-900 dark:text-white" id="total-desktop-{{ $id }}">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                </div>

                                <!-- Remove Button Desktop -->
                                <div class="pl-2">
                                    <form id="delete-form-desktop-{{ $id }}" action="{{ route('cart.remove', $id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmItemDelete('{{ $id }}', '{{ $item['product_name'] }}')"
                                                class="w-8 h-8 rounded-full bg-slate-100 dark:bg-zinc-800 text-slate-400 hover:text-rose-500 hover:bg-rose-500/10 transition-colors flex items-center justify-center" 
                                                title="Hapus dari Keranjang">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Ringkasan Belanja (Col 4) -->
            <div class="lg:col-span-4 reveal">
                <div class="glass-card p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-white/5 shadow-2xl lg:sticky lg:top-28">
                    <h3 class="font-serif text-xl font-semibold tracking-wide mb-6 pb-4 border-b border-slate-200 dark:border-white/10">Ringkasan Belanja</h3>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500 dark:text-zinc-400">Terpilih (<span id="selected-count">{{ count($cart) }}</span> unit)</span>
                            <span id="selected-subtotal" class="font-bold text-slate-900 dark:text-white">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>

                        <div class="text-[11px] text-slate-400 dark:text-zinc-500 italic pb-6 border-b border-slate-200 dark:border-white/10 leading-relaxed">
                            * Biaya pengiriman dan pajak akan dihitung secara detail pada halaman penyelesaian checkout.
                        </div>

                        <div class="flex justify-between items-center pt-4">
                            <h5 class="font-serif text-lg font-bold text-slate-900 dark:text-white">Estimasi Total</h5>
                            <h4 id="estimated-total" class="text-2xl font-black text-amber-600 dark:text-amber-400">Rp {{ number_format($total, 0, ',', '.') }}</h4>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 space-y-3">
                        <button type="button" id="checkoutBtn" onclick="submitCheckout()"
                           class="block w-full text-center py-4 font-semibold text-xs tracking-widest uppercase bg-slate-900 dark:bg-amber-400 text-white dark:text-black rounded-xl hover:bg-amber-500 dark:hover:bg-amber-300 shadow-lg active:scale-95 transition-all">
                            Lanjutkan ke Checkout
                        </button>
                        <a href="{{ route('shop') }}"
                           class="block w-full text-center py-4 font-semibold text-xs tracking-widest uppercase border border-slate-200 dark:border-white/10 text-slate-700 dark:text-zinc-300 rounded-xl hover:bg-slate-50 dark:hover:bg-zinc-850 transition-all">
                            Belanja Lagi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Tampilan jika keranjang kosong -->
        <div class="text-center py-24 reveal">
            <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-darkcard border border-slate-200 dark:border-white/5 flex items-center justify-center mx-auto mb-6 text-slate-400 shadow-inner">
                <i class="fas fa-shopping-bag text-2xl"></i>
            </div>
            <h3 class="font-serif text-xl sm:text-2xl font-bold">Keranjang Anda masih kosong</h3>
            <p class="text-xs sm:text-sm text-slate-400 dark:text-zinc-500 mt-2 max-w-sm mx-auto leading-relaxed">Temukan aroma tanda tangan dan karakter unik Anda di seluruh katalog terbaik kami.</p>
            <a href="{{ route('shop') }}" class="inline-block mt-8 px-8 py-4 font-semibold text-xs tracking-widest uppercase bg-slate-900 dark:bg-amber-400 text-white dark:text-black rounded-xl hover:bg-amber-500 dark:hover:bg-amber-300 transition-all shadow-lg shadow-amber-500/15">
                Mulai Belanja
            </a>
        </div>
    @endif
</div>

<!-- Form Hidden untuk Bulk / Multi Delete -->
<form id="hiddenBulkDeleteForm" action="" method="POST" class="hidden">
    @csrf
    @method('DELETE')
    <input type="hidden" name="cart_ids" id="hiddenCartIds">
</form>

<!-- Form Hidden untuk Proses Checkout Item Terpilih Beserta Qty Terbarunya -->
<form id="hiddenCheckoutForm" action="{{ route('checkout') }}" method="GET" class="hidden">
    <!-- Input item akan diinjeksi via JS sebelum disubmit -->
</form>

<style>
    /* Menghilangkan panah spinner bawaan browser pada input type number */
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>

<!-- =========================================================================
     SCRIPTS (Cart Dynamic Calculations & Spinner Logic)
     ========================================================================= -->
<script>
    // 1. Update order summary calculations dynamically
    function updateOrderSummary() {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        let selectedCount = 0; // Total jenis item
        let totalUnits = 0;    // Total botol fisik
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

        // Format subtotal ke rupiah
        const formattedTotal = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        }).format(selectedTotal);

        // Update DOM
        const selectedCountEl = document.getElementById('selected-count');
        const selectedSubtotalEl = document.getElementById('selected-subtotal');
        const estimatedTotalEl = document.getElementById('estimated-total');
        const checkoutBtn = document.getElementById('checkoutBtn');
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');

        if (selectedCountEl) selectedCountEl.innerText = totalUnits;
        if (selectedSubtotalEl) selectedSubtotalEl.innerText = formattedTotal;
        if (estimatedTotalEl) estimatedTotalEl.innerText = formattedTotal;

        // Atur status "Pilih Semua" checkbox utama
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = (selectedCount > 0 && selectedCount === checkboxes.length);
        }

        // Kunci tombol checkout jika tidak ada item terpilih
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

    // 2. Increment/Decrement Spinner Logic
    function updateCartQuantity(id, change) {
        const desktopInput = document.getElementById('qty-desktop-' + id);
        const mobileInput = document.getElementById('qty-mobile-' + id);
        
        let currentQty = 1;
        if(desktopInput) currentQty = parseInt(desktopInput.value);
        else if (mobileInput) currentQty = parseInt(mobileInput.value);
        
        let newQty = currentQty + change;
        if (newQty < 1) newQty = 1;
        
        // Perbarui Input Visual
        if(desktopInput) desktopInput.value = newQty;
        if(mobileInput) mobileInput.value = newQty;
        
        // Kalkulasi Ulang Harga Item
        const checkbox = document.querySelector(`.item-checkbox[value="${id}"]`);
        if(checkbox) {
            const basePrice = Number(checkbox.dataset.basePrice);
            const newTotal = basePrice * newQty;
            
            // Simpan harga baru di atribut data checkbox
            checkbox.dataset.price = newTotal;
            
            const formattedTotal = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            }).format(newTotal);
            
            // Perbarui label Total Harga produk
            const totalDesktop = document.getElementById('total-desktop-' + id);
            const priceMobile = document.getElementById('price-mobile-' + id);
            
            if(totalDesktop) totalDesktop.innerText = formattedTotal;
            if(priceMobile) priceMobile.innerText = formattedTotal;
            
            // Paksa pembaruan keranjang agar jika dicentang, ringkasan berubah otomatis
            updateOrderSummary();
            
            /* Catatan Integrasi:
               Dalam project asli, sebaiknya Anda meletakkan Axios/Fetch AJAX di sini
               untuk memperbarui Session Cart di Laravel agar data tersimpan di backend:
               axios.post(`/cart/update/${id}`, { quantity: newQty });
            */
        }
    }

    // 3. Toggle select/deselect all item checkboxes
    function toggleSelectAll(selectAll) {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        checkboxes.forEach(chk => {
            chk.checked = selectAll.checked;
        });
        updateOrderSummary();
    }

    // 4. Confirm Delete Dialog for Single Item
    function confirmItemDelete(cartId, productName) {
        Swal.fire({
            title: 'Keluarkan dari Keranjang?',
            text: `Apakah Anda yakin ingin menghapus produk '${productName}' dari daftar keranjang?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff2a5f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash mr-2"></i>Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-[1.5rem] dark-swal shadow-2xl',
                confirmButton: 'rounded-full px-4',
                cancelButton: 'rounded-full px-4'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Eksekusi form desktop atau mobile
                const formDesktop = document.getElementById('delete-form-desktop-' + cartId);
                const formMobile = document.getElementById('delete-form-mobile-' + cartId);
                if (formDesktop) formDesktop.submit();
                else if (formMobile) formMobile.submit();
            }
        });
    }

    // 5. Confirm Delete Dialog for Checked Items
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
                title: 'Tidak ada item terpilih',
                text: 'Pilih minimal satu parfum menggunakan kotak centang terlebih dahulu.',
                confirmButtonColor: '#f59e0b',
                customClass: { popup: 'rounded-[1.5rem] dark-swal shadow-2xl' }
            });
            return;
        }

        Swal.fire({
            title: 'Hapus Item Terpilih?',
            text: `Anda akan menghapus ${selectedIds.length} jenis produk dari keranjang secara massal.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff2a5f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash mr-2"></i>Ya, Hapus Semua!',
            cancelButtonText: 'Batal',
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

    // 6. Submit Checkout Handler
    function submitCheckout() {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const checkoutForm = document.getElementById('hiddenCheckoutForm');
        
        // Kosongkan form input sebelumnya (jika ada)
        checkoutForm.innerHTML = ''; 

        checkboxes.forEach(chk => {
            if (chk.checked) {
                const id = chk.value;
                const qtyInput = document.getElementById('qty-desktop-' + id) || document.getElementById('qty-mobile-' + id);
                const qty = qtyInput ? qtyInput.value : 1;

                // Injeksi data item yang dipilih dan kuantitasnya ke dalam form checkout
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

    // Jalankan kalkulasi total saat halaman pertama kali dimuat
    document.addEventListener('DOMContentLoaded', updateOrderSummary);
</script>
@endsection