<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    // Route::middleware('role:seller')->group(function () {
        Route::apiResource('stores', StoreController::class);

        // Rutas para productos (dentro de una tienda)
        Route::prefix('stores/{store}')->group(function () {
            Route::apiResource('products', ProductController::class);
        });
    // });
});