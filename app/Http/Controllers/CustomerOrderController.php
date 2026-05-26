<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class CustomerOrderController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $orders = Order::with([
                'items.variant.product.brand',
                'items.variant.product.images',
            ])
            ->where('user_id', Auth::id())
            ->latest()
            ->when($request->query('status') === 'active', function ($q) {
                $q->whereIn('status', ['Pending', 'Processing', 'Shipped']);
            })
            ->when(
                $request->query('status') && !in_array($request->query('status'), ['all', 'active']),
                function ($q) use ($request) {
                    $q->where('status', $request->query('status'));
                }
            )
            ->paginate(5)
            ->withQueryString();

        // Load review secara terpisah setelah collection terbentuk
        // agar tidak mengganggu relasi lain
        $orders->each(function ($order) {
            $order->items->each(function ($item) {
                $item->loadMissing('review');
            });
        });

        return view('orders.orders', compact('orders'));
    }

    public function show($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $order = Order::with([
                'items.variant.product.brand',
                'items.variant.product.images',
                'user',
            ])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        // Load review terpisah
        $order->items->each(function ($item) {
            $item->loadMissing('review');
        });

        return view('orders.show', compact('order'));
    }

    public function cancel($id)
    {
        // Cari order milik user yang sedang login
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        // Pastikan hanya pesanan berstatus 'Pending' yang bisa dibatalkan
        if ($order->status !== 'Pending') {
            // Jika menggunakan toast/sweetalert dari session, Anda bisa menyesuaikan flash message ini
            return back()->with('error', 'Hanya pesanan yang belum dibayar yang dapat dibatalkan.');
        }

        // Ubah status menjadi Cancelled
        $order->update([
            'status' => 'Cancelled'
        ]);

        /* * OPSIONAL: Jika sistem Anda memotong stok (stock deduction) pada saat 
         * checkout (bukan saat dibayar), jangan lupa kembalikan stoknya di sini.
         * Contoh:
         * foreach ($order->items as $item) {
         * $item->variant->increment('stock', $item->quantity);
         * }
         */

        return back()->with('success', 'Pesanan Anda berhasil dibatalkan.');
    }
}