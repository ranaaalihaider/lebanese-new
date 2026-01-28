<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\BuyerController;


Route::get('/', [BuyerController::class, 'index'])->name('home');
Route::view('/about', 'about')->name('about');
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar', 'hy', 'fr'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');


// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register/seller', [AuthController::class, 'showRegisterSeller'])->name('register.seller');
Route::post('/register/seller', [AuthController::class, 'registerSeller']);

Route::get('/register/buyer', [AuthController::class, 'showRegisterBuyer'])->name('register.buyer');
Route::post('/register/buyer', [AuthController::class, 'registerBuyer']);

Route::get('/verify', [AuthController::class, 'showVerify'])->name('verify');
Route::post('/verify', [AuthController::class, 'verify']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard Redirection
Route::middleware(['auth'])->get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role === 'admin')
        return redirect()->route('admin.dashboard');
    if ($user->role === 'seller')
        return redirect()->route('seller.dashboard');
    return redirect()->route('buyer.orders.index');
})->name('dashboard');

// Role Based Routes
Route::middleware(['auth'])->group(function () {
    // Admin
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/sellers', [AdminController::class, 'sellers'])->name('sellers');
        Route::post('/sellers/{id}/approve', [AdminController::class, 'approveSeller'])->name('sellers.approve');
        Route::post('/sellers/{id}/reject', [AdminController::class, 'rejectSeller'])->name('sellers.reject');

        Route::get('/sellers/{id}/edit', [AdminController::class, 'editSeller'])->name('sellers.edit');
        Route::put('/sellers/{id}', [AdminController::class, 'updateSeller'])->name('sellers.update');
        Route::post('/sellers/{id}/approve', [AdminController::class, 'approveSeller'])->name('sellers.approve');
        Route::post('/sellers/{id}/reject', [AdminController::class, 'rejectSeller'])->name('sellers.reject');
        Route::post('/sellers/{id}/deactivate', [AdminController::class, 'deactivateSeller'])->name('sellers.deactivate');
        Route::post('/sellers/{id}/activate', [AdminController::class, 'activateSeller'])->name('sellers.activate');
        Route::get('/buyers', [AdminController::class, 'buyers'])->name('buyers');
        Route::get('/buyers/{id}', [AdminController::class, 'showBuyer'])->name('buyers.show');

        Route::get('/products', [AdminController::class, 'products'])->name('products.index');
        Route::get('/earnings', [AdminController::class, 'earnings'])->name('earnings');
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders.index');
        Route::get('/lists', [AdminController::class, 'lists'])->name('lists');
        Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('orders.show');
        Route::patch('/products/{product}/toggle-status', [AdminController::class, 'toggleProductStatus'])->name('products.toggle');
        Route::get('/stores/{seller}', [AdminController::class, 'showStore'])->name('stores.show');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');

        Route::get('/payouts', [\App\Http\Controllers\AdminPayoutController::class, 'index'])->name('payouts.index');
        Route::post('/payouts/{order}/release', [\App\Http\Controllers\AdminPayoutController::class, 'payout'])->name('payouts.release');

        Route::resource('product-types', \App\Http\Controllers\ProductTypeController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('store-types', \App\Http\Controllers\StoreTypeController::class)->only(['index', 'store', 'update', 'destroy']);
    });

    // Seller
    Route::prefix('seller')->name('seller.')->middleware([\App\Http\Middleware\CheckSellerStatus::class])->group(function () {
        Route::get('/dashboard', [SellerController::class, 'dashboard'])->name('dashboard');
        Route::resource('products', \App\Http\Controllers\ProductController::class);
        Route::get('/orders/{status?}', [SellerController::class, 'orders'])->name('orders.index');
        Route::get('/order/{order}', [SellerController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [SellerController::class, 'updateOrderStatus'])->name('orders.update-status');
        Route::get('/earnings', [SellerController::class, 'earnings'])->name('earnings');
        Route::patch('/products/{product}/toggle-status', [\App\Http\Controllers\ProductController::class, 'toggleStatus'])->name('products.toggle');

    });

    // Generic Profile
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // Buyer Protected Routes
    // Cart Checkout Routes
    Route::get('/buyer/checkout/cart', [\App\Http\Controllers\CheckoutController::class, 'showCart'])->name('buyer.checkout.cart');
    Route::post('/buyer/checkout/cart', [\App\Http\Controllers\CheckoutController::class, 'storeCart'])->name('buyer.checkout.cart.store');

    // Single Product Checkout Routes
    Route::get('/buyer/checkout/{product}', [\App\Http\Controllers\CheckoutController::class, 'show'])->name('buyer.checkout.show');
    Route::post('/buyer/checkout/{product}', [\App\Http\Controllers\CheckoutController::class, 'store'])->name('buyer.checkout.store');
    Route::get('/buyer/orders', [\App\Http\Controllers\BuyerController::class, 'orders'])->name('buyer.orders.index');
    Route::get('/buyer/orders/{order}', [\App\Http\Controllers\BuyerController::class, 'showOrder'])->name('buyer.orders.show');

    // Order Routes
    Route::get('/buyer/orders', [App\Http\Controllers\OrderController::class, 'index'])->name('buyer.orders.index');
    Route::get('/buyer/orders/{order}', [App\Http\Controllers\OrderController::class, 'show'])->name('buyer.orders.show');
    Route::post('/buyer/orders/{order}/cancel', [App\Http\Controllers\OrderController::class, 'cancel'])->name('buyer.orders.cancel');

    // Review Routes
    Route::get('/buyer/orders/{order}/review', [App\Http\Controllers\ReviewController::class, 'create'])->name('buyer.reviews.create');
    Route::post('/buyer/orders/{order}/review', [App\Http\Controllers\ReviewController::class, 'store'])->name('buyer.reviews.store');
    Route::get('/buyer/reviews/{review}/edit', [App\Http\Controllers\ReviewController::class, 'edit'])->name('buyer.reviews.edit');
    Route::put('/buyer/reviews/{review}', [App\Http\Controllers\ReviewController::class, 'update'])->name('buyer.reviews.update');

    // Cart Routes
    Route::get('/buyer/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('buyer.cart.index');
    Route::post('/buyer/cart', [\App\Http\Controllers\CartController::class, 'store'])->name('buyer.cart.store');
    Route::delete('/buyer/cart/{id}', [\App\Http\Controllers\CartController::class, 'destroy'])->name('buyer.cart.destroy');

    // Wishlist Routes
    Route::get('/buyer/wishlist', [\App\Http\Controllers\WishlistController::class, 'index'])->name('buyer.wishlist.index');
    Route::post('/buyer/wishlist/toggle', [\App\Http\Controllers\WishlistController::class, 'toggle'])->name('buyer.wishlist.toggle');
});

// Public Buyer Routes
Route::prefix('buyer')->name('buyer.')->group(function () {
    Route::get('/home', [BuyerController::class, 'index'])->name('home');
    Route::get('/stores', [BuyerController::class, 'stores'])->name('stores');
    Route::get('/stores/{seller}', [BuyerController::class, 'showStore'])->name('stores.show');
    Route::get('/products/{product}', [BuyerController::class, 'showProduct'])->name('products.show');
});
