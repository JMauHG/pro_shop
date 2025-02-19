<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::middleware('role:seller')->group(function () {
        Route::apiResource('stores', StoreController::class);
        Route::prefix('stores/{store}')->group(function () {
            Route::apiResource('products', ProductController::class);
        });
        Route::get('stores/{store}/sales', [OrderController::class, 'getSalesByStore']);
    });

    Route::middleware('role:customer')->group(function () {
        Route::get('cart', [CartController::class, 'index']);
        Route::post('cart/add/{product}', [CartController::class, 'addProduct']);
        Route::post('cart/remove/{product}', [CartController::class, 'removeProduct']);
        Route::post('cart/{cart}/purchase', [OrderController::class, 'completePurchase']);
        Route::get('orders', [OrderController::class, 'index']);
    });
});