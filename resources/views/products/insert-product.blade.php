@extends('admin.layout')

@section('title', 'Tambah Produk Baru')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-xl-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1">Tambah Produk Baru</h2>
                    <p class="text-muted mb-0">Tambahkan produk baru ke inventori admin.</p>
                </div>
                <a href="{{ route('admin.inventory') }}" class="btn btn-outline-secondary rounded-pill px-4">Batal</a>
            </div>

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <h5 class="fw-bold mb-4 border-bottom pb-2">Informasi Dasar</h5>
                        <div class="mb-3">
                            <label class="form-label text-muted">Nama Parfum</label>
                            <input type="text" name="name" class="form-control" required placeholder="Contoh: Bleu Ethereal">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted">Brand</label>
                                <select name="brand_id" class="form-select" required>
                                    <option value="" disabled selected>Pilih Brand...</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Kategori</label>
                                <select name="category" class="form-select" required>
                                    <option value="Designer">Designer</option>
                                    <option value="Niche">Niche</option>
                                    <option value="Local">Local Premium</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label text-muted">Gender</label>
                                <select name="gender_type" class="form-select" required>
                                    <option value="Men">Men</option>
                                    <option value="Women">Women</option>
                                    <option value="Unisex">Unisex</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                {{-- <label class="form-label text-muted">Gambar Produk (URL Sementara)</label>
                                <input type="url" name="image_url" class="form-control" required    placeholder="https://contoh.com/gambar.jpg"> --}}
                                <label for="image" class="form-label text-muted">Product Image (jpg, jpeg, png)</label>
                                <input type="file" class="form-control" id="image" name="image" accept=".jpg,.jpeg,.png">
           
                            </div>
                        </div>

                        <h5 class="fw-bold mb-4 border-bottom pb-2 mt-5">Aroma & Deskripsi</h5>
                        <div class="mb-3">
                            <label class="form-label text-muted">Scent Notes (Bisa pilih lebih dari satu)</label>
                            <div class="mb-3">
                                <label class="form-label text-muted">
                                    Scent Notes
                                </label>

                                <div class="row mt-2">

                                    @foreach($notes as $note)

                                    <div class="col-6 col-md-4">

                                        <div class="form-check mb-2">

                                            <input 
                                                class="form-check-input"
                                                type="checkbox"
                                                name="notes[]"
                                                value="{{ $note->id }}"
                                                id="note{{ $note->id }}"
                                            >

                                            <label 
                                                class="form-check-label"
                                                for="note{{ $note->id }}"
                                            >
                                                {{ $note->name }}
                                            </label>

                                        </div>

                                    </div>

                                    @endforeach

                                </div>

                                <small class="text-muted">
                                    Pilih satu atau lebih scent notes.
                                </small>

                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-muted">Deskripsi Produk</label>
                            <textarea name="description" class="form-control" rows="4" required></textarea>
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
                            <button type="submit" class="btn btn-dark btn-lg rounded-pill py-3">Simpan Produk</button>
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

    // Tambahkan 2 varian default (50ml dan 100ml)
    addDefaultVariants();

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

    function updateVariantNumbers() {
        const items = variantsContainer.querySelectorAll('.variant-item');
        items.forEach((item, index) => {
            item.querySelector('.variantNumber').textContent = index + 1;
        });
    }
});
</script>
@endsection