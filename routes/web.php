<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\logincontroller;
use App\Http\Controllers\registercontroller;
use App\Http\Controllers\ReviewController;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $baseProducts = fn () => Product::with(['brand', 'category', 'images'])
        ->where('is_active', true);

    $latestProducts = (clone $baseProducts())
        ->orderByDesc('created_at')
        ->orderByDesc('id')
        ->take(4)
        ->get();

    $popularProducts = (clone $baseProducts())
        ->withSum('orderItems as total_sold', 'quantity')
        ->orderByDesc('total_sold')
        ->orderByDesc('created_at')
        ->orderByDesc('id')
        ->take(4)
        ->get();

    // Backwards-compatible list used by tests / other sections expecting $products.
    $products = $latestProducts->concat($popularProducts->whereNotIn('id', $latestProducts->modelKeys()));

    return view('main.cuahangmaytinh', compact('products', 'latestProducts', 'popularProducts'));
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

Route::get('/shop', function (Request $request) {
    $brands = Brand::query()
        ->whereHas('products', fn ($query) => $query->where('is_active', true))
        ->orderBy('name')
        ->get();
    $categories = Category::query()
        ->whereHas('products', fn ($query) => $query->where('is_active', true))
        ->orderBy('name')
        ->get();
    $sort = $request->query('sort', 'latest');
    if (! in_array($sort, ['latest', 'popular', 'az'])) {
        $sort = 'latest';
    }

    $productsQuery = Product::with(['brand', 'category', 'images'])
        ->where('is_active', true);

    match ($sort) {
        'popular' => $productsQuery->withCount('views')->orderByDesc('views_count')->orderByDesc('id'),
        'az' => $productsQuery->orderBy('name')->orderBy('id'),
        default => $productsQuery->orderByDesc('created_at')->orderByDesc('id'),
    };

    $products = $productsQuery
        ->paginate(20)
        ->withQueryString();

    return view('shop.shop', compact('brands', 'categories', 'products', 'sort'));
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

// Customer review tab: everybody reads the reviews with the admin answers,
// logged-in customers can post their own review.
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews');
Route::post('/reviews', [ReviewController::class, 'store'])->middleware('auth')->name('reviews.store');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{billCode}', [CheckoutController::class, 'success'])->name('checkout.success');

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
    Route::get('/my-orders', [AccountController::class, 'orders'])->name('my-orders');
    Route::delete('/my-orders/{order}', [AccountController::class, 'cancelOrder'])->name('my-orders.cancel');
    Route::delete('/my-orders/{order}/items/{item}', [AccountController::class, 'removeOrderItem'])->name('my-orders.remove-item');

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

        Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders.index');
        Route::get('/admin/orders/{order}', [AdminController::class, 'showOrder'])->name('admin.orders.show');
        Route::put('/admin/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.status');
        Route::put('/admin/orders/{order}/payment', [AdminController::class, 'updateOrderPayment'])->name('admin.orders.payment');

        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users.index');
        Route::get('/admin/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/admin/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');

        Route::get('/admin/inventory', [AdminController::class, 'inventory'])->name('admin.inventory.index');
        Route::put('/admin/inventory/{product}/stock', [AdminController::class, 'updateStock'])->name('admin.inventory.update-stock');

        Route::put('/admin/reviews/{review}/response', [AdminController::class, 'respondToReview'])->name('admin.reviews.respond');
        Route::delete('/admin/reviews/{review}', [AdminController::class, 'destroyReview'])->name('admin.reviews.destroy');
    });
});

Route::get('/{path}', function () {
    return redirect()->route('login');
})->where('path', 'shop(?:\.blade\.php)?|single-product-page(?:\.blade\.php)?|checkout(?:\.blade\.php)?|cart(?:\.blade\.php)?');
