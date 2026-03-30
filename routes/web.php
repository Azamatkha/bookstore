<?php

use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\ApiGuideController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreCheckoutController;
use App\Http\Controllers\StoreHomeController;
use App\Http\Controllers\Storefront\BookController as StoreBookController;
use App\Http\Controllers\Storefront\CartController;
use App\Http\Controllers\Storefront\OrderController as StoreOrderController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

Route::get('/lang/{locale}', function (string $locale) {
    if (! in_array($locale, ['en', 'uz', 'ru'])) {
        abort(400);
    }
    session()->put('locale', $locale);
    return back();
})->name('lang.switch');

Route::get('/', StoreHomeController::class)->name('home');
Route::get('/books/{book:slug}', [StoreBookController::class, 'show'])->name('books.show');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{book}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{book}', [CartController::class, 'destroy'])->name('cart.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => redirect()->route(auth()->user()->redirectRoute()))->name('dashboard');

    Route::get('/checkout', [StoreCheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [StoreCheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/wallet', [WalletController::class, 'show'])->name('wallet.show');
    Route::post('/wallet/top-up', [WalletController::class, 'topUp'])->name('wallet.top-up');

    Route::get('/orders', [StoreOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [StoreOrderController::class, 'show'])->name('orders.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::get('/api-docs', ApiGuideController::class)->name('api-docs');
        Route::resource('books', BookController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('authors', AuthorController::class);
        Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update']);
    });
});

require __DIR__.'/auth.php';
