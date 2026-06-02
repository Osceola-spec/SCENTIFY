<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use App\Models\ScentNote;
use App\Models\ProductImage;
use Illuminate\Support\Str;
use App\Events\ProductUpdated;
use App\Events\ProductDeleted;

class ProductController extends Controller
{
    public function create()
    {
        $brands = Brand::orderBy('name')->get();
        $notes  = ScentNote::orderBy('name')->get();
        return view('products.insert-product', compact('brands', 'notes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'brand_id'          => 'required|exists:brands,id',
            'category'          => 'required|in:Designer,Niche,Local',
            'gender_type'       => 'required|in:Men,Women,Unisex',
            'description'       => 'required|string',
            'image'             => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'extra_images.*'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'variants.size'     => 'required|array|min:1',
            'variants.price'    => 'required|array',
            'variants.stock'    => 'required|array',
        ]);

        // Upload gambar utama
        $mainImageName = time() . '_' . $request->file('image')->getClientOriginalName();
        $request->file('image')->move(public_path('product_image'), $mainImageName);

        $slug = Str::slug($request->name) . '-' . time();

        $searchContext = "Parfum {$request->name}, kategori {$request->category} untuk {$request->gender_type}. Deskripsi: {$request->description}";

        $product = Product::create([
            'brand_id'    => $request->brand_id,
            'name'        => $request->name,
            'slug'        => $slug,
            'category'    => $request->category,
            'gender_type' => $request->gender_type,
            'description' => $request->description,
            'image_url'   => $mainImageName,
            'search_context' => $searchContext,
        ]);

        // Simpan gambar utama ke product_images
        ProductImage::create([
            'product_id' => $product->id,
            'image_url'  => $mainImageName,
            'order'      => 0,
            'is_primary' => true,
        ]);

        // Upload extra images
        if ($request->hasFile('extra_images')) {
            foreach ($request->file('extra_images') as $index => $file) {
                $fileName = time() . '_' . ($index + 1) . '_' . $file->getClientOriginalName();
                $file->move(public_path('product_image'), $fileName);
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url'  => $fileName,
                    'order'      => $index + 1,
                    'is_primary' => false,
                ]);
            }
        }

        // Simpan notes
        if ($request->has('notes')) {
            $product->notes()->sync($request->notes);
        }

        // Simpan variants
        foreach ($request->variants['size'] as $i => $size) {
            $product->variants()->create([
                'size'  => $size,
                'price' => $request->variants['price'][$i],
                'stock' => $request->variants['stock'][$i],
            ]);
        }

        broadcast(new \App\Events\ProductAdded($product));

        return redirect()->route('admin.inventory')
            ->with('success', 'Produk berhasil ditambahkan ke katalog!');
    }

    public function edit(Product $product)
    {
        $brands = Brand::orderBy('name')->get();
        $notes  = ScentNote::orderBy('name')->get();
        $product->load(['variants', 'images', 'notes']);
        return view('products.edit-product', compact('product', 'brands', 'notes'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'brand_id'       => 'required|exists:brands,id',
            'category'       => 'required|in:Designer,Niche,Local',
            'gender_type'    => 'required|in:Men,Women,Unisex',
            'description'    => 'required|string',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'extra_images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'variants'       => 'nullable|array',
        ]);

        // Update gambar utama jika ada upload baru
        if ($request->hasFile('image')) {
            $mainImageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('product_image'), $mainImageName);

            $product->update(['image_url' => $mainImageName]);

            // Update atau buat ulang primary image
            $product->images()->where('is_primary', true)->delete();
            \App\Models\ProductImage::create([
                'product_id' => $product->id,
                'image_url'  => $mainImageName,
                'order'      => 0,
                'is_primary' => true,
            ]);
        }

        // Tambah extra images baru
        if ($request->hasFile('extra_images')) {
            $lastOrder = $product->images()->max('order') ?? 0;
            foreach ($request->file('extra_images') as $index => $file) {
                $fileName = time() . '_' . ($index + 1) . '_' . $file->getClientOriginalName();
                $file->move(public_path('product_image'), $fileName);
                \App\Models\ProductImage::create([
                    'product_id' => $product->id,
                    'image_url'  => $fileName,
                    'order'      => $lastOrder + $index + 1,
                    'is_primary' => false,
                ]);
            }
        }

        // Hapus gambar yang dipilih untuk dihapus
        if ($request->has('delete_images')) {
            \App\Models\ProductImage::whereIn('id', $request->delete_images)
                ->where('product_id', $product->id)
                ->where('is_primary', false)
                ->delete();
        }

        // Update data utama produk
        $product->update([
            'brand_id'    => $request->brand_id,
            'name'        => $request->name,
            'category'    => $request->category,
            'gender_type' => $request->gender_type,
            'description' => $request->description,
        ]);

        // Update Notes (Aroma)
        if ($request->has('notes')) {
            $product->notes()->sync($request->notes);
        }

        // ========================================================
        // ✨ FIX BARU: UPDATE HARGA & VARIAN (Gaya Parallel Array)
        // ========================================================
        if ($request->has('variants') && isset($request->variants['price'])) {
            // Ambil semua varian produk ini yang ada di database saat ini
            $existingVariants = $product->variants;

            foreach ($request->variants['price'] as $index => $price) {
                $size = $request->variants['size'][$index] ?? null;
                $stock = $request->variants['stock'][$index] ?? 0;

                if (isset($existingVariants[$index])) {
                    // 1. Jika varian lama ada di index ini -> UPDATE
                    $existingVariants[$index]->update([
                        'size'  => $size,
                        'price' => $price,
                        'stock' => $stock,
                    ]);
                } else {
                    // 2. Jika admin menambah baris baru di UI -> CREATE
                    \App\Models\ProductVariant::create([
                        'product_id' => $product->id,
                        'size'       => $size,
                        'price'      => $price,
                        'stock'      => $stock,
                    ]);
                }
            }

            // 3. Jika admin menghapus varian di UI -> DELETE sisa varian di database
            if (count($existingVariants) > count($request->variants['price'])) {
                for ($i = count($request->variants['price']); $i < count($existingVariants); $i++) {
                    $existingVariants[$i]->delete();
                }
            }
        }
        // ========================================================

        // Load ulang relasi agar WebSocket membawa data paling fresh
        $product->load(['brand', 'variants']);

        // Tembakkan sinyal ke WebSocket Reverb
        broadcast(new \App\Events\ProductUpdated($product));

        return redirect()->route('admin.inventory')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        $deletedId = $product->id;
        $product->delete();

        broadcast(new ProductDeleted($deletedId));

        return redirect()->back()->with('success', 'Produk berhasil dipindahkan ke tempat sampah!');
    }
}
