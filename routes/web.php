<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;
use App\Models\Brand;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\WishlistController;

// ==========================================
// RUTE PUBLIK
// ==========================================
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/shop', [ShopController::class, 'show'])->name('shop');

Route::middleware(['auth'])->group(function () {
    
    // Tampilan halaman utama riwayat pesanan saya
    Route::get('/my-orders', [CustomerOrderController::class, 'index'])
        ->name('orders.index');
        
    // Tampilan halaman detail pesanan kustomer
    Route::get('/my-orders/{id}', [CustomerOrderController::class, 'show'])
        ->name('orders.show');
        
});

// ==========================================
// RUTE KERANJANG & CHECKOUT
// ==========================================
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{variantId}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{variantId}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

// ==========================================
// RUTE AUTENTIKASI (GUEST)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'show_login'])->name('login');
    Route::post('/login_auth', [AuthController::class, 'login_auth'])->name('login.auth');
    Route::get('/register', [AuthController::class, 'show_register'])->name('register');
    Route::post('/register_auth', [AuthController::class, 'register_auth'])->name('register.auth');
});

// ==========================================
// RUTE PENGGUNA LOGIN (USER BIASA)
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show_profile'])->name('profile');
    // Addresses
    Route::post('/profile/addresses', [\App\Http\Controllers\AddressController::class, 'store'])->name('addresses.store');
    Route::put('/profile/addresses/{address}', [\App\Http\Controllers\AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/profile/addresses/{address}', [\App\Http\Controllers\AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::post('/profile/update', [ProfileController::class, 'update_profile'])->name('profile.update');
    Route::post('/logout', [ProfileController::class, 'logout'])->name('logout');
});

Route::get('/brands', [BrandController::class, 'publicIndex'])->name('brands.index');

// ==========================================
// RUTE KHUSUS ADMIN PANEL
// ==========================================
// Menggunakan prefix '/admin' agar URL rapi (contoh: scentify.com/admin/dashboard)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {

    // Dashboard & Inventory
    Route::get('/dashboard', function () {
        $totalProducts = Product::count();
        $totalBrands = Brand::count();
        $totalVariants = ProductVariant::count();
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'Pending')->count();
        $completedOrders = Order::where('status', 'Completed')->count();
        $totalRevenue = Order::whereIn('status', ['Paid', 'Shipped', 'Completed'])->sum('total_amount');
        $monthlyRevenue = Order::whereIn('status', ['Paid', 'Shipped', 'Completed'])
            ->whereMonth('created_at', now()->month)
            ->sum('total_amount');
        $todayRevenue = Order::whereIn('status', ['Paid', 'Shipped', 'Completed'])
            ->whereDate('created_at', now())
            ->sum('total_amount');

        $recentProducts = Product::with('brand')
            ->latest()
            ->take(5)
            ->get();

        $upcomingOrders = Order::with('user')
            ->whereIn('status', ['Pending', 'Paid', 'Shipped'])
            ->latest()
            ->take(5)
            ->get();

        $lowStockVariants = ProductVariant::with('product')
            ->where('stock', '<=', 10)
            ->orderBy('stock')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalBrands',
            'totalVariants',
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'totalRevenue',
            'monthlyRevenue',
            'todayRevenue',
            'recentProducts',
            'upcomingOrders',
            'lowStockVariants'
        ));
    })->name('admin.dashboard');

    Route::get('/inventory', function () {
        $search = request('search');
        $filter = request('filter', 'name');

        $products = Product::with(['brand', 'variants'])
            ->when($search, function ($query) use ($search, $filter) {
                if ($filter === 'brand') {
                    $query->whereHas('brand', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
                } elseif ($filter === 'category') {
                    $query->where('category', 'like', "%{$search}%");
                } elseif ($filter === 'gender_type') {
                    $query->where('gender_type', 'like', "%{$search}%");
                } else {
                    $query->where('name', 'like', "%{$search}%");
                }
            })
            ->orderBy('name')
            ->get();

        return view('admin.inventory', compact('products'));
    })->name('admin.inventory');

    // Manajemen Brand
    Route::get('/brands', [BrandController::class, 'index'])->name('admin.brands.index');
    Route::post('/brands', [BrandController::class, 'store'])->name('admin.brands.store');
    Route::put('/brands/{brand}', [BrandController::class, 'update'])->name('admin.brands.update');
    Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])->name('admin.brands.destroy');

    // Manajemen Produk
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
});

// Rute untuk mengklik tombol Google
Route::get('auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');

// Rute tempat Google mengembalikan data (callback)
Route::get('auth/google/callback', [GoogleAuthController::class, 'callback']);

Route::middleware('auth')->group(function () {
    // ... rute profile & addresses yang sudah ada ...
    
    // Rute Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle/{productId}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
});