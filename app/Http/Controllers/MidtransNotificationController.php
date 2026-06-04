<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;

class MidtransNotificationController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Konfigurasi Midtrans
        Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        try {
            // 2. Tangkap data notifikasi resmi dari Midtrans
            $notif = new Notification();

            $transactionStatus = $notif->transaction_status;
            $orderNumber       = $notif->order_id; 
            $paymentType       = $notif->payment_type;

            // 3. Cari data Order di database beserta relasi user-nya agar lebih cepat
            $order = Order::with('user')->where('order_number', $orderNumber)->first();

            if (!$order) {
                return response()->json(['message' => 'Order tidak ditemukan'], 404);
            }

            // 🌟 AMANKAN STATUS SEBELUM DATABASE DI-UPDATE (Solusi Bug)
            $previousStatus = $order->status; 

            // 4. Logika Perubahan Status dari Pending ke Processing (Paid)
            if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
                
                // Ubah status order menjadi Paid di database karena Enum di tabel tidak mendukung Processing
                $order->update([
                    'status' => 'Paid', 
                    'payment_method' => $paymentType
                ]);
                
                \Log::info("Order {$orderNumber} berhasil dibayar menggunakan {$paymentType}. Status lama: {$previousStatus} -> Status baru: Processing.");

                // TRIGGER EMAIL: Kirim email HANYA jika status lamanya beneran bukan 'Paid'
                if ($previousStatus !== 'Paid') {
                    // Ambil email dari relasi user
                    $customerEmail = $order->user->email ?? null;

                    if ($customerEmail) {
                        // Proses pengiriman email secara otomatis lewat Brevo
                        Mail::to($customerEmail)->send(new OrderConfirmationMail($order));
                        \Log::info("Email konfirmasi pesanan untuk Order {$orderNumber} berhasil dikirim ke {$customerEmail}.");
                    } else {
                        \Log::warning("Email tidak terkirim untuk Order {$orderNumber} karena data email user tidak ditemukan.");
                    }
                }

            } elseif ($transactionStatus == 'pending') {
                $order->update(['status' => 'Pending']);
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $order->update(['status' => 'Cancelled']);
                \Log::info("Order {$orderNumber} gagal atau kedaluwarsa.");
            }

            return response()->json(['message' => 'Notification handled successfully']);

        } catch (\Exception $e) {
            \Log::error('MIDTRANS NOTIFICATION ERROR: ' . $e->getMessage());
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}