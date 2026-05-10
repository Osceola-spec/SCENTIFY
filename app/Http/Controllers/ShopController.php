<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use App\Models\ScentNote;
use App\Models\ProductVariant;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    public function show()
    {
        $brands = Brand::all();
        $products = Product::with(['brand', 'variants'])->latest()->paginate(12);

        return view('shop', compact('products', 'brands'));
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
        // 1. Simpan Data Produk
        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(), // Slug unik
            'brand_id' => $request->brand_id,
            'category' => $request->category,
            'gender_type' => $request->gender_type,
            'description' => $request->description,
            'image_url' => $request->image_url,
        ]);

        // 2. Simpan Varian 50ml
        $product->variants()->create([
            'size' => '50ml',
            'price' => $request->price_50ml,
            'stock' => $request->stock_50ml,
        ]);

        // 3. Simpan Varian 100ml (Jika diisi)
        if ($request->price_100ml && $request->stock_100ml) {
            $product->variants()->create([
                'size' => '100ml',
                'price' => $request->price_100ml,
                'stock' => $request->stock_100ml,
            ]);
        }

        // 4. Simpan Relasi Scent Notes
        $product->notes()->attach($request->notes);

        return redirect()->route('shop')->with('success', 'Produk berhasil ditambahkan!');
    }
    public function update(Request $request, Product $product)
    {
        // 1. Update Data Produk Utama
        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(),
            'brand_id' => $request->brand_id,
            'category' => $request->category,
            'gender_type' => $request->gender_type,
            'description' => $request->description,
            'image_url' => $request->image_url,
        ]);

        // 2. Update atau Create Varian 50ml
        $product->variants()->updateOrCreate(
            ['size' => '50ml'], // Cari yang ukurannya 50ml
            ['price' => $request->price_50ml, 'stock' => $request->stock_50ml] // Update datanya
        );

        // 3. Update atau Create Varian 100ml
        if ($request->price_100ml && $request->stock_100ml) {
            $product->variants()->updateOrCreate(
                ['size' => '100ml'],
                ['price' => $request->price_100ml, 'stock' => $request->stock_100ml]
            );
        }

        // 4. Sinkronisasi Scent Notes (Hapus yang lama, ganti yang baru)
        $product->notes()->sync($request->notes);

        return redirect()->route('shop')->with('success', 'Produk berhasil diperbarui!');
    }

    // Memproses Hapus Produk
    public function destroy(Product $product)
    {
        // Relasi varian dan notes akan otomatis terhapus karena kita menggunakan onDelete('cascade') di file migration
        $product->delete();

        return redirect()->route('shop')->with('success', 'Produk berhasil dihapus!');
    }
}