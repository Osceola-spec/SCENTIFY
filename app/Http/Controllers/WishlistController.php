<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // 1. Menampilkan halaman Wishlist
    public function index()
    {
        $wishlists = Wishlist::with(['product.brand', 'product.variants'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(12);

        return view('wishlist', compact('wishlists'));
    }

    // 2. Fungsi Toggle Real-Time (AJAX)
    public function toggle(Request $request, $productId)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if ($wishlist) {
            $wishlist->delete(); // Jika sudah ada, hapus
            $count = Wishlist::where('user_id', Auth::id())->count();
            return response()->json(['status' => 'removed', 'count' => $count]);
        } else {
            Wishlist::create([ // Jika belum ada, tambahkan
                'user_id' => Auth::id(),
                'product_id' => $productId
            ]);
            $count = Wishlist::where('user_id', Auth::id())->count();
            return response()->json(['status' => 'added', 'count' => $count]);
        }
    }
}