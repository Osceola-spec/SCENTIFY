@extends('admin.layout')

@section('title', 'Edit Produk')

@section('content')
<div class="space-y-6 fade-in pb-12 max-w-5xl mx-auto">
    
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-200/50 pb-6 mb-2">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Edit Produk</h1>
            <p class="text-sm text-slate-500 mt-1">Perbarui informasi parfum, aroma, dan kelola varian ukurannya.</p>
        </div>
        <a href="{{ route('admin.inventory') }}" class="inline-flex items-center gap-2 bg-white border border-slate-200 text-slate-600 px-5 py-2.5 rounded-xl font-medium hover:bg-slate-50 hover:text-slate-900 transition-colors shadow-sm shrink-0">
            <i class="fas fa-arrow-left text-sm"></i> Kembali
        </a>
    </div>

    <!-- Alert Kesalahan Validasi Utama (Laravel Validation) -->
    @if ($errors->any())
        <div class="p-5 bg-rose-50 border border-rose-200 rounded-2xl flex items-start gap-4 text-rose-600 shadow-sm relative overflow-hidden mb-6" id="mainErrorAlert">
            <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center shrink-0">
                <i class="fas fa-exclamation-triangle text-rose-500"></i>
            </div>
            <div class="flex-1 pt-0.5">
                <h5 class="font-bold text-sm mb-2 text-rose-700">Oops! Ada kesalahan dalam form:</h5>
                <ul class="list-disc pl-4 space-y-1 text-xs text-rose-600/80 font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" onclick="document.getElementById('mainErrorAlert').remove()" class="text-rose-400 hover:text-rose-600 transition-colors focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Form Utama -->
    <div class="bg-white rounded-[1.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 sm:p-10">
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="productForm" class="space-y-10">
                @csrf
                @method('PUT')

                <!-- BAGIAN 1: INFORMASI DASAR -->
                <div>
                    <h5 class="text-lg font-bold text-slate-800 flex items-center gap-2 mb-6 pb-4 border-b border-slate-100">
                        <i class="fas fa-info-circle text-amber-500"></i> Informasi Dasar
                    </h5>
                    
                    <div class="space-y-6">
                        <!-- Nama Parfum -->
                        <div>
                            <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-400 mb-2 font-bold">Nama Parfum <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all placeholder-slate-300" required value="{{ $product->name }}">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Brand -->
                            <div class="relative">
                                <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-400 mb-2 font-bold">Brand Mitra <span class="text-rose-500">*</span></label>
                                <select name="brand_id" class="w-full appearance-none bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all cursor-pointer" required>
                                    <option value="" disabled>Pilih Brand Produk...</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute bottom-0 right-0 top-6 flex items-center pr-4 text-slate-400">
                                    <i class="fas fa-chevron-down text-[10px]"></i>
                                </div>
                            </div>

                            <!-- Kategori -->
                            <div class="relative">
                                <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-400 mb-2 font-bold">Kategori Kelas <span class="text-rose-500">*</span></label>
                                <select name="category" class="w-full appearance-none bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all cursor-pointer" required>
                                    <option value="Designer" {{ $product->category == 'Designer' ? 'selected' : '' }}>Designer</option>
                                    <option value="Niche" {{ $product->category == 'Niche' ? 'selected' : '' }}>Niche</option>
                                    <option value="Local" {{ $product->category == 'Local' ? 'selected' : '' }}>Local Premium</option>
                                </select>
                                <div class="pointer-events-none absolute bottom-0 right-0 top-6 flex items-center pr-4 text-slate-400">
                                    <i class="fas fa-chevron-down text-[10px]"></i>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Gender -->
                            <div class="relative">
                                <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-400 mb-2 font-bold">Gender Type <span class="text-rose-500">*</span></label>
                                <select name="gender_type" class="w-full appearance-none bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all cursor-pointer" required>
                                    <option value="Men" {{ $product->gender_type == 'Men' ? 'selected' : '' }}>Men (Pria)</option>
                                    <option value="Women" {{ $product->gender_type == 'Women' ? 'selected' : '' }}>Women (Wanita)</option>
                                    <option value="Unisex" {{ $product->gender_type == 'Unisex' ? 'selected' : '' }}>Unisex (Keduanya)</option>
                                </select>
                                <div class="pointer-events-none absolute bottom-0 right-0 top-6 flex items-center pr-4 text-slate-400">
                                    <i class="fas fa-chevron-down text-[10px]"></i>
                                </div>
                            </div>

                            <!-- Gambar Produk -->
                            <div>
                                <label for="image" class="block text-[10px] font-mono uppercase tracking-widest text-slate-400 mb-2 font-bold">Ubah Visual Produk</label>
                                <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png"
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-500 text-xs file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-100 file:text-amber-700 hover:file:bg-amber-200 transition-all cursor-pointer">
                                <p class="text-[10px] text-slate-400 mt-1"><i class="fas fa-info-circle mr-1"></i> Biarkan kosong jika tidak ingin mengubah gambar saat ini.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BAGIAN 2: AROMA & DESKRIPSI -->
                <div>
                    <h5 class="text-lg font-bold text-slate-800 flex items-center gap-2 mb-6 pb-4 border-b border-slate-100 mt-8">
                        <i class="fas fa-leaf text-amber-500"></i> Karakteristik Aroma & Deskripsi
                    </h5>

                    <div class="space-y-6">
                        <!-- Scent Notes -->
                        <div>
                            <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-400 mb-3 font-bold">Scent Notes (Karakteristik)</label>
                            
                            <!-- Array dari ID Scent Note yang saat ini dimiliki produk -->
                            @php $selectedNotes = $product->notes->pluck('id')->toArray(); @endphp

                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 bg-slate-50 border border-slate-200 p-5 rounded-2xl">
                                @foreach($notes as $note)
                                    <label class="flex items-center group cursor-pointer text-sm text-slate-700 hover:text-amber-600 transition-colors">
                                        <input type="checkbox" name="notes[]" value="{{ $note->id }}" id="note{{ $note->id }}" {{ in_array($note->id, $selectedNotes) ? 'checked' : '' }}
                                               class="rounded border-slate-300 text-amber-500 focus:ring-amber-500 bg-white mr-3 w-4 h-4 transition-colors cursor-pointer">
                                        <span class="font-medium truncate">{{ $note->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <small class="text-slate-400 text-xs mt-2 block"><i class="fas fa-info-circle mr-1"></i> Pilih satu atau lebih Scent Notes yang menggambarkan produk ini.</small>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-400 mb-2 font-bold">Deskripsi Lengkap <span class="text-rose-500">*</span></label>
                            <textarea name="description" rows="5" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all placeholder-slate-300 resize-none" required>{{ $product->description }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- BAGIAN 3: VARIAN & HARGA -->
                <div>
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 pb-4 border-b border-slate-100 mt-8">
                        <h5 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <i class="fas fa-tags text-amber-500"></i> Konfigurasi Varian & Stok
                        </h5>
                        <button type="button" id="addVariantBtn" class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200 transition-colors flex items-center gap-2 border border-slate-200 shadow-sm active:scale-95">
                            <i class="fas fa-plus"></i> Tambah Ukuran Baru
                        </button>
                    </div>

                    <!-- Container untuk variant items -->
                    <div id="variantsContainer" class="space-y-4">
                        <!-- Variant items akan ditambahkan di sini dengan JavaScript -->
                    </div>

                    <!-- Template untuk variant baru (hidden) -->
                    <template id="variantTemplate">
                        <div class="variant-item bg-slate-50/70 border border-slate-200 p-5 rounded-2xl relative group transition-all">
                            <div class="flex justify-between items-center mb-4">
                                <p class="font-bold text-slate-800 text-sm flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-md bg-slate-200 text-slate-600 flex items-center justify-center text-xs variantNumber">1</span>
                                    Konfigurasi Varian
                                </p>
                                <button type="button" class="removeVariantBtn w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-rose-500 hover:bg-rose-50 hover:border-rose-200 flex items-center justify-center transition-all shadow-sm" title="Hapus varian ini">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                <!-- Ukuran Volume -->
                                <div>
                                    <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 mb-2 font-bold">Volume <span class="text-rose-500">*</span></label>
                                    <div class="flex">
                                        <input type="number" name="variants[size][]" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-l-xl text-slate-700 text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all border-r-0 z-10" placeholder="Cth: 50" min="1" max="5000" required>
                                        <span class="inline-flex items-center px-4 bg-slate-100 border border-slate-200 rounded-r-xl text-slate-500 text-xs font-bold">ml</span>
                                    </div>
                                </div>
                                
                                <!-- Harga -->
                                <div>
                                    <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 mb-2 font-bold">Harga Jual <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="text-slate-400 text-xs font-bold">Rp</span>
                                        </div>
                                        <input type="number" name="variants[price][]" class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-slate-700 text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all" required placeholder="0">
                                    </div>
                                </div>

                                <!-- Stok -->
                                <div>
                                    <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 mb-2 font-bold">Kuantitas Stok <span class="text-rose-500">*</span></label>
                                    <input type="number" name="variants[stock][]" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-slate-700 text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all" required placeholder="0" min="0">
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Main Action Button -->
                <div class="pt-8 border-t border-slate-100 mt-10">
                    <button type="submit" class="w-full bg-slate-900 text-white font-bold tracking-widest uppercase py-4 rounded-xl hover:bg-slate-800 active:scale-95 transition-all duration-300 text-sm shadow-xl shadow-slate-900/20 flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle text-amber-400 text-base"></i> Perbarui Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const variantsContainer = document.getElementById('variantsContainer');
    const variantTemplate = document.getElementById('variantTemplate');
    const addVariantBtn = document.getElementById('addVariantBtn');
    const productForm = document.getElementById('productForm');

    // Tambahkan varian yang ada dari database
    const existingVariants = @json($product->variants);
    if (existingVariants && existingVariants.length > 0) {
        existingVariants.forEach(variant => {
            addVariantFromData(variant.size, variant.price, variant.stock);
        });
    } else {
        // Jika tidak ada varian, tambah 2 default
        addDefaultVariants();
    }

    // Event listener untuk tombol Add Variant
    addVariantBtn.addEventListener('click', function(e) {
        e.preventDefault();
        addNewVariant();
    });

    // Validasi form sebelum submit
    productForm.addEventListener('submit', function(e) {
        if (!validateVariants()) {
            e.preventDefault();
            showDuplicateError();
        }
    });

    function addDefaultVariants() {
        addNewVariant('50');
        addNewVariant('100');
    }

    function addNewVariant(defaultSize = '') {
        const clone = variantTemplate.content.cloneNode(true);
        const variantNumber = variantsContainer.children.length + 1;
        
        clone.querySelector('.variantNumber').textContent = variantNumber;
        
        // Set default size jika diberikan
        if (defaultSize) {
            clone.querySelector('input[name*="variants[size"]').value = defaultSize;
        }
        
        // Event listener untuk tombol hapus
        clone.querySelector('.removeVariantBtn').addEventListener('click', function(e) {
            e.preventDefault();
            const items = variantsContainer.querySelectorAll('.variant-item');
            if (items.length > 1) {
                // Tambahkan animasi fade-out sebelum dihapus
                const itemToRemove = this.closest('.variant-item');
                itemToRemove.style.opacity = '0';
                setTimeout(() => {
                    itemToRemove.remove();
                    updateVariantNumbers();
                    clearDuplicateError();
                }, 200);
            } else {
                showToastError('Minimal harus ada 1 varian produk!');
            }
        });

        variantsContainer.appendChild(clone);
    }

    function addVariantFromData(size, price, stock) {
        const clone = variantTemplate.content.cloneNode(true);
        const variantNumber = variantsContainer.children.length + 1;
        
        clone.querySelector('.variantNumber').textContent = variantNumber;
        clone.querySelector('input[name*="variants[size"]').value = size;
        clone.querySelector('input[name*="variants[price"]').value = price;
        clone.querySelector('input[name*="variants[stock"]').value = stock;
        
        // Event listener untuk tombol hapus
        clone.querySelector('.removeVariantBtn').addEventListener('click', function(e) {
            e.preventDefault();
            const items = variantsContainer.querySelectorAll('.variant-item');
            if (items.length > 1) {
                const itemToRemove = this.closest('.variant-item');
                itemToRemove.style.opacity = '0';
                setTimeout(() => {
                    itemToRemove.remove();
                    updateVariantNumbers();
                    clearDuplicateError();
                }, 200);
            } else {
                showToastError('Minimal harus ada 1 varian produk!');
            }
        });

        variantsContainer.appendChild(clone);
    }

    function updateVariantNumbers() {
        const items = variantsContainer.querySelectorAll('.variant-item');
        items.forEach((item, index) => {
            item.querySelector('.variantNumber').textContent = index + 1;
        });
    }

    function validateVariants() {
        const sizeInputs = variantsContainer.querySelectorAll('input[name*="variants[size"]');
        const sizes = [];
        let hasDuplicate = false;

        sizeInputs.forEach(input => {
            const value = input.value.trim();
            if (value) {
                if (sizes.includes(value)) {
                    hasDuplicate = true;
                    // Tandai input yang error
                    input.classList.add('border-rose-500', 'bg-rose-50');
                } else {
                    sizes.push(value);
                    input.classList.remove('border-rose-500', 'bg-rose-50');
                }
            }
        });

        return !hasDuplicate;
    }

    function showDuplicateError() {
        clearDuplicateError(); // Hapus error lama jika ada

        const alertHtml = `
            <div class="p-5 bg-rose-50 border border-rose-200 rounded-2xl flex items-start gap-4 text-rose-600 shadow-sm relative overflow-hidden mb-6 fade-in" id="duplicateErrorAlert">
                <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center shrink-0">
                    <i class="fas fa-exclamation-triangle text-rose-500"></i>
                </div>
                <div class="flex-1 pt-0.5">
                    <h5 class="font-bold text-sm mb-1 text-rose-700">Duplikasi Volume Terdeteksi</h5>
                    <p class="text-xs text-rose-600/80 font-medium">Anda tidak boleh memiliki dua varian dengan ukuran (ml) yang sama. Setiap varian harus memiliki ukuran yang unik.</p>
                </div>
                <button type="button" onclick="document.getElementById('duplicateErrorAlert').remove()" class="text-rose-400 hover:text-rose-600 transition-colors focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        // Insert alert tepat sebelum daftar varian
        const container = document.getElementById('variantsContainer');
        container.insertAdjacentHTML('beforebegin', alertHtml);

        // Scroll halus ke arah error
        document.getElementById('duplicateErrorAlert').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function clearDuplicateError() {
        const errorAlert = document.getElementById('duplicateErrorAlert');
        if (errorAlert) {
            errorAlert.remove();
        }
        
        // Bersihkan tanda merah di input size
        const sizeInputs = variantsContainer.querySelectorAll('input[name*="variants[size"]');
        sizeInputs.forEach(input => {
            input.classList.remove('border-rose-500', 'bg-rose-50');
        });
    }

    // Fungsi tambahan untuk toast error ringan
    function showToastError(message) {
        if(typeof Swal !== 'undefined') {
            Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'error',
                title: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                customClass: { popup: 'rounded-xl shadow-xl' }
            });
        } else {
            alert(message);
        }
    }
});
</script>

<style>
    /* Styling khusus spinner input number untuk form harga & stok */
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        opacity: 1;
    }
</style>
@endsection