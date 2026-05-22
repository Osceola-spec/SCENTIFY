<!-- resources/views/products/edit.blade.php -->
@extends('admin.layout')

@section('title', 'Edit Produk')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-xl-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1">Edit Produk</h2>
                    <p class="text-muted mb-0">Perbarui produk dan kembali ke inventori admin.</p>
                </div>
                <a href="{{ route('admin.inventory') }}" class="btn btn-outline-secondary rounded-pill px-4">Batal</a>
            </div>

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4 p-md-5">
                    {{-- Tampilkan Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4" role="alert">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-exclamation-circle me-3 mt-1" style="font-size: 1.2rem;"></i>
                                <div>
                                    <h5 class="alert-heading mb-2">Oops! Ada kesalahan dalam form</h5>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Gunakan method PUT untuk update -->
                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="productForm">
                        @csrf
                        @method('PUT')

                        <h5 class="fw-bold mb-4 border-bottom pb-2">Informasi Dasar</h5>
                        <div class="mb-3">
                            <label class="form-label text-muted">Nama Parfum</label>
                            <input type="text" name="name" class="form-control" required value="{{ $product->name }}">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted">Brand</label>
                                <select name="brand_id" class="form-select" required>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Kategori</label>
                                <select name="category" class="form-select" required>
                                    <option value="Designer" {{ $product->category == 'Designer' ? 'selected' : '' }}>Designer</option>
                                    <option value="Niche" {{ $product->category == 'Niche' ? 'selected' : '' }}>Niche</option>
                                    <option value="Local" {{ $product->category == 'Local' ? 'selected' : '' }}>Local Premium</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label text-muted">Gender</label>
                                <select name="gender_type" class="form-select" required>
                                    <option value="Men" {{ $product->gender_type == 'Men' ? 'selected' : '' }}>Men</option>
                                    <option value="Women" {{ $product->gender_type == 'Women' ? 'selected' : '' }}>Women</option>
                                    <option value="Unisex" {{ $product->gender_type == 'Unisex' ? 'selected' : '' }}>Unisex</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                {{-- <label class="form-label">Current Image</label> --}}
                                {{-- <div class="mb-2">
                                    <img src="{{ $product->image_url ? (strpos($product->image_url, 'http') === 0 ? $product->image_url : asset('product_image/' . $product->image_url)) : 'https://placehold.co/200x200?text=No+Image' }}" alt="{{ $product->name }}" style="object-fit: cover; height: 200px; width: 200px;" class="img-thumbnail">
                                </div> --}}
                                <label for="image" class="form-label">Update Product Image (jpg, jpeg, png)</label>
                                <input type="file" class="form-control" id="image" name="image" accept=".jpg,.jpeg,.png">
                            </div>
                        </div>

                        <h5 class="fw-bold mb-4 border-bottom pb-2 mt-5">Aroma & Deskripsi</h5>
                        <div class="mb-3">
                            <label class="form-label text-muted">Scent Notes</label>
                            <!-- Array dari ID Scent Note yang saat ini dimiliki produk -->
                            @php $selectedNotes = $product->notes->pluck('id')->toArray(); @endphp

                            <div class="row g-2 mt-2">
                                @foreach($notes as $note)
                                    <div class="col-6 col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="notes[]" value="{{ $note->id }}" id="note{{ $note->id }}" {{ in_array($note->id, $selectedNotes) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="note{{ $note->id }}">
                                                {{ $note->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <small class="text-muted">Pilih satu atau lebih scent notes.</small>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-muted">Deskripsi Produk</label>
                            <textarea name="description" class="form-control" rows="4" required>{{ $product->description }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
                            <h5 class="fw-bold mb-0 border-bottom pb-2" style="flex: 1;">Varian & Harga</h5>
                            <button type="button" class="btn btn-sm btn-outline-dark ms-2" id="addVariantBtn">
                                <i class="bi bi-plus-lg"></i> Add Variant
                            </button>
                        </div>

                        <!-- Container untuk variant items -->
                        <div id="variantsContainer">
                            <!-- Variant items akan ditambahkan di sini dengan JavaScript -->
                        </div>

                        <!-- Template untuk variant baru (hidden) -->
                        <template id="variantTemplate">
                            <div class="variant-item mb-3 bg-light p-3 rounded mx-0 position-relative">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <p class="fw-bold mb-0">Varian <span class="variantNumber">1</span></p>
                                    <button type="button" class="btn btn-sm btn-outline-danger removeVariantBtn" title="Hapus varian ini">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label small text-muted">Ukuran (Volume)</label>
                                        <div class="input-group">
                                            <input type="number" name="variants[size][]" class="form-control" placeholder="contoh: 50, 100, 250" min="1" max="5000" required>
                                            <span class="input-group-text bg-light border-start-0">ml</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small text-muted">Harga (Rp)</label>
                                        <input type="number" name="variants[price][]" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small text-muted">Stok</label>
                                        <input type="number" name="variants[stock][]" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-dark btn-lg rounded-pill py-3">Perbarui Produk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const variantsContainer = document.getElementById('variantsContainer');
    const variantTemplate = document.getElementById('variantTemplate');
    const addVariantBtn = document.getElementById('addVariantBtn');

    // Tambahkan varian yang ada dari database
    const existingVariants = @json($product->variants);
    if (existingVariants.length > 0) {
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
    document.getElementById('productForm').addEventListener('submit', function(e) {
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
                this.closest('.variant-item').remove();
                updateVariantNumbers();
                clearDuplicateError();
            } else {
                alert('Minimal harus ada 1 varian!');
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
                this.closest('.variant-item').remove();
                updateVariantNumbers();
                clearDuplicateError();
            } else {
                alert('Minimal harus ada 1 varian!');
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
                } else {
                    sizes.push(value);
                }
            }
        });

        return !hasDuplicate;
    }

    function showDuplicateError() {
        // Hapus error lama jika ada
        clearDuplicateError();

        const alertHtml = `
            <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4" role="alert" id="duplicateError">
                <div class="d-flex align-items-start">
                    <i class="fas fa-exclamation-circle me-3 mt-1" style="font-size: 1.2rem;"></i>
                    <div>
                        <h5 class="alert-heading mb-2">Ukuran Varian Duplikat!</h5>
                        <p class="mb-0">Anda tidak boleh memiliki dua varian dengan ukuran yang sama. Setiap varian harus memiliki ukuran yang unik.</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

        // Insert alert sebelum card
        const card = document.querySelector('.card');
        card.insertAdjacentHTML('beforebegin', alertHtml);

        // Scroll ke atas untuk melihat error
        document.querySelector('#duplicateError').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function clearDuplicateError() {
        const errorAlert = document.getElementById('duplicateError');
        if (errorAlert) {
            errorAlert.remove();
        }
    }
});
</script>
@endsection