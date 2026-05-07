<?php

use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\RoomController;
use App\Http\Controllers\Web\BlogController;
use App\Http\Controllers\Web\ShopController;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\CheckoutController;
use App\Http\Controllers\Web\ActivityController;
use App\Http\Controllers\Web\PageController;
use App\Http\Controllers\Web\AccountController;
use App\Http\Controllers\Web\OrderController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
Route::get('/rooms/suites', [RoomController::class, 'suites'])->name('rooms.suites');
Route::get('/rooms/{slug}', [RoomController::class, 'show'])->name('rooms.show');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{slug}', [ShopController::class, 'show'])->name('shop.show');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index')->middleware('auth');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store')->middleware('auth');

Route::get('/activities/{slug}', [ActivityController::class, 'show'])->name('activities.show');

Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/offers', [PageController::class, 'offers'])->name('offers');
Route::get('/landing', [PageController::class, 'landing'])->name('landing');

// Auth-protected user routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/account', [AccountController::class, 'index'])->name('account.index');
    Route::get('/account/edit', [AccountController::class, 'edit'])->name('account.edit');
    Route::put('/account/edit', [AccountController::class, 'update'])->name('account.update');
    Route::get('/account/address', [AccountController::class, 'address'])->name('account.address');
    Route::put('/account/address', [AccountController::class, 'updateAddress'])->name('account.address.update');
    Route::get('/account/downloads', [AccountController::class, 'downloads'])->name('account.downloads');
    Route::get('/account/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/account/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// Admin SPA shell (Vue Router handles sub-routes)
Route::get('/admin/{any?}', fn () => view('layouts.admin'))
    ->where('any', '.*')
    ->middleware(['auth', 'admin'])
    ->name('admin');

require __DIR__.'/auth.php';
