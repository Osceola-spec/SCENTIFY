@extends('admin.layout')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Inventory Produk</h1>
            <p class="text-muted mb-0">Kelola semua produk yang tersedia di toko.</p>
        </div>
        <a href="{{ route('products.create') }}" class="btn btn-primary">Tambah Product Baru</a>
    </div>

    <form action="{{ route('admin.inventory') }}" method="GET" class="row g-2 align-items-center mb-4">
        <div class="col-md-5">
            <input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari produk...">
        </div>
        <div class="col-md-4">
            <select name="filter" class="form-select">
                <option value="name" {{ request('filter', 'name') === 'name' ? 'selected' : '' }}>Nama Produk</option>
                <option value="brand" {{ request('filter') === 'brand' ? 'selected' : '' }}>Brand</option>
                <option value="category" {{ request('filter') === 'category' ? 'selected' : '' }}>Kategori</option>
                <option value="gender_type" {{ request('filter') === 'gender_type' ? 'selected' : '' }}>Gender</option>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Cari</button>
            <a href="{{ route('admin.inventory') }}" class="btn btn-outline-secondary w-100">Reset</a>
        </div>
    </form>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            @if($products->isEmpty())
                <div class="alert alert-info mb-0">
                    Belum ada produk. Klik tombol "Tambah Product Baru" untuk menambahkan inventaris.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Gambar</th>
                                <th>Nama Produk</th>
                                <th>Brand</th>
                                <th>Kategori</th>
                                <th>Gender</th>
                                <th>Varian</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $index => $product)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td style="width: 90px;">
                                        <img src="{{ $product->image_url
                                        ? asset('product_image/' . $product->image_url)
                                        : 'https://placehold.co/200x200?text=No+Image' }}" alt="{{ $product->name }}" class="img-fluid rounded" style="max-height: 60px; object-fit: cover;">
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->brand?->name ?? '-' }}</td>
                                    <td>{{ $product->category }}</td>
                                    <td>{{ $product->gender_type }}</td>
                                    <td>{{ $product->variants->count() }} variant</td>
                                    <td style="white-space: nowrap;">
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-secondary me-1">Edit</a>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus produk ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
