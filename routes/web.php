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

// ==========================================
// RUTE PUBLIK
// ==========================================
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/shop', [ShopController::class, 'show'])->name('shop');

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
});
