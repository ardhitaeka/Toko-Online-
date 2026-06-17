<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

// Guest routes (login/register)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Public landing - redirect based on auth
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin() ? redirect()->route('dashboard') : redirect()->route('store.index');
    }
    return redirect()->route('login');
});

// ==================== CUSTOMER STORE ====================
Route::middleware('auth')->prefix('store')->name('store.')->group(function () {
    Route::get('/', [StoreController::class, 'index'])->name('index');
    Route::get('/product/{product}', [StoreController::class, 'show'])->name('show');
    Route::post('/cart/add/{product}', [StoreController::class, 'addToCart'])->name('add-cart');
    Route::get('/cart', [StoreController::class, 'cart'])->name('cart');
    Route::post('/cart/update', [StoreController::class, 'updateCart'])->name('update-cart');
    Route::delete('/cart/{id}', [StoreController::class, 'removeFromCart'])->name('remove-cart');
    Route::get('/checkout', [StoreController::class, 'checkout'])->name('checkout');
    Route::post('/order', [StoreController::class, 'placeOrder'])->name('order');
    Route::get('/orders', [StoreController::class, 'myOrders'])->name('orders');
    Route::get('/orders/{order}', [StoreController::class, 'showOrder'])->name('orders.show');
    Route::get('/profile', [StoreController::class, 'profile'])->name('profile');
    Route::put('/profile', [StoreController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [StoreController::class, 'changePassword'])->name('profile.password');
});

// ==================== ADMIN PANEL ====================
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Categories
    Route::resource('categories', CategoryController::class);

    // Products
    Route::resource('products', ProductController::class);

    // Customers (admin manages customer records)
    Route::resource('customers', CustomerController::class);

    // Orders
    Route::resource('orders', OrderController::class);
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::patch('orders/{order}/payment', [OrderController::class, 'updatePayment'])->name('orders.update-payment');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export-excel');
});
