<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'order_id'      => 'required|exists:orders,id',
            'order_item_id' => 'required|exists:order_items,id',
            'product_id'    => 'required|exists:products,id',
            'rating'        => 'required|integer|min:1|max:5',
            'title'         => 'nullable|string|max:100',
            'comment'       => 'nullable|string|max:1000',
        ]);

        // Pastikan order milik user yang login
        $order = Order::where('id', $request->order_id)
            ->where('user_id', Auth::id())
            ->where('status', 'Completed')
            ->firstOrFail();

        // Pastikan produk ada di dalam order tersebut
        $productInOrder = $order->items()
            ->where('id', $request->order_item_id)
            ->whereHas('variant', fn($q) => $q->where('product_id', $request->product_id))
            ->exists();

        if (!$productInOrder) {
            return response()->json(['message' => 'Produk tidak ditemukan dalam pesanan ini.'], 403);
        }

        // Cek sudah pernah review atau belum
        $existing = Review::where([
            'user_id'       => Auth::id(),
            'order_item_id' => $request->order_item_id,
        ])->first();

        if ($existing) {
            return response()->json(['message' => 'You have already reviewed this product.'], 409);
        }

        $review = Review::create([
            'user_id'       => Auth::id(),
            'product_id'    => $request->product_id,
            'order_id'      => $request->order_id,
            'order_item_id' => $request->order_item_id,
            'rating'        => $request->rating,
            'title'         => $request->title,
            'comment'       => $request->comment,
        ]);

        return response()->json([
            'message' => 'Review saved successfully!',
            'review'  => $review,
        ]);
    }
}