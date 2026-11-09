<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IntroController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PhoneLoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| متجر Alo: الروابط
|--------------------------------------------------------------------------
*/

// المدخل التفاعلي، وبعده الرئيسية حق المتجر
Route::get('/', IntroController::class)->name('intro');

// المتجر (مفتوح للجميع)
Route::get('/store', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::view('/about', 'pages.about')->name('pages.about');
Route::view('/policies', 'pages.policies')->name('pages.policies');
Route::view('/contact', 'pages.contact')->name('pages.contact');

// السلة (مفتوحة للزوار)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/{product}', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');

// الدخول برقم الجوال
Route::middleware('guest')->group(function () {
    Route::get('/login', [PhoneLoginController::class, 'show'])->name('login');
    Route::post('/login', [PhoneLoginController::class, 'sendCode'])->middleware('throttle:5,1');
    Route::get('/login/verify', [PhoneLoginController::class, 'showVerify'])->name('login.verify');
    Route::post('/login/verify', [PhoneLoginController::class, 'verify'])->middleware('throttle:10,1');
    Route::post('/login/resend', [PhoneLoginController::class, 'resend'])->name('login.resend')->middleware('throttle:3,1');
});

// للعملاء المسجلين
Route::middleware('auth')->group(function () {
    Route::post('/logout', [PhoneLoginController::class, 'logout'])->name('logout');
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// لوحة التحكم
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', Admin\ProductController::class)->except('show');
    Route::resource('categories', Admin\CategoryController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('orders', Admin\OrderController::class)->only(['index', 'show', 'update']);
});
