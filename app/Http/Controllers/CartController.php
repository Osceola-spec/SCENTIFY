<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\Promotion;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        // Sync prices with latest database values
        if (!empty($cart)) {
            $variantIds = array_keys($cart);
            $variants = ProductVariant::with('product')->whereIn('id', $variantIds)->get()->keyBy('id');
            
            $now = now();
            $promo = Promotion::where('is_active', true)
                ->where(function($q) use ($now) { $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now); })
                ->first();

            $changed = false;
            foreach ($cart as $variantId => &$item) {
                if (isset($variants[$variantId])) {
                    $variant = $variants[$variantId];
                    $originalPrice = $variant->price;
                    $finalPrice = $originalPrice;
                    
                    if ($promo && ($promo->applies_to_all || $promo->product_id == $variant->product_id)) {
                        $dv = (float) $promo->discount_value;
                        $finalPrice = $promo->discount_type === 'percent'
                            ? max(0, round($originalPrice * (1 - $dv / 100)))
                            : max(0, round($originalPrice - $dv));
                    }

                    if ($item['price'] != $finalPrice || $item['original_price'] != $originalPrice) {
                        $item['price'] = $finalPrice;
                        $item['original_price'] = $originalPrice;
                        $changed = true;
                    }
                }
            }
            if ($changed) {
                session()->put('cart', $cart);
            }
        }

        // Hitung total harga
        $total = 0;
        $totalDiscount = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
            if (isset($item['original_price']) && $item['original_price'] > $item['price']) {
                $totalDiscount += ($item['original_price'] - $item['price']) * $item['quantity'];
            }
        }

        return view('cart', compact('cart', 'total', 'totalDiscount'));
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

        // Hitung harga setelah diskon jika ada promo aktif
        $originalPrice = $variant->price;
        $finalPrice = $originalPrice;
        $now = now();
        $promo = Promotion::where('is_active', true)
            ->where(function($q) use ($now) { $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now); })
            ->first();
        if ($promo && ($promo->applies_to_all || $promo->product_id == $variant->product_id)) {
            $dv = (float) $promo->discount_value;
            $finalPrice = $promo->discount_type === 'percent'
                ? max(0, round($originalPrice * (1 - $dv / 100)))
                : max(0, round($originalPrice - $dv));
        }

        // Ambil data keranjang saat ini di session (jika kosong, buat array baru)
        $cart = session()->get('cart', []);

        // Jika produk dengan varian ini sudah ada di keranjang, tambahkan dengan kuantitas baru
        if (isset($cart[$variantId])) {
            $cart[$variantId]['quantity'] += $quantityToAdd;
        } else {
            // Jika belum ada, masukkan sebagai item baru dengan kuantitas yang direquest
            $cart[$variantId] = [
                'variant_id'   => $variant->id,
                'product_name' => $variant->product->name,
                'brand_name'   => $variant->product->brand->name ?? 'Scentify',
                'size'         => $variant->size,
                'price'        => $finalPrice,
                'original_price' => $originalPrice,
                'image_url'    => $variant->product->image_url,
                'quantity'     => $quantityToAdd,
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