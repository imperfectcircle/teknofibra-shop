<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('products', ProductController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('customers', CustomerController::class);
    
    Route::get('/countries', [CustomerController::class, 'countries']);

    Route::controller(OrderController::class)->group(function () {
        Route::get('/orders', 'index');
        Route::get('/orders/statuses', 'getStatuses');
        Route::post('/orders/change-status/{order}/{status}', 'changeStatus');
        Route::get('/orders/{order}', 'view');
    });

    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard/customers-count', 'activeCustomers');
        Route::get('/dashboard/products-count', 'activeProducts');
        Route::get('/dashboard/orders-count', 'paidOrders');
        Route::get('/dashboard/income-amount', 'totalIncome');
        Route::get('/dashboard/orders-by-country', 'ordersByCountry');
        Route::get('/dashboard/latest-customers', 'latestCustomers');
        Route::get('/dashboard/latest-orders', 'latestOrders');
    });
});

Route::post('/login', [AuthController::class, 'login']);