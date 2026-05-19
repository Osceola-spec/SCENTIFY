<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use App\Models\ScentNote;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // 1. Menampilkan Halaman Tambah Produk
    public function create()
    {
        // Ambil data brand dan scent note untuk ditampilkan di form
        $brands = Brand::all();
        $notes = ScentNote::all();

        // Mengarah ke file resources/views/products/insert-product.blade.php
        return view('products.insert-product', compact('brands', 'notes'));
    }

    // 2. Memproses Simpan Produk Baru ke Database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
            'category' => 'required|string|max:50',
            'gender_type' => 'required|string|max:50',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'notes' => 'nullable|array',
            'notes.*' => 'exists:scent_notes,id',
            'variants.size' => 'required|array|min:1',
            'variants.price' => 'required|array|min:1',
            'variants.stock' => 'required|array|min:1',
            'variants.size.*' => 'required|string|max:50',
            'variants.price.*' => 'required|numeric|min:0',
            'variants.stock.*' => 'required|integer|min:0',
        ]);

        return DB::transaction(function () use ($request) {
            $fileName = null;
            if ($request->hasFile('image')) {
                if (!file_exists(public_path('product_image'))) {
                    mkdir(public_path('product_image'), 0755, true);
                }
                $file = $request->file('image');
                $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9\-\.]/', '', $file->getClientOriginalName());
                $file->move(public_path('product_image'), $fileName);
            }

            $product = Product::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name) . '-' . time(),
                'brand_id' => $request->brand_id,
                'category' => $request->category ?? 'Designer',
                'gender_type' => $request->gender_type ?? 'Unisex',
                'description' => $request->description,
                'image_url' => $fileName,
            ]);

            $product->notes()->sync($request->input('notes', []));

            $variants = $request->input('variants', []);
            if (is_array($variants['size'] ?? null)) {
                foreach ($variants['size'] as $index => $size) {
                    $price = $variants['price'][$index] ?? null;
                    $stock = $variants['stock'][$index] ?? null;

                    if (!$size || is_null($price) || is_null($stock)) {
                        continue;
                    }

                    $product->variants()->create([
                        'size' => $size,
                        'price' => $price,
                        'stock' => $stock,
                    ]);
                }
            }

            return redirect()->route('admin.inventory')->with('success', 'Produk berhasil ditambahkan!');
        });
    }

    // 3. Menampilkan Halaman Edit Produk
    public function edit(Product $product)
    {
        $brands = Brand::all();
        $notes = ScentNote::all();
        $product->load('notes');

        // Mengarah ke file resources/views/products/edit-product.blade.php
        return view('products.edit-product', compact('product', 'brands', 'notes'));
    }

    // 4. Memproses Update Data Produk
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
            'category' => 'required|string|max:50',
            'gender_type' => 'required|string|max:50',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'notes' => 'nullable|array',
            'notes.*' => 'exists:scent_notes,id',
            'variants.size' => 'required|array|min:1',
            'variants.price' => 'required|array|min:1',
            'variants.stock' => 'required|array|min:1',
            'variants.size.*' => 'required|string|max:50',
            'variants.price.*' => 'required|numeric|min:0',
            'variants.stock.*' => 'required|integer|min:0',
        ]);

        return DB::transaction(function () use ($request, $product) {
            $fileName = $product->image_url;
            if ($request->hasFile('image')) {
                if (!file_exists(public_path('product_image'))) {
                    mkdir(public_path('product_image'), 0755, true);
                }
                $file = $request->file('image');
                $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9\-\.]/', '', $file->getClientOriginalName());
                $file->move(public_path('product_image'), $fileName);
            }

            $product->update([
                'name' => $request->name,
                'brand_id' => $request->brand_id,
                'category' => $request->category,
                'gender_type' => $request->gender_type,
                'description' => $request->description,
                'image_url' => $fileName,
            ]);

            $product->notes()->sync($request->input('notes', []));

            // Hapus semua varian lama dan buat ulang dari input baru
            $product->variants()->delete();
            $variants = $request->input('variants', []);
            if (is_array($variants['size'] ?? null)) {
                foreach ($variants['size'] as $index => $size) {
                    $price = $variants['price'][$index] ?? null;
                    $stock = $variants['stock'][$index] ?? null;

                    if (!$size || is_null($price) || is_null($stock)) {
                        continue;
                    }

                    $product->variants()->create([
                        'size' => $size,
                        'price' => $price,
                        'stock' => $stock,
                    ]);
                }
            }

            return redirect()->route('admin.inventory')->with('success', 'Produk berhasil diperbarui!');
        });
    }

    // 5. Menghapus Produk
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.inventory')->with('success', 'Produk berhasil dihapus!');
    }

    public function index(Request $request)
    {
        // 1. Ambil data produk dasar yang aktif (belum di-soft-delete)
        $query = Product::query();

        // 2. JIKA ada filter brand dari URL (?brand=ID), saring produknya
        // Asumsinya nama kolom relasi brand di tabel produk Anda adalah 'brand_id'
        if ($request->has('brand') && $request->brand != '') {
            $query->where('brand_id', $request->brand);
        }

        // 3. Ambil hasil produknya
        $products = $query->latest()->get();

        // 4. Ambil semua brand untuk opsi filter di halaman shop (jika ada sidebar filter)
        $brands = Brand::all();

        // 5. Lempar ke halaman view shop Anda
        return view('shop', compact('products', 'brands'));
    }
}
