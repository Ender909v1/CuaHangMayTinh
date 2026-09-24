<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\logincontroller;
use App\Http\Controllers\registercontroller;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('main.cuahangmaytinh');
})->name('cuahangmaytinh');

Route::get('/register', function () {
    return view('verify.register');
})->name('register');

Route::get('/login', function () {
    return view('verify.login');
})->name('login');

Route::get('/shop', function () {
    return view('shop.shop');
})->name('shop');

Route::get('/product', function () {
    return view('shop.single-product-page');
})->name('product');

Route::get('/cart', function () {
    return view('cart.cart');
})->name('cart');

Route::get('/checkout', function () {
    return view('cart.checkout');
})->name('checkout');

Route::get('/404', function () {
    return view('error.404');
})->name('not-found');

Route::post('/register', [registercontroller::class, 'register'])->name('register.submit');
Route::post('/login', [logincontroller::class, 'login'])->name('login.submit');
Route::post('/logout', [logincontroller::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products.index');
    Route::get('/admin/products/{product}', [AdminController::class, 'show'])->name('admin.products.show');
    Route::get('/admin/products/{product}/edit', [AdminController::class, 'edit'])->name('admin.products.edit');
    Route::put('/admin/products/{product}', [AdminController::class, 'update'])->name('admin.products.update');
    Route::delete('/admin/products/{product}', [AdminController::class, 'destroy'])->name('admin.products.destroy');
    Route::get('/admin/history', [AdminController::class, 'history'])->name('admin.history');
});

Route::get('/{path}', function () {
    return redirect()->route('login');
})->where('path', 'shop(?:\.blade\.php)?|single-product-page(?:\.blade\.php)?|checkout(?:\.blade\.php)?|cart(?:\.blade\.php)?');
