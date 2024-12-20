<?php

use App\Http\Controllers\DiscountController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LangController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\SupportController;

Route::middleware(['guestOrVerified'])->group(function () {
    Route::controller(ProductController::class)->group(function () {
        Route::get('/', 'index')->name('home');
        Route::get('/category/{category:slug}', 'byCategory')->name('byCategory');
        Route::get('/product/{product:slug}', 'view')->name('product.view');
    });

    Route::prefix('/cart')->name('cart.')->group(function () {
        Route::controller(CartController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/add/{product:slug}', 'add')->name('add');
            Route::post('/remove/{product:slug}', 'remove')->name('remove');
            Route::post('/update-quantity/{product:slug}', 'updateQuantity')->name('update-quantity');
        });
    });
    
    Route::controller(SupportController::class)->group(function () {
        Route::get('/support', 'index')->name('support');
        Route::post('/support/submit', 'submit')->name('support.submit');
    });

    Route::controller(ReturnController::class)->group(function() {
        Route::get('/return-policy', 'index')->name('return');
        Route::post('/return/submit', 'submit')->name('return.submit');
    });
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'view')->name('profile');
        Route::post('/profile', 'store')->name('profile.update');
        Route::post('/profile/password-update', 'passwordUpdate')->name('profile_password.update');
    });
    Route::controller(CheckoutController::class)->group(function () {
        Route::post('/checkout', 'checkout')->name('checkout');
        Route::post('/checkout/{order}', 'checkoutOrder')->name('cart.checkout-order');
        Route::get('/checkout/success', 'success')->name('checkout.success');
        Route::get('/checkout/failure', 'failure')->name('checkout.failure');
    });
    Route::controller(OrderController::class)->group(function () {
        Route::get('/orders', 'index')->name('orders.index');
        Route::get('/orders/view/{order}', 'view')->name('order.view');
    });

    Route::post('/apply-discount', [DiscountController::class, 'apply']);
});

Route::post('/lang/{locale}', [LangController::class, 'lang'])->name('public.lang');

Route::post('/webhook/stripe', [CheckoutController::class, 'webhook'])->name('webhook.stripe');

require __DIR__.'/auth.php';
