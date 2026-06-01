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

// ✨ PINDAH KE SINI: Rute API RajaOngkir dijadikan publik agar Fetch AJAX lancar jaya
Route::get('/api/cities/{province_id}', [CheckoutController::class, 'getCities']);
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
    Route::post('/profile/addresses', [\App\Http\Controllers\AddressController::class, 'store'])->name('addresses.store');
    Route::put('/profile/addresses/{address}', [\App\Http\Controllers\AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/profile/addresses/{address}', [\App\Http\Controllers\AddressController::class, 'destroy'])->name('addresses.destroy');
    
    // Keranjang Belanja (Cart)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{variantId}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{variantId}', [CartController::class, 'remove'])->name('cart.remove');

    // Proses Checkout & Pembayaran
    Route::any('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/pay-later/{order}', [CheckoutController::class, 'payLater'])->name('checkout.pay-later');
    Route::get('/api/cities', [CheckoutController::class, 'searchCity'])->name('api.cities');
    
    // 💡 Catatan: Route::post('/api/ongkir') yang duplikat di sini sudah dihapus & dipindah ke atas
    
    // Riwayat Pesanan Kustomer
    Route::get('/my-orders', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('/my-orders/{id}', [CustomerOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{id}/cancel', [CustomerOrderController::class, 'cancel'])->name('orders.cancel');
    
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
    Route::get('/promotions', [App\Http\Controllers\AdminPromotionController::class, 'index'])->name('admin.promotions.index');
    Route::get('/promotions/create', [App\Http\Controllers\AdminPromotionController::class, 'create'])->name('admin.promotions.create');
    Route::post('/promotions', [App\Http\Controllers\AdminPromotionController::class, 'store'])->name('admin.promotions.store');
    Route::get('/promotions/{promotion}/edit', [App\Http\Controllers\AdminPromotionController::class, 'edit'])->name('admin.promotions.edit');
    Route::put('/promotions/{promotion}', [App\Http\Controllers\AdminPromotionController::class, 'update'])->name('admin.promotions.update');
    Route::delete('/promotions/{promotion}', [App\Http\Controllers\AdminPromotionController::class, 'destroy'])->name('admin.promotions.destroy');

    // Manajemen Pesanan oleh Admin
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');

    // Manajemen Pelanggan (Customers)
    Route::get('/customers', [App\Http\Controllers\AdminCustomerController::class, 'index'])->name('admin.customers.index');
    Route::get('/customers/{user}', [App\Http\Controllers\AdminCustomerController::class, 'show'])->name('admin.customers.show');
});

// Pastikan strukturnya seperti ini dan tidak terhalang middleware auth jika diakses via ajax diluar login (atau masukkan dalam group auth)
Route::get('/get-cities/{province_id}', [App\Http\Controllers\CheckoutController::class, 'getCities'])->name('checkout.getCities');