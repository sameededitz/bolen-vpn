<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PurchaseController;
use App\Http\Controllers\Api\ServerController;
use App\Http\Controllers\Api\SocialController;
use App\Http\Controllers\Api\VerifyController;
use App\Http\Controllers\OptionsController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SliderController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('api.login');

    Route::post('/signup', [AuthController::class, 'signup'])->name('api.signup');

    Route::post('/reset-password', [VerifyController::class, 'sendResetLink'])->name('api.reset.password');

    Route::post('/login/google', [SocialController::class, 'handleGoogleCallback'])->name('api.auth.google');

    Route::post('/login/apple', [SocialController::class, 'handleAppleCallback'])->name('api.auth.apple');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

    Route::post('/purchase', [PurchaseController::class, 'addPurchase'])->name('api.add.purchase');

    Route::post('/purchase/status', [PurchaseController::class, 'Status'])->name('api.purchase');

    Route::post('/purchase/verify', [PurchaseController::class, 'redeemActivationCode'])->name('api.purchase.verify');
});

Route::post('/email/resend-verification', [VerifyController::class, 'resendVerify'])->name('api.verify.resend');

Route::get('/servers', [ServerController::class, 'index'])->name('api.all.servers');

Route::get('/plans', [PlanController::class, 'plans'])->name('api.all.plans');

Route::get('/sliders', [SliderController::class, 'sliders'])->name('api.all.sliders');

Route::get('/options', [OptionsController::class, 'getOptions'])->name('api.options');
