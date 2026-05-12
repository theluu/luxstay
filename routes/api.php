<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me',      [AuthController::class, 'me']);

        Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

        Route::apiResource('rooms',      RoomController::class);
        Route::apiResource('bookings',   BookingController::class);
        Route::apiResource('posts',      PostController::class);
        Route::apiResource('products',   ProductController::class);
        Route::apiResource('orders',     OrderController::class);
        Route::apiResource('activities', ActivityController::class);
    });
});
