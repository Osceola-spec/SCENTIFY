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

        // 1. Tangkap filter status dari URL, jika kosong default ke 'processing'
        $statusFilter = $request->query('status', 'processing');

        $orders = Order::with([
                'items.variant.product.brand',
                'items.variant.product.images',
            ])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc') // Mengunci urutan dari yang paling baru
            ->when($statusFilter === 'processing', function ($q) {
                // Dalam Proses = Sudah dibayar atau sedang dikirim kurir
                $q->whereIn('status', ['Paid', 'Processing', 'Shipped']);
            })
            ->when($statusFilter === 'unpaid', function ($q) {
                // Perlu Dibayar = Masih pending menunggu pembayaran
                $q->where('status', 'Pending');
            })
            ->when($statusFilter === 'history', function ($q) {
                // Riwayat = Selesai diterima atau dibatalkan
                $q->whereIn('status', ['Completed', 'Cancelled']);
            })
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
                
                // 1. 🔥 AMAN: Mengubah status menjadi 'Paid'
                DB::table('orders')
                    ->where('order_number', $orderNumber)
                    ->update([
                        'status' => 'Paid', 
                        'updated_at' => now()
                    ]);

                // 2. Kirim email nota otomatis via Brevo saat itu juga!
                $customerEmail = $order->user->email ?? Auth::user()->email ?? null;
                if ($customerEmail) {
                    try {
                        Mail::to($customerEmail)->send(new OrderConfirmationMail($order));
                        \Log::info("Email sukses dikirim ke {$customerEmail} dari rute pengalihan sukses.");
                    } catch (\Exception $e) {
                        \Log::error("Gagal mengirim email konfirmasi: " . $e->getMessage());
                    }
                }
            }
        }

        // Alihkan halaman user ke daftar transaksi mereka dengan pesan sukses
        return redirect()->route('orders.index')->with('success', 'Pembayaran Anda berhasil diverifikasi! Nota resmi telah dikirim ke email Anda.');
    }

    /**
     * Mengarahkan pesanan Pending ke halaman pembayaran dengan Snap Token Baru
     */
    public function payNow($order_number)
    {
        if (!\Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // 1. Ambil detail order
        $order = Order::with(['items.variant.product', 'user'])
            ->where('order_number', $order_number)
            ->where('user_id', \Auth::id())
            ->firstOrFail();

        if ($order->status !== 'Pending') {
            return redirect()->route('orders.index')->with('error', 'Pesanan ini sudah diproses atau dibatalkan.');
        }

        // 2. Konfigurasi SDK Midtrans (Gunakan Config bawaan atau manually set jika tidak ada class config)
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // 3. Susun parameter transaksi untuk Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => (int) $order->total_amount,
            ],
            'customer_details' => [
                'first_name' => $order->user->name ?? 'Pelanggan Scentify',
                'email' => $order->user->email,
            ],
        ];

        // 4. Generate atau dapatkan Snap Token Baru secara aman
        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
        } catch (\Exception $e) {
            // Jika token gagal digenerate karena order_id duplikat di sandbox Midtrans, 
            // Anda bisa mengakali dengan menambahkan suffix timestamp, namun untuk production pastikan order_id unik.
            \Log::error('Midtrans Error: ' . $e->getMessage());
            return redirect()->route('orders.index')->with('error', 'Gagal terhubung ke gateway pembayaran: ' . $e->getMessage());
        }

        // 5. Lempar variabel $order dan $snapToken ke view
        return view('payment', compact('order', 'snapToken'));
    }
}