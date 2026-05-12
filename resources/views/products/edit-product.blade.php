<!-- resources/views/products/edit.blade.php -->
@extends('base.base')

@section('content')
<div class="container py-5 mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-light mb-0">Edit Produk</h2>
                <a href="{{ route('shop') }}" class="btn btn-outline-secondary rounded-pill px-4">Batal</a>
            </div>

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4 p-md-5">
                    <!-- Gunakan method PUT untuk update -->
                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
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
                                    <img src="{{ $product->image_url ? asset('product_image/' . $product->image_url) : 'https://placehold.co/200x200?text=No+Image' }}" alt="{{ $product->name }}" style="object-fit: cover; height: 200px; width: 200px;" class="img-thumbnail">
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
                            
                            <select name="notes[]" class="form-select" multiple required style="height: 120px;">
                                @foreach($notes as $note)
                                    <option value="{{ $note->id }}" {{ in_array($note->id, $selectedNotes) ? 'selected' : '' }}>
                                        {{ $note->name }}
                                    </option>
                                @endforeach
                            </select>
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
                                        <label class="form-label small text-muted">Nama Varian</label>
                                        <input type="text" name="variants[size][]" class="form-control" placeholder="contoh: 50ml, 100ml, Travel Size" required>
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

    function addDefaultVariants() {
        addNewVariant('50ml');
        addNewVariant('100ml');
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
});
</script>
@endsection