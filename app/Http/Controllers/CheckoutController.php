<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\Address; // Pastikan Model Address dipanggil
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

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $shippingCost = 50000;
        $taxAmount = $subtotal * 0.11;
        $totalAmount = $subtotal + $shippingCost + $taxAmount;

        return view('checkout', compact('cart', 'subtotal', 'shippingCost', 'taxAmount', 'totalAmount'));
    }

    // 2. Memproses Simpan Pesanan (Place Order) & Auto-Save Alamat
    public function process(Request $request)
    {
        \Log::info('=== CHECKOUT PROCESS START ===');
        \Log::info('Request data', $request->all());
        \Log::info('Cart session', session()->get('cart', []));
        \Log::info('DB_PORT', [config('database.connections.mysql.port')]);
        \Log::info('DB_HOST', [config('database.connections.mysql.host')]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            \Log::info('REDIRECT: cart kosong');
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $shippingCost = 50000;
        $taxAmount    = $subtotal * 0.11;
        $totalAmount  = $subtotal + $shippingCost + $taxAmount;

        \Log::info('Kalkulasi', compact('subtotal', 'taxAmount', 'totalAmount'));

        DB::beginTransaction();
        \Log::info('DB transaction started');


        try {
            // Address logic
            if ($request->filled('address_id') && $request->address_id !== 'new') {
                \Log::info('Pakai alamat lama: ' . $request->address_id);
                $addr = Address::where('id', $request->address_id)
                    ->where('user_id', auth()->id())
                    ->first();

                \Log::info('Alamat ditemukan', [$addr]);
        

                if ($addr) {
                    $request->merge([
                        'first_name'  => $addr->first_name,
                        'last_name'   => $addr->last_name,
                        'phone'       => $addr->phone,
                        'address'     => $addr->address,
                        'city'        => $addr->city,
                        'postal_code' => $addr->postal_code,
                    ]);
                }
            } else {
                \Log::info('Simpan alamat baru');
                Address::create([
                    'user_id'     => auth()->id(),
                    'label'       => 'Alamat ' . now()->format('d M Y'),
                    'first_name'  => $request->first_name,
                    'last_name'   => $request->last_name,
                    'phone'       => $request->phone,
                    'address'     => $request->address,
                    'city'        => $request->city,
                    'postal_code' => $request->postal_code,
                    'is_default'  => auth()->user()->addresses()->count() === 0,
                ]);
            }

            $fullAddress = $request->first_name . ' ' . $request->last_name . " | " .
                $request->phone . " | " .
                $request->address . ", " . $request->city . " " . $request->postal_code;

            \Log::info('Full address: ' . $fullAddress);

            // dd($subtotal, $taxAmount, $totalAmount, $fullAddress);

            $order = Order::create([
                'user_id'          => auth()->id(),
                'order_number'     => 'ORD-' . strtoupper(\Str::random(8)),
                'subtotal'         => $subtotal,
                'tax_amount'       => $taxAmount,
                'total_amount'     => $totalAmount,
                'status'           => 'Pending',
                'shipping_address' => $fullAddress,
            ]);
            

            \Log::info('Order created', [$order->id]);

            
            foreach ($cart as $variantId => $item) {
                \Log::info('Creating order item', $item);
                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_variant_id' => $item['variant_id'],
                    'quantity'           => $item['quantity'],
                    'price_at_purchase'  => $item['price'],
                ]);


                $variant = ProductVariant::find($item['variant_id']);
                if ($variant) {
                    $variant->decrement('stock', $item['quantity']);
                    \Log::info('Stock decremented for variant ' . $variant->id);
                }
            }
            

            DB::commit();
            \Log::info('DB committed');

            // Midtrans
            Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            Config::$isSanitized  = true;
            Config::$is3ds        = true;

            $params = [
                'transaction_details' => [
                    'order_id'     => $order->order_number,
                    'gross_amount' => (int) $order->total_amount,
                ],
                'customer_details' => [
                    'first_name' => $request->first_name,
                    'last_name'  => $request->last_name,
                    'email'      => $request->email,
                    'phone'      => $request->phone,
                ],
            ];

            \Log::info('Midtrans params', $params);

            $snapToken = Snap::getSnapToken($params);
            \Log::info('Snap token received: ' . $snapToken);

            session()->forget('cart');

            return view('payment', compact('snapToken', 'order'));

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            \Log::error('CHECKOUT ERROR: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}