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

        $statusQuery = $request->query('status');

        $orders = Order::with(['items.variant.product.brand'])
            ->where('user_id', Auth::id())
            ->latest()
            ->when($statusQuery === 'active', function ($q) {
                // Ubah 'Processing' menjadi 'Paid' untuk pencarian di database
                $q->whereIn('status', ['Pending', 'Paid', 'Shipped']);
            })
            ->when($statusQuery && !in_array($statusQuery, ['all', 'active']), function ($q) use ($statusQuery) {
                // Jika URL memiliki ?status=Processing, kita cari 'Paid' di database
                $searchStatus = $statusQuery === 'Processing' ? 'Paid' : $statusQuery;
                $q->where('status', $searchStatus);
            })
            ->paginate(5)
            ->withQueryString();

        // Modifikasi status 'Paid' menjadi 'Processing' sebelum dikirim ke View Customer
        $orders->getCollection()->transform(function ($order) {
            if ($order->status === 'Paid') {
                $order->status = 'Processing';
            }
            return $order;
        });

        return view('orders.orders', compact('orders'));
    }

    public function show($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $order = Order::with(['items.variant.product.brand'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        // Ubah 'Paid' dari database menjadi 'Processing' agar view menampilkannya dengan benar
        if ($order->status === 'Paid') {
            $order->status = 'Processing';
        }

        return view('orders.show', compact('order'));
    }
}