<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\AuthController;

// Public routes
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/refresh', [AuthController::class, 'refresh']);

// Protected routes - using middleware class directly
Route::middleware([\App\Http\Middleware\KeycloakMiddleware::class])->group(function () {
    Route::apiResource('subscribers', SubscriberController::class);
    Route::apiResource('subscriptions', SubscriptionController::class);
    Route::post('auth/logout', [AuthController::class, 'logout']);
});