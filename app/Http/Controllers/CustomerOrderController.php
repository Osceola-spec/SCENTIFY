<?php

// =========================================================================
// 1. FILE CONTROLLER: app/Http/Controllers/CustomerOrderController.php
// =========================================================================

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class CustomerOrderController extends Controller
{
    /**
     * Menampilkan daftar riwayat pesanan milik pelanggan yang sedang login.
     * Menerima query status filter (all, active) untuk penyaringan dinamis.
     */
    public function index(Request $request)
    {
        // Pastikan pengguna telah terautentikasi
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        
        // Mulai query dengan memuat relasi items, produk, dan brand untuk efisiensi n+1 query
        $query = Order::with(['items.variant.product.brand'])
            ->where('user_id', $user->id)
            ->latest();

        // Logika Filter Status sesuai tombol di UI Canvas
        $statusFilter = $request->query('status');

        if ($statusFilter === 'active') {
            // Pesanan aktif/berlangsung adalah pesanan yang belum selesai atau dibatalkan
            $query->whereIn('status', ['Pending', 'Processing', 'Shipped']);
        } elseif ($statusFilter && $statusFilter !== 'all') {
            // Jika ada filter spesifik status tertentu di masa mendatang
            $query->where('status', $statusFilter);
        }

        // Ambil data dengan paginasi 5 item per halaman
        $orders = $query->paginate(5)->withQueryString();

        return view('orders.orders', compact('orders'));
    }

    /**
     * Menampilkan detail spesifik dari satu pesanan kustomer.
     */
    public function show($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $order = Order::with(['items.variant.product.brand'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('orders.show', compact('order'));
    }
}