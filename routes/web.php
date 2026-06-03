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
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\AdminBranchController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\MidtransNotificationController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\AdminPromotionController;
use App\Http\Controllers\AdminCustomerController;

// ==========================================
// RUTE PUBLIK (BISA DIAKSES SIAPA SAJA)
// ==========================================
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/shop', [ShopController::class, 'show'])->name('shop');
Route::get('/brands', [BrandController::class, 'publicIndex'])->name('brands.index');
Route::get('/stores', [BranchController::class, 'index'])->name('stores.index');

// API Chatbot & Webhook Midtrans (Jangan diberi middleware auth)
Route::post('/api/chatbot', [ChatbotController::class, 'chat']);
Route::post('/midtrans/notification', [MidtransNotificationController::class, 'handle']);

// Rute API Lokasi Wilayah (RajaOngkir) untuk Fetch AJAX Dropdown Halaman Checkout
Route::get('/api/cities/{province_id}', [CheckoutController::class, 'getCities'])->name('checkout.getCities');
Route::post('/api/ongkir', [CheckoutController::class, 'getOngkir'])->name('api.ongkir');

// Rute Login Google
Route::get('auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('auth/google/callback', [GoogleAuthController::class, 'callback']);


// ==========================================
// RUTE AUTENTIKASI (KHUSUS TAMU / GUEST)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'show_login'])->name('login');
    Route::post('/login_auth', [AuthController::class, 'login_auth'])->name('login.auth');
    Route::get('/register', [AuthController::class, 'show_register'])->name('register');
    Route::post('/register_auth', [AuthController::class, 'register_auth'])->name('register.auth');
    Route::get('/verify-email', [AuthController::class, 'show_verify'])->name('verify.email');
    Route::post('/verify-email', [AuthController::class, 'verify_email_post'])->name('verify.email.post');
    Route::post('/verify-email/resend', [AuthController::class, 'resend_otp'])->name('resend.otp');
});


// ==========================================
// RUTE PENGGUNA LOGIN (WAJIB AUTH)
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    // Profil & Pengaturan Akun
    Route::get('/profile', [ProfileController::class, 'show_profile'])->name('profile');
    Route::put('/profile/update', [ProfileController::class, 'update_profile'])->name('profile.update');
    Route::post('/logout', [ProfileController::class, 'logout'])->name('logout');
    
    // Manajemen Alamat Pengguna
    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::post('/profile/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::put('/profile/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/profile/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::put('/profile/addresses/{address}/default', [AddressController::class, 'setDefault'])->name('addresses.setDefault');
    
    // Keranjang Belanja (Cart)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{variantId}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{variantId}', [CartController::class, 'remove'])->name('cart.remove');

    // Proses Checkout & Pembayaran
    Route::any('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/pay-later/{order}', [CheckoutController::class, 'payLater'])->name('checkout.pay-later');
    Route::get('/api/cities-search', [CheckoutController::class, 'searchCity'])->name('api.cities.search');
    
    // Riwayat Pesanan Kustomer
    Route::get('/my-orders', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('/my-orders/{id}', [CustomerOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{id}/cancel', [CustomerOrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders/{order_number}/pay', [CustomerOrderController::class, 'payNow'])->name('orders.pay');
    Route::get('/orders/payment/finished', [CustomerOrderController::class, 'paymentFinished'])->name('orders.payment_finished');
    
    // RUTE PENYELESAIAN MIDTRANS KHUSUS DEMO LOCALHOST
    Route::get('/payment/finished', [CustomerOrderController::class, 'paymentFinished'])->name('payment.finished');
        
    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle/{productId}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Ulasan / Review Produk
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});


// ==========================================
// RUTE KHUSUS ADMIN PANEL
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {

    // Dashboard & Inventory
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

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

    // Manajemen Cabang (Branches)
    Route::get('/branches', [AdminBranchController::class, 'index'])->name('admin.branches.index');
    Route::get('/branches/create', [AdminBranchController::class, 'create'])->name('admin.branches.create');
    Route::post('/branches', [AdminBranchController::class, 'store'])->name('admin.branches.store');
    Route::get('/branches/{branch}', [AdminBranchController::class, 'show'])->name('admin.branches.show');
    Route::get('/branches/{branch}/edit', [AdminBranchController::class, 'edit'])->name('admin.branches.edit');
    Route::put('/branches/{branch}', [AdminBranchController::class, 'update'])->name('admin.branches.update');
    Route::delete('/branches/{branch}', [AdminBranchController::class, 'destroy'])->name('admin.branches.destroy');

    // Manajemen Promo / Flash Sale
    Route::get('/promotions', [AdminPromotionController::class, 'index'])->name('admin.promotions.index');
    Route::get('/promotions/create', [AdminPromotionController::class, 'create'])->name('admin.promotions.create');
    Route::post('/promotions', [AdminPromotionController::class, 'store'])->name('admin.promotions.store');
    Route::get('/promotions/{promotion}/edit', [AdminPromotionController::class, 'edit'])->name('admin.promotions.edit');
    Route::put('/promotions/{promotion}', [AdminPromotionController::class, 'update'])->name('admin.promotions.update');
    Route::delete('/promotions/{promotion}', [AdminPromotionController::class, 'destroy'])->name('admin.promotions.destroy');

    // Manajemen Pesanan oleh Admin
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');

    // Manajemen Pelanggan (Customers)
    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('admin.customers.index');
    Route::get('/customers/{user}', [AdminCustomerController::class, 'show'])->name('admin.customers.show');
});