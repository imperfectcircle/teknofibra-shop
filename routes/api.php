<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DashboardController;

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('products', ProductController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('categories', CategoryController::class)->except('show');
    Route::get('/categories/tree', [CategoryController::class, 'getAsTree']);
    
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

    Route::controller(ReportController::class)->group(function () {
        Route::get('/report/orders', 'orders');
        Route::get('/report/customers', 'customers');
    });
});

Route::post('/login', [AuthController::class, 'login']);