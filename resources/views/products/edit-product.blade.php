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
                                <label class="form-label text-muted">Gambar Produk (URL Sementara)</label>
                                <input type="url" name="image_url" class="form-control" required value="{{ $product->image_url }}">
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

                        <h5 class="fw-bold mb-4 border-bottom pb-2 mt-5">Varian & Harga</h5>
                        <!-- Logic untuk mengambil varian 50ml dan 100ml -->
                        @php
                            $var50 = $product->variants->where('size', '50ml')->first();
                            $var100 = $product->variants->where('size', '100ml')->first();
                        @endphp

                        <div class="row mb-3 bg-light p-3 rounded mx-0">
                            <p class="fw-bold mb-2">Ukuran 50ml</p>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Harga (Rp)</label>
                                <input type="number" name="price_50ml" class="form-control" value="{{ $var50->price ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Stok</label>
                                <input type="number" name="stock_50ml" class="form-control" value="{{ $var50->stock ?? '' }}" required>
                            </div>
                        </div>
                        
                        <div class="row mb-4 bg-light p-3 rounded mx-0">
                            <p class="fw-bold mb-2">Ukuran 100ml</p>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Harga (Rp)</label>
                                <input type="number" name="price_100ml" class="form-control" value="{{ $var100->price ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Stok</label>
                                <input type="number" name="stock_100ml" class="form-control" value="{{ $var100->stock ?? '' }}">
                            </div>
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-dark btn-lg rounded-pill py-3">Perbarui Produk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection