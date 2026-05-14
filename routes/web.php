<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

// Halaman Utama
Route::get('/', function () {
    return view('home'); 
})->name('home');

// Halaman Shop (Menampilkan Produk dari Database)
Route::get('/shop', [ShopController::class, 'show'])->name('shop');

// Auth Routes (hanya untuk guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'show_login'])->name('login');
    Route::post('/login_auth', [AuthController::class, 'login_auth'])->name('login.auth');
    Route::get('/register', [AuthController::class, 'show_register'])->name('register');
    Route::post('/register_auth', [AuthController::class, 'register_auth'])->name('register.auth');
});

// Product Routes (hanya untuk admin)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/products/insert-product', [ShopController::class, 'insert_product'])->name('products.insert');
    Route::post('/products', [ShopController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit-product', [ShopController::class, 'edit_product'])->name('products.edit');
    Route::put('/products/{product}', [ShopController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ShopController::class, 'destroy'])->name('products.destroy');
});

// Protected Routes (hanya untuk authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show_profile'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update_profile'])->name('profile.update');
    Route::post('/logout', [ProfileController::class, 'logout'])->name('logout');
});