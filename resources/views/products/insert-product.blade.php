@extends('base.base')

@section('content')
<div class="container py-5 mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-light mb-0">Tambah Produk Baru</h2>
                <a href="{{ route('shop') }}" class="btn btn-outline-secondary rounded-pill px-4">Batal</a>
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
                                <label class="form-label text-muted">Gambar Produk (URL Sementara)</label>
                                <input type="url" name="image_url" class="form-control" required placeholder="https://contoh.com/gambar.jpg">
                            </div>
                        </div>

                        <h5 class="fw-bold mb-4 border-bottom pb-2 mt-5">Aroma & Deskripsi</h5>
                        <div class="mb-3">
                            <label class="form-label text-muted">Scent Notes (Bisa pilih lebih dari satu)</label>
                            <select name="notes[]" class="form-select" multiple required style="height: 120px;">
                                @foreach($notes as $note)
                                    <option value="{{ $note->id }}">{{ $note->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Tahan tombol Ctrl (Windows) atau Cmd (Mac) untuk memilih lebih dari satu.</small>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-muted">Deskripsi Produk</label>
                            <textarea name="description" class="form-control" rows="4" required></textarea>
                        </div>

                        <h5 class="fw-bold mb-4 border-bottom pb-2 mt-5">Varian & Harga</h5>
                        <!-- Varian 50ml -->
                        <div class="row mb-3 bg-light p-3 rounded mx-0">
                            <p class="fw-bold mb-2">Ukuran 50ml</p>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Harga (Rp)</label>
                                <input type="number" name="price_50ml" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Stok</label>
                                <input type="number" name="stock_50ml" class="form-control" required>
                            </div>
                        </div>
                        <!-- Varian 100ml -->
                        <div class="row mb-4 bg-light p-3 rounded mx-0">
                            <p class="fw-bold mb-2">Ukuran 100ml</p>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Harga (Rp)</label>
                                <input type="number" name="price_100ml" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Stok</label>
                                <input type="number" name="stock_100ml" class="form-control">
                            </div>
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-dark btn-lg rounded-pill py-3">Simpan Produk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection