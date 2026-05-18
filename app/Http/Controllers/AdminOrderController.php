<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class AdminOrderController extends Controller
{
    public function index(Request $request)
{
    // Kita panggil data user dan items dasar saja dulu untuk memutus rantai loop
    $query = Order::with(['user', 'items']);

    // Fitur Filter Status (Bawaan)
    if ($request->has('status') && $request->status != '') {
        $query->where('status', $request->status);
    }

    // Fitur Pencarian (Bawaan)
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('order_number', 'LIKE', "%{$search}%")
              ->orWhere('customer_name', 'LIKE', "%{$search}%");
        });
    }

    $orders = $query->latest()->paginate(10)->withQueryString();
    
    return view('admin.orders.index', compact('orders'));
}
public function show($id)
    {
        // Ambil data order berdasarkan ID beserta relasi item, varian, dan data user pembelinya
        $order = Order::with(['items.variant.product', 'user'])->findOrFail($id);

        // Arahkan ke file Blade detail yang berada di resources/views/admin/orders/show.blade.php
        return view('admin.orders.show', compact('order'));
    }
    public function updateStatus(Request $request, $id)
    {
        // 1. Validasi input agar data status dan resi yang masuk sesuai aturan
        $request->validate([
            'status' => 'required|in:Pending,Processing,Shipped,Completed,Cancelled',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        // 2. Cari data pesanan berdasarkan ID
        $order = Order::findOrFail($id);

        // 3. Update data ke database
        $order->update([
            'status' => $request->status,
            'tracking_number' => $request->tracking_number,
        ]);

        // 4. Kembalikan ke halaman detail dengan pesan sukses
        return redirect()->back()->with('success', 'Status operasional pesanan berhasil diperbarui!');
    }
}