@extends('admin.layout')

@section('title', 'Tambah Produk Baru')

@section('content')
    <div class="space-y-6 fade-in pb-12 max-w-5xl mx-auto">

        <!-- Header Section -->
        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-200/50 pb-6 mb-2">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Tambah Produk Baru</h1>
                <p class="text-sm text-slate-500 mt-1">Tambahkan informasi parfum baru beserta varian ukurannya ke inventori.
                </p>
            </div>
            <a href="{{ route('admin.inventory') }}"
                class="inline-flex items-center gap-2 bg-white border border-slate-200 text-slate-600 px-5 py-2.5 rounded-xl font-medium hover:bg-slate-50 hover:text-slate-900 transition-colors shadow-sm shrink-0">
                <i class="fas fa-times text-sm"></i> Batal
            </a>
        </div>

        <!-- Alert Kesalahan Validasi Utama (Laravel Validation) -->
        @if ($errors->any())
            <div class="p-5 bg-rose-50 border border-rose-200 rounded-2xl flex items-start gap-4 text-rose-600 shadow-sm relative overflow-hidden mb-6"
                id="mainErrorAlert">
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
                <button type="button" onclick="document.getElementById('mainErrorAlert').remove()"
                    class="text-rose-400 hover:text-rose-600 transition-colors focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Form Utama -->
        <div class="bg-white rounded-[1.5rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-6 sm:p-10">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" id="productForm"
                    class="space-y-10">
                    @csrf

                    <!-- BAGIAN 1: INFORMASI DASAR -->
                    <div>
                        <h5
                            class="text-lg font-bold text-slate-800 flex items-center gap-2 mb-6 pb-4 border-b border-slate-100">
                            <i class="fas fa-info-circle text-amber-500"></i> Informasi Dasar
                        </h5>

                        <div class="space-y-6">
                            <!-- Nama Parfum -->
                            <div>
                                <label
                                    class="block text-[10px] font-mono uppercase tracking-widest text-slate-400 mb-2 font-bold">Nama
                                    Parfum <span class="text-rose-500">*</span></label>
                                <input type="text" name="name"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all placeholder-slate-300"
                                    required placeholder="Contoh: Bleu Ethereal">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Brand -->
                                <div class="relative">
                                    <label
                                        class="block text-[10px] font-mono uppercase tracking-widest text-slate-400 mb-2 font-bold">Brand
                                        Mitra <span class="text-rose-500">*</span></label>
                                    <select name="brand_id"
                                        class="w-full appearance-none bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all cursor-pointer"
                                        required>
                                        <option value="" disabled selected>Pilih Brand Produk...</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                    <div
                                        class="pointer-events-none absolute bottom-0 right-0 top-6 flex items-center pr-4 text-slate-400">
                                        <i class="fas fa-chevron-down text-[10px]"></i>
                                    </div>
                                </div>

                                <!-- Kategori -->
                                <div class="relative">
                                    <label
                                        class="block text-[10px] font-mono uppercase tracking-widest text-slate-400 mb-2 font-bold">Kategori
                                        Kelas <span class="text-rose-500">*</span></label>
                                    <select name="category"
                                        class="w-full appearance-none bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all cursor-pointer"
                                        required>
                                        <option value="Designer">Designer</option>
                                        <option value="Niche">Niche</option>
                                        <option value="Local">Local Premium</option>
                                    </select>
                                    <div
                                        class="pointer-events-none absolute bottom-0 right-0 top-6 flex items-center pr-4 text-slate-400">
                                        <i class="fas fa-chevron-down text-[10px]"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Gender -->
                                <div class="relative">
                                    <label
                                        class="block text-[10px] font-mono uppercase tracking-widest text-slate-400 mb-2 font-bold">Gender
                                        Type <span class="text-rose-500">*</span></label>
                                    <select name="gender_type"
                                        class="w-full appearance-none bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all cursor-pointer"
                                        required>
                                        <option value="Men">Men (Pria)</option>
                                        <option value="Women">Women (Wanita)</option>
                                        <option value="Unisex">Unisex (Keduanya)</option>
                                    </select>
                                    <div
                                        class="pointer-events-none absolute bottom-0 right-0 top-6 flex items-center pr-4 text-slate-400">
                                        <i class="fas fa-chevron-down text-[10px]"></i>
                                    </div>
                                </div>

                                <!-- Gambar Produk -->
                                <div>
                                    <label for="image"
                                        class="block text-[10px] font-mono uppercase tracking-widest text-slate-400 mb-2 font-bold">Visual
                                        Produk <span class="text-rose-500">*</span></label>
                                    <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png" required
                                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-500 text-xs file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-100 file:text-amber-700 hover:file:bg-amber-200 transition-all cursor-pointer">
                                    <p class="text-[10px] text-slate-400 mt-1">Format: JPG, JPEG, PNG.</p>
                                </div>
                                <!-- Gambar Tambahan -->
                                {{-- Gambar Tambahan --}}
                                <div class="md:col-span-2">
                                    <label
                                        class="block text-[10px] font-mono uppercase tracking-widest text-slate-400 mb-2 font-bold">
                                        Gambar Tambahan <span class="text-slate-300">(Opsional, maks. 5 foto)</span>
                                    </label>

                                    {{-- Drop Zone --}}
                                    <div id="dropZone"
                                        class="border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center cursor-pointer hover:border-amber-400 hover:bg-amber-50/30 transition-all duration-200 relative"
                                        onclick="document.getElementById('extraImagesInput').click()"
                                        ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)"
                                        ondrop="handleDrop(event)">
                                        <div id="dropZonePlaceholder">
                                            <i class="fas fa-cloud-upload-alt text-3xl text-slate-300 mb-3 block"></i>
                                            <p class="text-sm font-semibold text-slate-400">Klik atau seret foto ke sini</p>
                                            <p class="text-xs text-slate-300 mt-1">JPG, JPEG, PNG — Maks. 5 foto, 2MB per
                                                foto</p>
                                        </div>
                                        <input type="file" id="extraImagesInput" name="extra_images[]"
                                            accept=".jpg,.jpeg,.png" multiple class="hidden"
                                            onchange="handleFileSelect(this.files)">
                                    </div>

                                    {{-- Preview Grid --}}
                                    <div id="imagePreviewGrid"
                                        class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3 mt-4 hidden"></div>

                                    {{-- Counter --}}
                                    <p id="imageCounter" class="text-[11px] text-slate-400 mt-2 hidden">
                                        <span id="imageCountNum">0</span> foto dipilih
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BAGIAN 2: AROMA & DESKRIPSI -->
                    <div>
                        <h5
                            class="text-lg font-bold text-slate-800 flex items-center gap-2 mb-6 pb-4 border-b border-slate-100 mt-8">
                            <i class="fas fa-leaf text-amber-500"></i> Karakteristik Aroma & Deskripsi
                        </h5>

                        <div class="space-y-6">
                            <!-- Scent Notes -->
                            <div>
                                <label
                                    class="block text-[10px] font-mono uppercase tracking-widest text-slate-400 mb-3 font-bold">Scent
                                    Notes (Karakteristik)</label>

                                <div
                                    class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 bg-slate-50 border border-slate-200 p-5 rounded-2xl">
                                    @foreach ($notes as $note)
                                        <label
                                            class="flex items-center group cursor-pointer text-sm text-slate-700 hover:text-amber-600 transition-colors">
                                            <input type="checkbox" name="notes[]" value="{{ $note->id }}"
                                                id="note{{ $note->id }}"
                                                class="rounded border-slate-300 text-amber-500 focus:ring-amber-500 bg-white mr-3 w-4 h-4 transition-colors cursor-pointer">
                                            <span class="font-medium truncate">{{ $note->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <small class="text-slate-400 text-xs mt-2 block"><i class="fas fa-info-circle mr-1"></i>
                                    Pilih satu atau lebih Scent Notes yang menggambarkan produk ini.</small>
                            </div>

                            <!-- Deskripsi -->
                            <div>
                                <label
                                    class="block text-[10px] font-mono uppercase tracking-widest text-slate-400 mb-2 font-bold">Deskripsi
                                    Lengkap <span class="text-rose-500">*</span></label>
                                <textarea name="description" rows="5"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all placeholder-slate-300 resize-none"
                                    required placeholder="Tuliskan latar belakang aroma, nuansa, dan cerita di balik parfum ini..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- BAGIAN 3: VARIAN & HARGA -->
                    <div>
                        <div
                            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 pb-4 border-b border-slate-100 mt-8">
                            <h5 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <i class="fas fa-tags text-amber-500"></i> Konfigurasi Varian & Stok
                            </h5>
                            <button type="button" id="addVariantBtn"
                                class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200 transition-colors flex items-center gap-2 border border-slate-200 shadow-sm active:scale-95">
                                <i class="fas fa-plus"></i> Tambah Ukuran Baru
                            </button>
                        </div>

                        <!-- Container untuk variant items -->
                        <div id="variantsContainer" class="space-y-4">
                            <!-- Variant items akan ditambahkan di sini dengan JavaScript -->
                        </div>

                        <!-- Template untuk variant baru (hidden) -->
                        <template id="variantTemplate">
                            <div
                                class="variant-item bg-slate-50/70 border border-slate-200 p-5 rounded-2xl relative group transition-all">
                                <div class="flex justify-between items-center mb-4">
                                    <p class="font-bold text-slate-800 text-sm flex items-center gap-2">
                                        <span
                                            class="w-6 h-6 rounded-md bg-slate-200 text-slate-600 flex items-center justify-center text-xs variantNumber">1</span>
                                        Konfigurasi Varian
                                    </p>
                                    <button type="button"
                                        class="removeVariantBtn w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-rose-500 hover:bg-rose-50 hover:border-rose-200 flex items-center justify-center transition-all shadow-sm"
                                        title="Hapus varian ini">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                    <!-- Ukuran Volume -->
                                    <div>
                                        <label
                                            class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 mb-2 font-bold">Volume
                                            <span class="text-rose-500">*</span></label>
                                        <div class="flex">
                                            <input type="number" name="variants[size][]"
                                                class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-l-xl text-slate-700 text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all border-r-0 z-10"
                                                placeholder="Cth: 50" min="1" max="5000" required>
                                            <span
                                                class="inline-flex items-center px-4 bg-slate-100 border border-slate-200 rounded-r-xl text-slate-500 text-xs font-bold">ml</span>
                                        </div>
                                    </div>

                                    <!-- Harga -->
                                    <div>
                                        <label
                                            class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 mb-2 font-bold">Harga
                                            Jual <span class="text-rose-500">*</span></label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <span class="text-slate-400 text-xs font-bold">Rp</span>
                                            </div>
                                            <input type="number" name="variants[price][]"
                                                class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-slate-700 text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all"
                                                required placeholder="0">
                                        </div>
                                    </div>

                                    <!-- Stok -->
                                    <div>
                                        <label
                                            class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 mb-2 font-bold">Kuantitas
                                            Stok <span class="text-rose-500">*</span></label>
                                        <input type="number" name="variants[stock][]"
                                            class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-slate-700 text-sm focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all"
                                            required placeholder="0" min="0">
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Main Action Button -->
                    <div class="pt-8 border-t border-slate-100 mt-10">
                        <button type="submit"
                            class="w-full bg-slate-900 text-white font-bold tracking-widest uppercase py-4 rounded-xl hover:bg-slate-800 active:scale-95 transition-all duration-300 text-sm shadow-xl shadow-slate-900/20 flex items-center justify-center gap-2">
                            <i class="fas fa-check-circle text-amber-400 text-base"></i> Simpan Ke Katalog
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

            // Tambahkan 2 varian default (50 dan 100 ml)
            addDefaultVariants();

            // Event listener untuk tombol Add Variant
            addVariantBtn.addEventListener('click', function(e) {
                e.preventDefault();
                addNewVariant();
            });

            // ngecek size 2MB buat gambar utamanya
            const mainImageInput = document.getElementById('image');
            if (mainImageInput) {
                mainImageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    const maxFileSize = 2 * 1024 * 1024; // 2MB

                    if (file && file.size > maxFileSize) {
                        // Munculkan pop-up SweetAlert2
                        Swal.fire({
                            title: 'Ukuran Terlalu Besar!',
                            html: `Gambar <strong>${file.name}</strong> ukurannya ${(file.size / (1024*1024)).toFixed(2)} MB.<br>Maksimal ukuran yang diperbolehkan adalah <strong>2 MB</strong>.`,
                            icon: 'warning',
                            confirmButtonColor: '#f59e0b',
                            confirmButtonText: 'Pilih Gambar Lain',
                            customClass: {
                                popup: 'rounded-xl shadow-xl'
                            }
                        });

                        e.target.value = '';
                    }
                });
            }

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
                document.getElementById('duplicateErrorAlert').scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
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
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'bottom-end',
                        icon: 'error',
                        title: message,
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        customClass: {
                            popup: 'rounded-xl shadow-xl'
                        }
                    });
                } else {
                    alert(message);
                }
            }
        });
        // ===== MULTI IMAGE UPLOAD =====
        let selectedFiles = [];
        const MAX_FILES = 5;

        function handleFileSelect(files) {
            const newFiles = Array.from(files);
            const remaining = MAX_FILES - selectedFiles.length;

            if (newFiles.length > remaining) {
                showToastError(`Maksimal ${MAX_FILES} foto. Hanya ${remaining} slot tersisa.`);
                newFiles.splice(remaining);
            }

            newFiles.forEach(file => {
                if (!['image/jpeg', 'image/jpg', 'image/png'].includes(file.type)) {
                    showToastError(`Format file ${file.name} tidak didukung.`);
                    return;
                }
                if (file.size > 2 * 1024 * 1024) {
                    showToastError(`File ${file.name} melebihi 2MB.`);
                    return;
                }
                selectedFiles.push(file);
            });

            syncFilesToInput();
            renderPreviews();
        }

        function syncFilesToInput() {
            const input = document.getElementById('extraImagesInput');
            const dt = new DataTransfer();
            selectedFiles.forEach(f => dt.items.add(f));
            input.files = dt.files;
        }

        function renderPreviews() {
            const grid = document.getElementById('imagePreviewGrid');
            const counter = document.getElementById('imageCounter');
            const countNum = document.getElementById('imageCountNum');
            const placeholder = document.getElementById('dropZonePlaceholder');

            grid.innerHTML = '';

            if (selectedFiles.length === 0) {
                grid.classList.add('hidden');
                counter.classList.add('hidden');
                placeholder.style.display = '';
                return;
            }

            grid.classList.remove('hidden');
            counter.classList.remove('hidden');
            countNum.textContent = selectedFiles.length;
            placeholder.style.display = 'none';

            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const div = document.createElement('div');
                    div.className =
                        'relative group rounded-xl overflow-hidden border border-slate-200 bg-slate-50 aspect-square';
                    div.innerHTML = `
                <img src="${e.target.result}" class="w-full h-full object-cover">
                <button type="button"
                        onclick="removePreviewFile(${index})"
                        class="absolute top-1.5 right-1.5 w-6 h-6 rounded-full bg-rose-500 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-md focus:outline-none">
                    <i class="fas fa-times text-[10px]"></i>
                </button>
                <div class="absolute bottom-0 left-0 right-0 bg-black/40 px-2 py-1">
                    <p class="text-white text-[9px] truncate">${file.name}</p>
                </div>
            `;
                    grid.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }

        function removePreviewFile(index) {
            selectedFiles.splice(index, 1);
            syncFilesToInput();
            renderPreviews();
        }

        function handleDragOver(e) {
            e.preventDefault();
            document.getElementById('dropZone').classList.add('border-amber-400', 'bg-amber-50/30');
        }

        function handleDragLeave(e) {
            document.getElementById('dropZone').classList.remove('border-amber-400', 'bg-amber-50/30');
        }

        function handleDrop(e) {
            e.preventDefault();
            handleDragLeave(e);
            handleFileSelect(e.dataTransfer.files);
        }

        // Khusus edit: tandai gambar untuk dihapus
        function markDeleteImage(imgId, btn) {
            const checkbox = document.getElementById('deleteImg' + imgId);
            const container = document.getElementById('existingImg' + imgId);

            if (checkbox.checked) {
                // Batalkan hapus
                checkbox.checked = false;
                container.classList.remove('opacity-40', 'ring-2', 'ring-rose-400');
                btn.innerHTML = '<i class="fas fa-times text-[10px]"></i>';
                btn.classList.remove('bg-slate-400');
                btn.classList.add('bg-rose-500');
            } else {
                // Tandai hapus
                checkbox.checked = true;
                container.classList.add('opacity-40', 'ring-2', 'ring-rose-400');
                btn.innerHTML = '<i class="fas fa-undo text-[10px]"></i>';
                btn.classList.remove('bg-rose-500');
                btn.classList.add('bg-slate-400');
            }
        }
        // ===== END MULTI IMAGE UPLOAD =====
    </script>

    <style>
        /* Styling khusus spinner input number untuk form harga & stok */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            opacity: 1;
        }
    </style>
@endsection
