<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // 1. Menampilkan Halaman Tambah Produk
    public function create()
    {
        // Ambil data brand untuk ditampilkan di dropdown form
        $brands = Brand::all();

        // Mengarah ke file resources/views/products/insert-product.blade.php
        return view('products.insert-product', compact('brands'));
    }

    // 2. Memproses Simpan Produk Baru ke Database
    public function store(Request $request)
    {
        // Simpan data produk ke database
        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(),
            'brand_id' => $request->brand_id,
            'category' => $request->category ?? 'Designer', // Default jika kosong
            'gender_type' => $request->gender_type ?? 'Unisex',
            'description' => $request->description,
            'image_url' => $request->image_url,
        ]);

        // Arahkan kembali ke halaman shop atau inventory
        return redirect()->route('shop')->with('success', 'Produk berhasil ditambahkan!');
    }

    // 3. Menampilkan Halaman Edit Produk
    public function edit(Product $product)
    {
        $brands = Brand::all();

        // Mengarah ke file resources/views/products/edit-product.blade.php
        return view('products.edit-product', compact('product', 'brands'));
    }

    // 4. Memproses Update Data Produk
    public function update(Request $request, Product $product)
    {
        $product->update([
            'name' => $request->name,
            'brand_id' => $request->brand_id,
            'category' => $request->category,
            'gender_type' => $request->gender_type,
            'description' => $request->description,
            'image_url' => $request->image_url,
        ]);

        return redirect()->route('shop')->with('success', 'Produk berhasil diperbarui!');
    }

    // 5. Menghapus Produk
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('shop')->with('success', 'Produk berhasil dihapus!');
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
