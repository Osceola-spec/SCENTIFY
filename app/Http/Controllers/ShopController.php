<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use App\Models\Brand;
use App\Models\ScentNote;
use App\Models\ProductVariant;
use App\Models\Promotion;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    public function show(Request $request)
    {
        
        $brands = Brand::all();

            // ensure variable exists for the view in all code paths
            $activePromotions = collect();
            $upcomingPromotions = collect();

            // Auto-disable expired promos
            Promotion::where('is_active', true)
                ->whereNotNull('ends_at')
                ->where('ends_at', '<', now())
                ->update(['is_active' => false]);

        // 1. Mulai query dengan eager loading
        $query = Product::with([
            'brand',
            'variants',
            'images',
            'reviews', // tambahkan
        ]);

        // LOGIKA PENCARIAN NAVBAR
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;

            // Gunakan Laravel Scout (Meilisearch) jika tersedia untuk fuzzy search
            try {
                $products = Product::search($searchTerm)
                    ->with(['brand','variants','images','reviews'])
                    ->paginate(12);

                $wishlistedProductIds = [];
                if (auth()->check()) {
                    $wishlistedProductIds = \App\Models\Wishlist::where('user_id', auth()->id())->pluck('product_id')->toArray();
                }

                $brands = Brand::all();

                // include active and upcoming promotions even when returning early from Scout search
                $now = now();
                $activePromotions = Promotion::where('is_active', true)
                    ->where(function($q) use ($now) {
                        $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
                    })
                    ->where(function($q) use ($now) {
                        $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
                    })->get();

                $upcomingPromotions = Promotion::where('is_active', true)
                    ->whereNotNull('starts_at')
                    ->where('starts_at', '>', $now)
                    ->orderBy('starts_at', 'asc')
                    ->get();

                Log::debug('ShopController: scout search promotions', ['active_found' => $activePromotions->count(), 'upcoming_found' => $upcomingPromotions->count()]);

                return view('shop', compact('products', 'brands', 'wishlistedProductIds', 'activePromotions', 'upcomingPromotions'));
            } catch (\Throwable $e) {
                // Jika Scout/Meilisearch tidak tersedia, fallback ke query SQL biasa
                // (log error dan lanjutkan)
                Log::warning('Scout search failed, falling back to SQL: ' . $e->getMessage());

                $search = $searchTerm;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('gender_type', 'LIKE', "%{$search}%")
                      ->orWhere('category', 'LIKE', "%{$search}%")
                      ->orWhereHas('brand', function($brandQuery) use ($search) {
                          $brandQuery->where('name', 'LIKE', "%{$search}%");
                      });
                });
            }
        }

        // 2. Filter Gender (Sekarang aman di luar scope IF Search)
        if ($request->has('gender')) {
            $query->whereIn('gender_type', $request->gender);
        }

        // 3. Filter Brand
        if ($request->has('brand') && $request->brand != '') {
            $brandFilter = (array) $request->brand;

            $query->whereIn('brand_id', $brandFilter);
        }

        // 3.5 Filter Category
        if ($request->has('category') && !empty($request->category)) {
            $categoryFilter = (array) $request->category;
            $query->whereIn('category', $categoryFilter);
        }

        // 4. Filter Harga
        if ($request->has('max_price')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('price', '<=', $request->max_price);
            });
        }

        // 5. Logika Sorting
        if ($request->sort === 'price_asc') {
            $query->addSelect([
                'min_price' => ProductVariant::select('price')
                    ->whereColumn('product_id', 'products.id')
                    ->orderBy('price', 'asc')
                    ->limit(1)
            ])->orderBy('min_price', 'asc');
        } elseif ($request->sort === 'price_desc') {
            $query->addSelect([
                'min_price' => ProductVariant::select('price')
                    ->whereColumn('product_id', 'products.id')
                    ->orderBy('price', 'asc')
                    ->limit(1)
            ])->orderBy('min_price', 'desc');
        } else {
            $query->latest();
        }

        // Eksekusi pagination wajib berjalan di setiap request apa pun
        // $products = $query->paginate(12)->withQueryString();

        $wishlistedProductIds = [];
        if (auth()->check()) {
            $wishlistedProductIds = \App\Models\Wishlist::where('user_id', auth()->id())->pluck('product_id')->toArray();
        }

        // PASTIKAN COMPACT-NYA DISESUAIKAN:
        $products = $query->paginate(12)->withQueryString();

        // Cari promo aktif berjalan (jika ada)
        $now = now();
        $activePromotions = Promotion::where('is_active', true)
            ->where(function($q) use ($now) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function($q) use ($now) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            })->get();

        $upcomingPromotions = Promotion::where('is_active', true)
            ->whereNotNull('starts_at')
            ->where('starts_at', '>', $now)
            ->orderBy('starts_at', 'asc')
            ->get();

        return view('shop', compact('products', 'brands', 'wishlistedProductIds', 'activePromotions', 'upcomingPromotions'));
    }

    public function insert_product()
    {
        $brands = Brand::all();
        $notes = ScentNote::all();

        return view('products.insert-product', compact('brands', 'notes'));
    }

    // Menampilkan halaman Edit Produk
    public function edit_product(Product $product)
    {
        $brands = Brand::all();
        $notes = ScentNote::all();

        // Load relasi agar data varian dan notes terpilih muncul di form edit
        $product->load(['variants', 'notes']);

        return view('products.edit-product', compact('product', 'brands', 'notes'));
    }
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
            'category' => 'required|in:Designer,Niche,Local',
            'gender_type' => 'required|in:Men,Women,Unisex',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'variants' => 'required|array',
            'variants.size' => 'required|array|min:1',
            'variants.size.*' => 'required|string|max:50',
            'variants.price' => 'required|array|min:1',
            'variants.price.*' => 'required|numeric|min:1',
            'variants.stock' => 'required|array|min:1',
            'variants.stock.*' => 'required|integer|min:0',
            'notes' => 'required|array|min:1',
            'notes.*' => 'exists:scent_notes,id',
            'is_new_arrival' => 'nullable|boolean',
            'discount_percent' => 'nullable|integer|min:0|max:100'
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'brand_id.required' => 'Silakan pilih brand produk.',
            'brand_id.exists' => 'Brand yang dipilih tidak valid.',
            'category.required' => 'Kategori produk wajib dipilih.',
            'gender_type.required' => 'Tipe gender wajib dipilih.',
            'image.image' => 'File harus berupa gambar.',
            'variants.price.*.min' => 'Harga varian minimal 1.',
            'variants.stock.required' => 'Stok varian wajib diisi.',
            'variants.stock.*.required' => 'Stok varian wajib diisi.',
            'variants.stock.*.integer' => 'Stok varian harus berupa angka bulat.',
            'variants.stock.*.min' => 'Stok varian minimal 0.',
            'notes.required' => 'Silakan pilih minimal 1 scent note.',
            'notes.min' => 'Silakan pilih minimal 1 scent note.',
        ]);

        // Handle image upload
        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '-' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('product_image'), $imageName);
        }

        // 1. Simpan Data Produk
        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(), // Slug unik
            'brand_id' => $request->brand_id,
            'category' => $request->category,
            'gender_type' => $request->gender_type,
            'description' => $request->description,
            'image_url' => $imageName,
            'is_new_arrival' => $request->boolean('is_new_arrival'),
            'discount_percent' => $request->discount_percent ?? 0,
        ]);

        // 2. Simpan Varian Dinamis
        $sizes = $request->input('variants.size');
        $prices = $request->input('variants.price');
        $stocks = $request->input('variants.stock');

        foreach ($sizes as $index => $size) {
            $product->variants()->create([
                'size' => $size,
                'price' => $prices[$index],
                'stock' => $stocks[$index],
            ]);
        }

        // 3. Simpan Relasi Scent Notes
        $product->notes()->attach($request->notes);
        
        return redirect()->route('shop')->with('success', 'Product successfully added!');
    }
    public function update(Request $request, Product $product)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
            'category' => 'required|in:Designer,Niche,Local',
            'gender_type' => 'required|in:Men,Women,Unisex',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'variants' => 'required|array',
            'variants.size' => 'required|array|min:1',
            'variants.size.*' => 'required|string|max:50',
            'variants.price' => 'required|array|min:1',
            'variants.price.*' => 'required|numeric|min:1',
            'variants.stock' => 'required|array|min:1',
            'variants.stock.*' => 'required|integer|min:0',
            'notes' => 'required|array|min:1',
            'notes.*' => 'exists:scent_notes,id',
            'is_new_arrival' => 'nullable|boolean',
            'discount_percent' => 'nullable|integer|min:0|max:100'
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'brand_id.required' => 'Silakan pilih brand produk.',
            'category.required' => 'Kategori produk wajib dipilih.',
            'gender_type.required' => 'Tipe gender wajib dipilih.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar yang diizinkan: jpeg, png, jpg, gif, svg.',
            'image.max' => 'Ukuran gambar tidak boleh melebihi 2MB.',
            'variants.required' => 'Minimal harus ada 1 varian.',
            'variants.size.required' => 'Ukuran varian wajib diisi.',
            'variants.size.*.required' => 'Ukuran varian wajib diisi.',
            'variants.price.required' => 'Harga varian wajib diisi.',
            'variants.price.*.required' => 'Harga varian wajib diisi.',
            'variants.price.*.numeric' => 'Harga varian harus berupa angka.',
            'variants.price.*.min' => 'Harga varian minimal 1.',
            'variants.stock.required' => 'Stok varian wajib diisi.',
            'variants.stock.*.required' => 'Stok varian wajib diisi.',
            'variants.stock.*.integer' => 'Stok varian harus berupa angka bulat.',
            'variants.stock.*.min' => 'Stok varian minimal 0.',
            'notes.required' => 'Silakan pilih minimal 1 scent note.',
            'notes.min' => 'Silakan pilih minimal 1 scent note.',
        ]);

        // Handle image upload
        $imageUrl = $product->image_url;
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image_url && file_exists(public_path('product_image/' . $product->image_url))) {
                unlink(public_path('product_image/' . $product->image_url));
            }

            $imageUrl = time() . '-' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('product_image'), $imageUrl);
        }

        // Check old discount
        $oldDiscount = $product->discount_percent ?? 0;

        // 1. Update Data Produk Utama
        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(),
            'brand_id' => $request->brand_id,
            'category' => $request->category,
            'gender_type' => $request->gender_type,
            'description' => $request->description,
            'image_url' => $imageUrl,
            'is_new_arrival' => $request->boolean('is_new_arrival'),
            'discount_percent' => $request->discount_percent ?? 0,
        ]);

        $newDiscount = $product->discount_percent ?? 0;
        if ($newDiscount > 0 && $newDiscount > $oldDiscount) {
            \App\Jobs\NotifyUsersOfProductDiscountJob::dispatch($product);
        }

        // 2. Hapus semua varian lama dan buat yang baru
        $product->variants()->delete();

        $sizes = $request->input('variants.size');
        $prices = $request->input('variants.price');
        $stocks = $request->input('variants.stock');

        foreach ($sizes as $index => $size) {
            $product->variants()->create([
                'size' => $size,
                'price' => $prices[$index],
                'stock' => $stocks[$index],
            ]);
        }

        // 3. Update Scent Notes
        $product->notes()->sync($request->notes);

        return redirect()->route('shop')->with('success', 'Product successfully updated!');
    }

    public function destroy(Product $product)
    {
        if (auth()->user()?->role !== 'admin') {
            return redirect()->back()->with('error', 'You do not have permission to delete this product.');
        }

        $product->delete();

        return redirect()->route('shop')->with('success', 'Produk berhasil dipindahkan ke tempat sampah!');
    }
}
