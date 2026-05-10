<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;

// Halaman Utama
Route::get('/', function () {
    return view('home'); 
})->name('home');

// Halaman Shop (Menampilkan Produk dari Database)
Route::get('/shop', [ShopController::class, 'show'])->name('shop');

// Rute CRUD Produk (Sementara dibuka tanpa Auth untuk testing Frontend)
Route::get('/products/insert-product', [ShopController::class, 'insert_product'])->name('products.insert');
Route::post('/products', [ShopController::class, 'store'])->name('products.store');
Route::get('/products/{product}/edit-product', [ShopController::class, 'edit_product'])->name('products.edit');
Route::put('/products/{product}', [ShopController::class, 'update'])->name('products.update');
Route::delete('/products/{product}', [ShopController::class, 'destroy'])->name('products.destroy');