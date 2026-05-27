@extends('admin.layout')

@section('title', 'Inventori Produk')

@section('content')
<div class="space-y-6 fade-in pb-10">

    <div class="pt-2 pb-4 border-b border-slate-200/50 mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Inventory Produk</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola dan pantau semua produk yang tersedia di katalog toko.</p>
            </div>
            <a href="{{ route('products.create') }}" class="inline-flex items-center gap-2 bg-amber-500 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-amber-600 transition-all shadow-lg shadow-amber-500/30 active:scale-95 shrink-0">
                <i class="fas fa-plus"></i> Tambah Produk Baru
            </a>
        </div>
    </div>

    <div class="bg-white rounded-[1.5rem] p-5 border border-slate-100 shadow-sm relative overflow-hidden">
        <form action="{{ route('admin.inventory') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center relative z-10">
            
            <div class="md:col-span-5 relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-amber-500 transition-colors">
                    <i class="fas fa-search"></i>
                </div>
                <input type="search" name="search" value="{{ request('search') }}" 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-11 pr-4 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all" 
                       placeholder="Cari nama parfum, notes, dll...">
            </div>

            <div class="md:col-span-4 relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <i class="fas fa-filter text-xs"></i>
                </div>
                <select name="filter" class="w-full appearance-none bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-10 py-2.5 text-sm text-slate-700 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition-all cursor-pointer">
                    <option value="name" {{ request('filter', 'name') === 'name' ? 'selected' : '' }}>Saring berdasarkan: Nama Produk</option>
                    <option value="brand" {{ request('filter') === 'brand' ? 'selected' : '' }}>Saring berdasarkan: Brand</option>
                    <option value="category" {{ request('filter') === 'category' ? 'selected' : '' }}>Saring berdasarkan: Kategori</option>
                    <option value="gender_type" {{ request('filter') === 'gender_type' ? 'selected' : '' }}>Saring berdasarkan: Gender</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                    <i class="fas fa-chevron-down text-[10px]"></i>
                </div>
            </div>

            <div class="md:col-span-3 flex gap-3">
                <button type="submit" class="flex-1 bg-slate-900 text-white font-semibold text-sm py-2.5 rounded-xl hover:bg-slate-800 transition-colors shadow-md">
                    Cari
                </button>
                <a href="{{ route('admin.inventory') }}" class="flex-1 bg-white border border-slate-200 text-slate-600 font-semibold text-sm py-2.5 rounded-xl hover:bg-slate-50 hover:text-slate-900 transition-colors text-center flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-[1.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-0">
            @if($products->isEmpty())
                <div class="flex flex-col items-center justify-center p-12 text-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 text-3xl mb-4">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h5 class="text-lg font-bold text-slate-800 mb-1">Inventaris Kosong</h5>
                    <p class="text-sm text-slate-500 max-w-md">Belum ada produk yang ditemukan. Klik tombol "Tambah Produk Baru" di sudut kanan atas untuk mulai mengelola katalog Anda.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                                <th class="px-6 py-4 border-b border-slate-100">#</th>
                                <th class="px-6 py-4 border-b border-slate-100">Visual</th>
                                <th class="px-6 py-4 border-b border-slate-100">Nama Produk</th>
                                <th class="px-6 py-4 border-b border-slate-100">Brand</th>
                                <th class="px-6 py-4 border-b border-slate-100">Kategori</th>
                                <th class="px-6 py-4 border-b border-slate-100">Gender</th>
                                <th class="px-6 py-4 border-b border-slate-100 text-center">Varian</th>
                                <th class="px-6 py-4 border-b border-slate-100 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-slate-700 divide-y divide-slate-50">
                            @foreach($products as $index => $product)
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="px-6 py-4 font-medium text-slate-400">{{ $index + 1 }}</td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden shadow-sm">
                                            <img src="{{ $product->image_url 
                                                ? (strpos($product->image_url, 'http') === 0 ? $product->image_url : asset('product_image/' . $product->image_url)) 
                                                : 'https://placehold.co/200x200?text=No+Image' }}" 
                                                alt="{{ $product->name }}" 
                                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 font-bold text-slate-900">{{ $product->name }}</td>
                                    
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 text-[11px] font-semibold tracking-wide border border-slate-200">
                                            {{ $product->brand?->name ?? '-' }}
                                        </span>
                                    </td>
                                    
                                    <td class="px-6 py-4">{{ $product->category }}</td>
                                    
                                    <td class="px-6 py-4">
                                        @php
                                            $genderClass = match($product->gender_type) {
                                                'Men' => 'text-blue-600 bg-blue-50 border-blue-100',
                                                'Women' => 'text-rose-600 bg-rose-50 border-rose-100',
                                                default => 'text-purple-600 bg-purple-50 border-purple-100'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold border {{ $genderClass }}">
                                            {{ $product->gender_type }}
                                        </span>
                                    </td>
                                    
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-bold border border-emerald-100">
                                            {{ $product->variants->count() }}
                                        </span>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('products.edit', $product->id) }}" 
                                               class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-200 flex items-center justify-center transition-all shadow-sm"
                                               title="Edit Produk">
                                                <i class="fas fa-edit text-xs"></i>
                                            </a>
                                            
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline m-0 p-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini dari katalog?')"
                                                        class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:text-rose-600 hover:bg-rose-50 hover:border-rose-200 flex items-center justify-center transition-all shadow-sm"
                                                        title="Hapus Produk">
                                                    <i class="fas fa-trash-alt text-xs"></i>
                                                </button>
                                            </form>
                                            <a href="{{ route('admin.promotions.create') }}?product_id={{ $product->id }}" 
                                               class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:text-amber-600 hover:bg-amber-50 hover:border-amber-200 flex items-center justify-center transition-all shadow-sm"
                                               title="Set Promo untuk produk ini">
                                                <i class="fas fa-tags text-xs"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection