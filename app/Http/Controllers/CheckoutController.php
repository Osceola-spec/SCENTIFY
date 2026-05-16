<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    // 1. Menampilkan Halaman Checkout
    public function index()
    {
        $cart = session()->get('cart', []);

        // Jika keranjang kosong, kembalikan ke halaman cart
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        // Kalkulasi ulang subtotal
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Contoh perhitungan sederhana
        $shippingCost = 50000;
        $taxAmount = $subtotal * 0.11; // Pajak 11%
        $totalAmount = $subtotal + $shippingCost + $taxAmount;

        return view('checkout', compact('cart', 'subtotal', 'shippingCost', 'taxAmount', 'totalAmount'));
    }

    // 2. Memproses Simpan Pesanan (Place Order)
    public function process(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('shop')->with('error', 'Keranjang kosong.');
        }

        // 1. Kalkulasi Total
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $taxAmount = $subtotal * 0.11;
        $totalAmount = $subtotal + 50000 + $taxAmount; // + Ongkir 50rb

        $fullAddress = $request->first_name . ' ' . $request->last_name . " | " .
            $request->phone . " | " .
            $request->address . ", " . $request->city . " " . $request->postal_code;

        // 2. Simpan ke Database
        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => 1, // SEMENTARA hardcode
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'status' => 'Pending', // Status masih pending karena belum dibayar
                'shipping_address' => $fullAddress,
            ]);

            foreach ($cart as $variantId => $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $item['variant_id'],
                    'quantity' => $item['quantity'],
                    'price_at_purchase' => $item['price'],
                ]);

                // Kurangi stok
                $variant = ProductVariant::find($item['variant_id']);
                if ($variant) {
                    $variant->decrement('stock', $item['quantity']);
                }
            }

            session()->forget('cart');
            DB::commit();

            // ==========================================
            // 3. KONFIGURASI MIDTRANS SNAP
            // ==========================================
            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_number,
                    'gross_amount' => (int) $order->total_amount, // Midtrans butuh tipe integer
                ],
                'customer_details' => [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                ],
            ];

            // Minta Snap Token dari Midtrans
            $snapToken = Snap::getSnapToken($params);

            // Arahkan ke halaman khusus pembayaran dengan membawa token
            return view('payment', compact('snapToken', 'order'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
