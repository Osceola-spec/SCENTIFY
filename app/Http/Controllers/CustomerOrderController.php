<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // ✨ Ditambahkan untuk Query Builder aman

// 🌟 IMPORT UNTUK KEPERLUAN EMAIL DI SINI
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;

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
                // Sesuai daftar di databasemu, status aktif adalah Pending, Paid, dan Shipped
                $q->whereIn('status', ['Pending', 'Paid', 'Shipped']);
            })
            ->when(
                $request->query('status') && !in_array($request->query('status'), ['all', 'active']),
                function ($q) use ($request) {
                    $q->where('status', $request->query('status'));
                }
            )
            ->paginate(5)
            ->withQueryString();

        // Load review secara terpisah setelah collection terbentuk agar tidak mengganggu relasi lain
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
            return back()->with('error', 'Hanya pesanan yang belum dibayar yang dapat dibatalkan.');
        }

        // Ubah status menjadi Cancelled (Ada di ENUM databasemu)
        DB::table('orders')
            ->where('id', $id)
            ->update([
                'status' => 'Cancelled',
                'updated_at' => now()
            ]);

        return back()->with('success', 'Pesanan Anda berhasil dibatalkan.');
    }

    /**
     * 🌟 METHOD KHUSUS DEMO: Dipanggil otomatis saat user sukses bayar di Midtrans
     */
    public function paymentFinished(Request $request)
    {
        // Tangkap nomor order dari parameter yang dikirim balik oleh Midtrans (?order_id=ORD-XXXX)
        $orderNumber = $request->query('order_id');

        if ($orderNumber) {
            // Cari data ordernya di database lengkap dengan data usernya
            $order = Order::with('user')->where('order_number', $orderNumber)->first();

            // Jalankan kode ini HANYA jika status lamanya masih 'Pending'
            if ($order && $order->status === 'Pending') {
                
                // 1. 🔥 AMAN: Mengubah status menjadi 'Paid' (Sesuai dengan opsi ENUM di tabel orders-mu)
                DB::table('orders')
                    ->where('order_number', $orderNumber)
                    ->update([
                        'status' => 'Paid', 
                        'updated_at' => now()
                    ]);

                // 2. Kirim email nota otomatis via Brevo saat itu juga!
                $customerEmail = $order->user->email ?? Auth::user()->email ?? null;
                if ($customerEmail) {
                    Mail::to($customerEmail)->send(new OrderConfirmationMail($order));
                    \Log::info("Email sukses dikirim ke {$customerEmail} dari rute pengalihan sukses.");
                }
            }
        }

        // Alihkan halaman user ke daftar transaksi mereka dengan pesan sukses
        return redirect()->route('orders.index')->with('success', 'Pembayaran Anda berhasil diverifikasi! Nota resmi telah dikirim ke email Anda.');
    }
}