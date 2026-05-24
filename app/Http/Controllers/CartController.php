<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductVariant;

class CartController extends Controller
{
    // Menampilkan halaman Keranjang
    public function index()
    {
        $cart = session()->get('cart', []);

        // Hitung total harga
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart', compact('cart', 'total'));
    }

    // Memasukkan produk ke keranjang
    public function add(Request $request, $variantId)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu untuk menambah produk ke keranjang.');
        }

        // Tangkap kuantitas dari input form, default 1 jika tidak ada
        $quantityToAdd = (int) $request->input('quantity', 1);

        // Ambil data varian (beserta data produk utamanya)
        $variant = ProductVariant::with('product')->findOrFail($variantId);

        // Ambil data keranjang saat ini di session (jika kosong, buat array baru)
        $cart = session()->get('cart', []);

        // Jika produk dengan varian ini sudah ada di keranjang, tambahkan dengan kuantitas baru
        if (isset($cart[$variantId])) {
            $cart[$variantId]['quantity'] += $quantityToAdd;
        } else {
            // Jika belum ada, masukkan sebagai item baru dengan kuantitas yang direquest
            $cart[$variantId] = [
                'variant_id' => $variant->id,
                'product_name' => $variant->product->name,
                'brand_name' => $variant->product->brand->name ?? 'Scentify',
                'size' => $variant->size,
                'price' => $variant->price,
                'image_url' => $variant->product->image_url,
                'quantity' => $quantityToAdd
            ];
        }

        // Simpan kembali ke session
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    // Menghapus item dari keranjang
    public function remove($variantId)
    {
        $cart = session()->get('cart');

        if (isset($cart[$variantId])) {
            unset($cart[$variantId]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Produk dihapus dari keranjang.');
    }
}