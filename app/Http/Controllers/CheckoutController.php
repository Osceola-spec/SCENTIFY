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
    public function index(Request $request)
    {
        $cart = session()->get('cart', []);

        // Sync prices with latest database values
        if (!empty($cart)) {
            $variantIds = array_keys($cart);
            $variants = ProductVariant::with('product')->whereIn('id', $variantIds)->get()->keyBy('id');
            
            $now = now();
            $promo = \App\Models\Promotion::where('is_active', true)
                ->where(function($q) use ($now) { $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now); })
                ->first();

            $changed = false;
            foreach ($cart as $variantId => &$item) {
                if (isset($variants[$variantId])) {
                    $variant = $variants[$variantId];
                    $originalPrice = $variant->price;
                    $finalPrice = $originalPrice;
                    
                    if ($promo && ($promo->applies_to_all || $promo->product_id == $variant->product_id)) {
                        $dv = (float) $promo->discount_value;
                        $finalPrice = $promo->discount_type === 'percent'
                            ? max(0, round($originalPrice * (1 - $dv / 100)))
                            : max(0, round($originalPrice - $dv));
                    }

                    if ($item['price'] != $finalPrice || $item['original_price'] != $originalPrice) {
                        $item['price'] = $finalPrice;
                        $item['original_price'] = $originalPrice;
                        $changed = true;
                    }
                }
            }
            unset($item); // Fix PHP reference bug
            if ($changed) {
                session()->put('cart', $cart);
            }
        }

        // Tangkap array ID produk yang dicentang oleh user dari halaman keranjang
        $selectedIds = $request->input('checkout_items');
        $quantities = $request->input('quantities');

        if ($selectedIds === null) {
            $checkoutData = session('checkout_data');
            if (is_array($checkoutData)) {
                $selectedIds = $checkoutData['checkout_items'] ?? [];
                $quantities = $checkoutData['quantities'] ?? [];
            }
        }

        if (empty($selectedIds)) {
            if (empty(session('cart'))) {
                return redirect()->route('orders.index');
            }
            return redirect()->route('cart.index')->with('error', 'Select at least one product to checkout.');
        }

        // Filter session cart HANYA untuk item yang dipilih
        $selectedCart = [];
        $subtotal = 0;
        $totalWeight = 0; // Tambahan hitung berat total paket

        foreach ($selectedIds as $id) {
            if (isset($cart[$id])) {
                $item = $cart[$id];
                
                // Sinkronisasi kuantitas terbaru jika ada perubahan di form keranjang
                if (isset($quantities[$id])) {
                    $item['quantity'] = (int) $quantities[$id];
                }
                
                $selectedCart[$id] = $item;
                $subtotal += $item['price'] * $item['quantity'];
                
                // Asumsi per produk berbobot 500 gram (opsional, sesuaikan dengan DB jika ada)
                $totalWeight += ($item['weight'] ?? 500) * $item['quantity'];
            }
        }

        if (empty($selectedCart)) {
            return redirect()->route('orders.index');
        }

        // Default awal ongkir diatur 0 sebelum kurir dipilih di Ajax
        $shippingCost = 0;
        $taxAmount = round($subtotal * 0.11);
        $totalAmount = $subtotal + $shippingCost + $taxAmount;

        // Amankan data kalkulasi & item terpilih ke dalam Session agar bisa dibaca aman di fungsi process()
        session()->put('checkout_data', [
            'cart' => $selectedCart,
            'subtotal' => $subtotal,
            'shippingCost' => $shippingCost,
            'taxAmount' => $taxAmount,
            'totalAmount' => $totalAmount,
            'totalWeight' => $totalWeight, // Simpan info berat
            'checkout_items' => $selectedIds,
            'quantities' => $quantities,
        ]);

        $provinces = Province::orderBy('name', 'asc')->get();

        // Cari tahu apakah user punya alamat default untuk parsing awal kota tujuan di View
        $defaultAddress = Address::where('user_id', auth()->id())->where('is_default', true)->first();

        return view('checkout', [
            'cart' => $selectedCart,
            'subtotal' => $subtotal,
            'shippingCost' => $shippingCost,
            'taxAmount' => $taxAmount,
            'totalAmount' => $totalAmount,
            'provinces' => $provinces,
            'totalWeight' => $totalWeight,
            'defaultAddress' => $defaultAddress
        ]);
    }

    public function getCities($province_id)
    {
        // Mengambil data kota berdasarkan province_id
        $cities = City::where('province_id', $province_id)
                    ->orderBy('name', 'asc')
                    ->get();
        
        // BACKUP: Jika tabel di DB kamu ternyata menggunakan kolom 'city_name' bukan 'name'
        if ($cities->isEmpty()) {
            $cities = City::where('province_id', $province_id)->get();
        }

        // Transformasi data agar JavaScript (seperti Select2 atau dropdown biasa) pasti menerima ID dan Nama
        $results = $cities->map(fn($city) => [
            'id'   => $city->id ?? $city->city_id ?? $city->id,
            'name' => $city->name ?? $city->city_name ?? $city->title
        ]);
        
        return response()->json($results);
    }

    // 2. Memproses Simpan Pesanan (Place Order) & Auto-Save Alamat
    public function process(Request $request)
    {
        \Log::info('=== CHECKOUT PROCESS START ===');
        \Log::info('Request data awal', $request->all());

        // Tarik data item terpilih dan kalkulasi harga dari session aman kita
        $checkoutData = session()->get('checkout_data');

        // SISTEM BACKUP OTOMATIS: Jika session checkout kosong, rakit kembali secara real-time
        if (!$checkoutData || empty($checkoutData['cart'])) {
            \Log::info('Session checkout_data kosong, mengaktifkan sistem backup otomatis...');
            
            $globalCart = session()->get('cart', []);
            if (empty($globalCart)) {
                \Log::info('REDIRECT: Keranjang belanja benar-benar kosong, redirect ke orders.index');
                return redirect()->route('orders.index');
            }

            $subtotal = 0;
            foreach ($globalCart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            $shippingCost = (int) $request->input('shipping_cost', 0);
            $taxAmount = round($subtotal * 0.11);
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
                    'province_id' => $addr->province_id,
                    'city_id'     => $addr->city_id,
                    'postal_code' => $addr->postal_code,
                ]);
            } else {
                return redirect()->back()->withErrors(['address_id' => 'Alamat pilihan tidak valid atau bukan milik Anda.']);
            }
        }

        // Aturan Validasi Pengiriman Data
        $request->validate([
            'address_id'    => 'required',
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'nullable|string|max:255',
            'email'         => 'required|email',
            'phone'         => 'required|string|min:8|max:20',
            'address'       => 'required|string',
            'city'          => 'required|string|max:255',
            'postal_code'   => 'required|string|max:10',
            'shipping_cost' => 'required|numeric|min:0', 
        ]);

        // RE-CALCULATE TOTAL AMOUNT AGAR SELALU SINKRON DENGAN REQ ONGKIR TERBARU
        $cart         = $checkoutData['cart'];
        $subtotal     = $checkoutData['subtotal'];
        $shippingCost = (int) $request->input('shipping_cost');
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
                    'province_id' => $request->province_id,
                    'city_id'     => $request->city_id,
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
                'shipping_amount'  => $shippingCost, // PASTIKAN KOLOM INI ADA DI TABEL ORDERS KAMU
                'total_amount'     => $totalAmount,
                'status'           => 'Pending',
                'shipping_address' => $fullAddress,
            ]);

            \Log::info('Order successfully created. ID: ' . $order->id);

            // Simpan setiap item ke Order Items dan kurangi stok produk
            foreach ($cart as $variantId => $item) {
                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_variant_id' => $item['variant_id'] ?? $variantId,
                    'quantity'           => $item['quantity'],
                    'price_at_purchase'  => $item['price'],
                ]);

                $variant = ProductVariant::find($item['variant_id'] ?? $variantId);
                if ($variant) {
                    if ($variant->stock < $item['quantity']) {
                        throw new \Exception("Stok untuk produk " . ($item['product_name'] ?? 'Pilihan') . " tidak mencukupi.");
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
                    'order_id'     => $order->order_number . '-' . time(),
                    'gross_amount' => (int) $order->total_amount,
                ],
                'customer_details' => [
                    'first_name' => $request->first_name,
                    'last_name'  => $request->last_name ?? '',
                    'email'      => $request->email,
                    'phone'      => $request->phone,
                    'billing_address' => [
                        'first_name'   => $request->first_name,
                        'last_name'    => $request->last_name ?? '',
                        'phone'        => $request->phone,
                        'address'      => $request->address,
                        'city'         => $request->city,
                        'postal_code'  => $request->postal_code,
                    ],
                    'shipping_address' => [
                        'first_name'   => $request->first_name,
                        'last_name'    => $request->last_name ?? '',
                        'phone'        => $request->phone,
                        'address'      => $request->address,
                        'city'         => $request->city,
                        'postal_code'  => $request->postal_code,
                    ]
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
            
            return redirect()->back()->withInput()->with('error', 'Failed to process transaction: ' . $e->getMessage());
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
        $request->validate([
            'destination' => 'required', 
            'courier'     => 'required', 
            'weight'      => 'required|integer|min:1'
        ]);

        try {
            $destination = $request->destination;
            if (!is_numeric($destination)) {
                $cityRecord = City::where('name', 'LIKE', '%' . $destination . '%')->first();
                if ($cityRecord) {
                    $destination = $cityRecord->id ?? $cityRecord->city_id;
                }
            }

            // Tambahkan timeout, withoutVerifying (untuk mencegah cURL error 35 di lokal Windows), dan logging
            $response = Http::asForm()->timeout(10)->withoutVerifying()
                ->withHeaders($this->rajaOngkirHeaders())
                ->post(env('RAJAONGKIR_BASE_URL') . 'cost', [
                    'origin'      => env('RAJAONGKIR_ORIGIN_CITY', 151), 
                    'destination' => $destination,
                    'weight'      => $request->weight,
                    'courier'     => $request->courier,
                ]);

            if ($response->failed()) {
                \Log::error('RajaOngkir cost failed', [
                    'url' => env('RAJAONGKIR_BASE_URL') . 'cost',
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                throw new \Exception("Gagal mengambil data dari RajaOngkir: " . $response->body());
            }

            $results = $response->json('rajaongkir.results.0.costs', []);

            return response()->json([
                'success' => true,
                'costs'   => $results
            ]);

        } catch (\Exception $e) {
            \Log::error('RajaOngkir exception', ['message' => $e->getMessage()]);
            
            // SYSTEM BACKUP: Mengembalikan ongkir dummy jika API RajaOngkir error atau Timeout (cURL 28)
            return response()->json([
                'success' => true,
                'costs'   => [
                    [
                        'service' => 'REG (Fallback)',
                        'description' => 'Layanan Reguler',
                        'cost' => [
                            [
                                'value' => 15000,
                                'etd' => '2-3',
                                'note' => ''
                            ]
                        ]
                    ],
                    [
                        'service' => 'YES (Fallback)',
                        'description' => 'Layanan Kilat',
                        'cost' => [
                            [
                                'value' => 25000,
                                'etd' => '1-1',
                                'note' => ''
                            ]
                        ]
                    ]
                ]
            ]);
        }
    }

    public function payLater(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Akses tidak sah.');
        }

        if ($order->status !== 'Pending') {
            return redirect()->route('orders.index')->with('info', 'Pesanan ini sudah diproses.');
        }

        \Log::info("User memilih opsi Bayar Nanti untuk Order ID: {$order->order_number}");

        return redirect()->route('orders.index')->with('success', 'Order saved successfully. Please make payment before it expires in your account page.');
    }
}