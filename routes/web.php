<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\logincontroller;
use App\Http\Controllers\registercontroller;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $products = Product::with(['brand', 'category', 'images'])
        ->where('is_active', true)
        ->orderByDesc('created_at')
        ->take(8)
        ->get();

    return view('main.cuahangmaytinh', compact('products'));
})->name('cuahangmaytinh');

// Guests see the register page; logged-in users are sent to account management.
Route::get('/register', function () {
    if (Auth::check()) {
        return redirect()->route('account')->with('status', 'You are already logged in.');
    }

    return view('verify.register');
})->name('register');

// Guests see the login page; logged-in users are sent to account management.
Route::get('/login', function () {
    if (Auth::check()) {
        return redirect()->route('account')->with('status', 'You are already logged in.');
    }

    return view('verify.login');
})->name('login');

Route::get('/shop', function () {
    return view('shop.shop');
})->name('shop');

Route::get('/product/{product?}', function (?Product $product = null) {
    $product ??= Product::with(['brand', 'category', 'images', 'specifications'])
        ->where('is_active', true)
        ->firstOrFail();

    $product->load(['brand', 'category', 'images', 'specifications']);

    return view('shop.single-product-page', compact('product'));
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

Route::redirect('/register.html', '/register', 301);
Route::redirect('/login.html', '/login', 301);

Route::post('/register', [registercontroller::class, 'register'])->name('register.submit');
Route::post('/login', [logincontroller::class, 'login'])->name('login.submit');
Route::post('/logout', [logincontroller::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/account', [AccountController::class, 'show'])->name('account');
    Route::put('/account', [AccountController::class, 'update'])->name('account.update');

    Route::middleware('admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/products/create', [AdminController::class, 'create'])->name('admin.products.create');
        Route::post('/admin/products', [AdminController::class, 'store'])->name('admin.products.store');
        Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products.index');
        Route::get('/admin/products/{product}', [AdminController::class, 'show'])->name('admin.products.show');
        Route::get('/admin/products/{product}/edit', [AdminController::class, 'edit'])->name('admin.products.edit');
        Route::put('/admin/products/{product}', [AdminController::class, 'update'])->name('admin.products.update');
        Route::delete('/admin/products/{product}', [AdminController::class, 'destroy'])->name('admin.products.destroy');

        Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.categories.index');
        Route::get('/admin/categories/create', [AdminController::class, 'createCategory'])->name('admin.categories.create');
        Route::post('/admin/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
        Route::get('/admin/categories/{category}/edit', [AdminController::class, 'editCategory'])->name('admin.categories.edit');
        Route::put('/admin/categories/{category}', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
        Route::delete('/admin/categories/{category}', [AdminController::class, 'destroyCategory'])->name('admin.categories.destroy');

        Route::get('/admin/history', [AdminController::class, 'history'])->name('admin.history');
    });
});

Route::get('/{path}', function () {
    return redirect()->route('login');
})->where('path', 'shop(?:\.blade\.php)?|single-product-page(?:\.blade\.php)?|checkout(?:\.blade\.php)?|cart(?:\.blade\.php)?');
