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
    public function index(Request $request)
    {
        $cart = session()->get('cart', []);

        // Tangkap array ID produk yang dicentang oleh user dari halaman keranjang
        $selectedIds = $request->input('checkout_items', []);
        $quantities = $request->input('quantities', []);

        if (empty($selectedIds)) {
            return redirect()->route('cart.index')->with('error', 'Pilih minimal satu produk untuk di-checkout.');
        }

        // Filter session cart HANYA untuk item yang dipilih
        $selectedCart = [];
        $subtotal = 0;

        foreach ($selectedIds as $id) {
            if (isset($cart[$id])) {
                $item = $cart[$id];
                
                // Sinkronisasi kuantitas terbaru jika ada perubahan di form keranjang
                if (isset($quantities[$id])) {
                    $item['quantity'] = (int) $quantities[$id];
                }
                
                $selectedCart[$id] = $item;
                $subtotal += $item['price'] * $item['quantity'];
            }
        }

        $shippingCost = 50000;
        $taxAmount = $subtotal * 0.11;
        $totalAmount = $subtotal + $shippingCost + $taxAmount;

        // Amankan data kalkulasi & item terpilih ke dalam Session agar bisa dibaca aman di fungsi process()
        session()->put('checkout_data', [
            'cart' => $selectedCart,
            'subtotal' => $subtotal,
            'shippingCost' => $shippingCost,
            'taxAmount' => $taxAmount,
            'totalAmount' => $totalAmount,
        ]);

        // Kirim $selectedCart sebagai variabel 'cart' ke Blade agar foreach ($cart as $item) tidak error
        return view('checkout', [
            'cart' => $selectedCart,
            'subtotal' => $subtotal,
            'shippingCost' => $shippingCost,
            'taxAmount' => $taxAmount,
            'totalAmount' => $totalAmount
        ]);
    }

    // 2. Memproses Simpan Pesanan (Place Order) & Auto-Save Alamat
    public function process(Request $request)
    {
        \Log::info('=== CHECKOUT PROCESS START ===');
        \Log::info('Request data', $request->all());

        // Tarik data item terpilih dan kalkulasi harga dari session aman kita
        $checkoutData = session()->get('checkout_data');

        if (!$checkoutData || empty($checkoutData['cart'])) {
            \Log::info('REDIRECT: Data session checkout kosong');
            return redirect()->route('cart.index')->with('error', 'Sesi checkout kosong atau kedaluwarsa.');
        }

        $cart         = $checkoutData['cart'];
        $subtotal     = $checkoutData['subtotal'];
        $shippingCost = $checkoutData['shippingCost'];
        $taxAmount    = $checkoutData['taxAmount'];
        $totalAmount  = $checkoutData['totalAmount'];

        \Log::info('Kalkulasi terambil dari session', compact('subtotal', 'taxAmount', 'totalAmount'));

        DB::beginTransaction();
        \Log::info('DB transaction started');

        try {
            // Logika Alamat
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

            // Simpan data Order ke Database
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

            // Simpan setiap item ke Order Items dan kurangi stok produk
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

            // Konfigurasi Sistem Midtrans
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

            // Dapatkan token Midtrans
            $snapToken = Snap::getSnapToken($params);
            \Log::info('Snap token received: ' . $snapToken);

            // Bersihkan data cart global dan session checkout pembantu
            $globalCart = session()->get('cart', []);
            foreach ($cart as $id => $item) {
                unset($globalCart[$id]); // Hapus produk yang dibeli saja dari keranjang belanja global
            }
            session()->put('cart', $globalCart);
            session()->forget('checkout_data');

            // MENGHINDARI LAYAR PUTIH: Kembalikan view payment dengan data lengkap
            return view('payment', compact('snapToken', 'order'));

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('CHECKOUT ERROR: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            // Mengganti dd($e->getMessage()) agar user dikembalikan ke halaman sebelumnya dengan pesan error yang rapi
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
    /**
     * Menangani opsi bayar nanti dari halaman payment.
     */
    public function payLater(Order $order)
    {
        // Keamanan: Pastikan order ini memang milik user yang sedang login
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Akses tidak sah.');
        }

        // Pastikan status order masih Pending
        if ($order->status !== 'Pending') {
            return redirect()->route('orders.index')->with('info', 'Pesanan ini sudah diproses.');
        }

        \Log::info("User memilih opsi Bayar Nanti untuk Order ID: {$order->order_number}");

        // 1. Bersihkan sisa data checkout di session jika masih ada
        // session()->forget('checkout_data');
        // session()->forget('cart'); // Memastikan keranjang benar-benar kosong setelah pesanan disimpan

        // 2. Redirect ke halaman My Orders dengan pesan sukses
        // Catatan: Sesuaikan 'orders.index' dengan nama route halaman daftar pesanan Anda
        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil disimpan. Silakan lakukan pembayaran sebelum kedaluwarsa di halaman akun Anda.');
    }
}