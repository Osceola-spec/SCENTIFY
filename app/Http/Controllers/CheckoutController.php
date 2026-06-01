<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\Address;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Province;
use App\Models\City;

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

        if (empty($selectedCart)) {
            return redirect()->route('cart.index')->with('error', 'Item yang dipilih tidak ditemukan di keranjang.');
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

        $provinces = Province::orderBy('name', 'asc')->get();

        // Kirim $selectedCart sebagai variabel 'cart' ke Blade agar foreach ($cart as $item) tidak error
        return view('checkout', [
            'cart' => $selectedCart,
            'subtotal' => $subtotal,
            'shippingCost' => $shippingCost,
            'taxAmount' => $taxAmount,
            'totalAmount' => $totalAmount,      // <-- ERROR DI SINI
            'provinces' => $provinces
        ]);
    }


    public function getCities($province_id)
    {
        // Mengambil data kota yang memiliki province_id sesuai dengan yang dipilih
        $cities = City::where('province_id', $province_id)->orderBy('name', 'asc')->get();
        
        // Kembalikan dalam format JSON
        return response()->json($cities);
    }

    // 2. Memproses Simpan Pesanan (Place Order) & Auto-Save Alamat
    public function process(Request $request)
    {
        \Log::info('=== CHECKOUT PROCESS START ===');
        \Log::info('Request data awal', $request->all());

        // Tarik data item terpilih dan kalkulasi harga dari session aman kita
        $checkoutData = session()->get('checkout_data');

        // SISTEM BACKUP AUTOMATIS: Jika session checkout kosong (efek clear/pull), rakit kembali secara real-time
        if (!$checkoutData || empty($checkoutData['cart'])) {
            \Log::info('Session checkout_data kosong, mengaktifkan sistem backup otomatis...');
            
            $globalCart = session()->get('cart', []);
            if (empty($globalCart)) {
                \Log::info('REDIRECT: Keranjang belanja benar-benar kosong');
                return redirect()->route('cart.index')->with('error', 'Sesi checkout kosong atau kedaluwarsa. Silakan pilih ulang item dari keranjang.');
            }

            $subtotal = 0;
            foreach ($globalCart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            $shippingCost = 50000;
            $taxAmount = $subtotal * 0.11;
            $totalAmount = $subtotal + $shippingCost + $taxAmount;

            $checkoutData = [
                'cart' => $globalCart,
                'subtotal' => $subtotal,
                'shippingCost' => $shippingCost,
                'taxAmount' => $taxAmount,
                'totalAmount' => $totalAmount
            ];
        }

        // Sinkronisasi paksa input email jika form me-read-only dan tidak terkirim di request
        if (!$request->has('email') || empty($request->email)) {
            $request->merge(['email' => auth()->user()->email]);
        }

        // Logika Alamat Lama: Tarik data dari DB sebelum validasi agar request terisi data lengkap
        if ($request->filled('address_id') && $request->address_id !== 'new') {
            \Log::info('Pakai alamat lama: ' . $request->address_id);
            $addr = Address::where('id', $request->address_id)
                ->where('user_id', auth()->id())
                ->first();

            if ($addr) {
                \Log::info('Alamat ditemukan di DB, melakukan merge data ke request');
                $request->merge([
                    'first_name'  => $addr->first_name,
                    'last_name'   => $addr->last_name,
                    'phone'       => $addr->phone,
                    'address'     => $addr->address,
                    'city'        => $addr->city,
                    'postal_code' => $addr->postal_code,
                ]);
            } else {
                return redirect()->back()->withErrors(['address_id' => 'Alamat pilihan tidak valid atau bukan milik Anda.']);
            }
        }

        // Aturan Validasi Pengiriman Data
        $request->validate([
            'address_id'  => 'required',
            'first_name'  => 'required|string|max:255',
            'last_name'   => 'nullable|string|max:255',
            'email'       => 'required|email',
            'phone'       => 'required|string|min:8|max:20',
            'address'     => 'required|string',
            'city'        => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
        ]);

        $cart         = $checkoutData['cart'];
        $subtotal     = $checkoutData['subtotal'];
        $shippingCost = (int) $request->input('shipping_cost', $checkoutData['shippingCost']);
        $taxAmount    = round($subtotal * 0.11);
        $totalAmount  = $subtotal + $shippingCost + $taxAmount;

        DB::beginTransaction();
        \Log::info('DB transaction started');

        try {
            // Jika memilih alamat baru, buat record baru di tabel addresses
            if ($request->address_id === 'new') {
                \Log::info('Menyimpan alamat baru ke database');
                Address::create([
                    'user_id'     => auth()->id(),
                    'label'       => 'Alamat ' . now()->format('d M Y'),
                    'first_name'  => $request->first_name,
                    'last_name'   => $request->last_name ?? "", 
                    'phone'       => $request->phone,
                    'address'     => $request->address,
                    'city'        => $request->city,
                    'postal_code' => $request->postal_code,
                    'is_default'  => auth()->user()->addresses()->count() === 0,
                ]);
            }

            // Gabungkan teks nama belakang dengan aman jika bernilai null
            $lastNameText = $request->last_name ? ' ' . $request->last_name : '';
            $fullAddress  = $request->first_name . $lastNameText . " | " .
                $request->phone . " | " .
                $request->address . ", " . $request->city . " " . $request->postal_code;

            \Log::info('Full address text compiled: ' . $fullAddress);

            // Simpan data Order ke Database
            $order = Order::create([
                'user_id'          => auth()->id(),
                'order_number'     => 'ORD-' . strtoupper(Str::random(8)),
                'subtotal'         => $subtotal,
                'tax_amount'       => $taxAmount,
                'total_amount'     => $totalAmount,
                'status'           => 'Pending',
                'shipping_address' => $fullAddress,
            ]);

            \Log::info('Order successfully created. ID: ' . $order->id);

            // Simpan setiap item ke Order Items dan kurangi stok produk
            foreach ($cart as $variantId => $item) {
                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_variant_id' => $item['variant_id'],
                    'quantity'           => $item['quantity'],
                    'price_at_purchase'  => $item['price'],
                ]);

                $variant = ProductVariant::find($item['variant_id']);
                if ($variant) {
                    if ($variant->stock < $item['quantity']) {
                        throw new \Exception("Stok untuk produk " . $item['product_name'] . " tidak mencukupi.");
                    }
                    $variant->decrement('stock', $item['quantity']);
                    \Log::info('Stock decremented for variant ID: ' . $variant->id);
                }
            }

            DB::commit();
            \Log::info('DB Transaction Committed successfully');

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
                    'last_name'  => $request->last_name ?? '',
                    'email'      => $request->email,
                    'phone'      => $request->phone,
                ],
            ];

            \Log::info('Submitting parameters to Midtrans', $params);

            // Dapatkan token Midtrans
            $snapToken = Snap::getSnapToken($params);
            \Log::info('Snap token received: ' . $snapToken);

            // Bersihkan produk yang berhasil dibeli dari session cart utama
            $globalCart = session()->get('cart', []);
            foreach ($cart as $id => $item) {
                unset($globalCart[$id]);
            }
            session()->put('cart', $globalCart);
            session()->forget('checkout_data');

            // Buka halaman pembayaran midtrans
            return view('payment', compact('snapToken', 'order'));

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('CHECKOUT TRANSACTION FAILED: ' . $e->getMessage());
            
            return redirect()->back()->withInput()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }

    private function rajaOngkirHeaders(): array
    {
        return ['key' => env('RAJAONGKIR_API_KEY')];
    }

    public function searchCity(Request $request)
    {
        $response = Http::withHeaders($this->rajaOngkirHeaders())
            ->get(env('RAJAONGKIR_BASE_URL') . 'city');

        $cities = collect($response->json('rajaongkir.results', []))
            ->filter(fn($c) => str_contains(strtolower($c['city_name']), strtolower($request->q ?? '')))
            ->values()
            ->map(fn($c) => ['id' => $c['city_id'], 'text' => $c['type'] . ' ' . $c['city_name']]);

        return response()->json($cities);
    }

    public function getOngkir(Request $request)
    {
        $request->validate(['destination' => 'required', 'courier' => 'required', 'weight' => 'integer|min:1']);

        $response = Http::withHeaders($this->rajaOngkirHeaders())
            ->post(env('RAJAONGKIR_BASE_URL') . 'cost', [
                'origin'      => env('RAJAONGKIR_ORIGIN_CITY', 151),
                'destination' => $request->destination,
                'weight'      => $request->input('weight', 1000),
                'courier'     => $request->courier,
            ]);

        $results = $response->json('rajaongkir.results.0.costs', []);
        return response()->json($results);
    }

    /**
     * Menangani opsi bayar nanti dari halaman payment.
     */
    public function payLater(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Akses tidak sah.');
        }

        if ($order->status !== 'Pending') {
            return redirect()->route('orders.index')->with('info', 'Pesanan ini sudah diproses.');
        }

        \Log::info("User memilih opsi Bayar Nanti untuk Order ID: {$order->order_number}");

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil disimpan. Silakan lakukan pembayaran sebelum kedaluwarsa di halaman akun Anda.');
    }
}