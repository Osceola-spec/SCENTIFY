<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransNotificationController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Konfigurasi Midtrans (Sama dengan di CheckoutController)
        Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        try {
            // 2. Tangkap data notifikasi resmi dari Midtrans
            $notif = new Notification();

            $transactionStatus = $notif->transaction_status;
            $orderNumber       = $notif->order_id; // Ini mengambil ORD-XXXXXX Anda
            $paymentType       = $notif->payment_type;

            // 3. Cari data Order di database Anda berdasarkan order_number
            $order = Order::where('order_number', $orderNumber)->first();

            if (!$order) {
                return response()->json(['message' => 'Order tidak ditemukan'], 404);
            }

            // 4. Logika Perubahan Status dari Pending ke Processing (Paid)
            if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
                
                // Ubah status menjadi Processing atau Success sesuai kebutuhan Anda
                $order->update([
                    'status' => 'Processing', 
                    'payment_method' => $paymentType
                ]);
                
                \Log::info("Order {$orderNumber} berhasil dibayar menggunakan {$paymentType}. Status: Processing.");

            } elseif ($transactionStatus == 'pending') {
                $order->update(['status' => 'Pending']);
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                
                // Opsional: Jika gagal/kedaluwarsa, kembalikan stok produk jika diperlukan
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