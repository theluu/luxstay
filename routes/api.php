<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\PostCategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductCategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\SiteSettingController;
use App\Http\Controllers\Api\ContactMessageController;
use App\Http\Controllers\Api\SubscriberController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\Api\PaymentTransactionController;
use App\Http\Controllers\Api\AboutPageController;
use App\Http\Controllers\Api\FooterController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\PaymentSettingController;
use App\Http\Controllers\Api\TranslationController;
use App\Http\Controllers\Api\MailSettingController;
use App\Http\Controllers\Api\UserController;
use App\Models\RoomType;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.')->group(function () {

    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me',      [AuthController::class, 'me']);

        Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
        Route::post('/upload',         [UploadController::class, 'store']);

        Route::get('/room-types',              fn () => response()->json(['data' => RoomType::orderBy('name')->get(['id','name'])]));
        Route::get('/settings',    [SiteSettingController::class, 'index']);
        Route::put('/settings',    [SiteSettingController::class, 'update']);
        Route::get('/payment-settings', [PaymentSettingController::class, 'show']);
        Route::put('/payment-settings', [PaymentSettingController::class, 'update']);
        Route::get('/mail-settings',           [MailSettingController::class, 'show']);
        Route::put('/mail-settings',           [MailSettingController::class, 'update']);
        Route::post('/mail-settings/test',     [MailSettingController::class, 'testEmail']);

        Route::get('/users', [UserController::class, 'index']);
        Route::get('/about-page',   [AboutPageController::class, 'index']);
        Route::put('/about-page',   [AboutPageController::class, 'update']);
        Route::get('/footer',       [FooterController::class, 'index']);
        Route::put('/footer',       [FooterController::class, 'update']);
        Route::get('/menu',         [MenuController::class, 'index']);
        Route::put('/menu',         [MenuController::class, 'update']);

        Route::get('/comments',                      [CommentController::class, 'index']);
        Route::patch('/comments/{comment}/approve',  [CommentController::class, 'approve']);
        Route::delete('/comments/{comment}',         [CommentController::class, 'reject']);
        Route::post('/comments/{comment}/reply',     [CommentController::class, 'reply']);

        Route::get('/contact-messages',        [ContactMessageController::class, 'index']);
        Route::patch('/contact-messages/{contactMessage}/read', [ContactMessageController::class, 'markRead']);

        Route::get('/subscribers',             [SubscriberController::class, 'index']);
        Route::delete('/subscribers/{subscriber}', [SubscriberController::class, 'destroy']);
        Route::get('/post-categories',    [PostCategoryController::class, 'index']);
        Route::get('/product-categories', [ProductCategoryController::class, 'index']);

        Route::apiResource('rooms',      RoomController::class);
        Route::apiResource('bookings',   BookingController::class);
        Route::apiResource('posts',      PostController::class);
        Route::apiResource('products',   ProductController::class);
        Route::apiResource('orders',     OrderController::class);
        Route::apiResource('activities', ActivityController::class);
        Route::apiResource('sliders',    SliderController::class);
        Route::get('/payment-transactions', [PaymentTransactionController::class, 'index']);
        Route::get('/payment-transactions/{paymentTransaction}', [PaymentTransactionController::class, 'show']);

        // Translations management
        Route::post('/translations/translate',               [TranslationController::class, 'translate']);
        Route::get('/translations/{model}',                  [TranslationController::class, 'index']);
        Route::put('/translations/{model}/{id}',             [TranslationController::class, 'update']);
        Route::delete('/translations/{model}/{id}/{locale}', [TranslationController::class, 'clearLocale']);
    });
});
