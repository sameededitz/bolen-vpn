<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SocialController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\PurchaseController;
use App\Http\Controllers\Api\ResourceController;
use App\Http\Controllers\UserFeedbackController;

Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('api.login');
    Route::post('/signup', [AuthController::class, 'signup'])->name('api.signup');

    Route::post('/login/google', [SocialController::class, 'handleGoogleCallback'])->name('api.auth.google');
    Route::post('/login/apple', [SocialController::class, 'handleAppleCallback'])->name('api.auth.apple');

    Route::post('/email/resend-verification', [AccountController::class, 'resendEmail'])->name('api.verify.resend');
    Route::get('/email/verify/{id}/{hash}', [AccountController::class, 'verifyEmail'])->name('verification.verify');

    Route::post('/forgot-password', [AccountController::class, 'sendResetLink'])->name('api.password.reset');
    Route::post('/reset-password', [AccountController::class, 'resetPassword'])->name('api.password.update');
});

Route::middleware(['auth:sanctum', 'authorized', 'touch'])->group(function () {
    Route::get('/user', [UserController::class, 'user'])->name('api.user');
    Route::post('/user/update', [UserController::class, 'updateProfile'])->name('api.profile.update');
    Route::post('/user/update-password', [UserController::class, 'updatePassword'])->name('api.profile.update.password');
    Route::delete('/user/delete', [UserController::class, 'deleteAccount'])->name('api.profile.delete');
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

    Route::get('/purchase/active', [PurchaseController::class, 'active'])->name('api.plan.active');
    Route::get('/purchase/history', [PurchaseController::class, 'history'])->name('api.plan.history');
    Route::post('/purchase/generate', [PurchaseController::class, 'generateCode'])->name('api.add.purchase');
    Route::post('/purchase/redeem', [PurchaseController::class, 'redeemActivationCode'])->name('api.purchase.redeem');

    Route::get('/devices', [UserController::class, 'devices'])->name('api.user.devices');
    Route::delete('/devices/{id}', [UserController::class, 'revoke'])->name('api.user.device.revoke');
    Route::delete('/devices', [UserController::class, 'revokeAllExceptCurrent'])->name('api.user.devices.revokeAll');

    Route::get('/servers', [ResourceController::class, 'servers'])->name('api.all.servers');
    Route::get('/plans', [ResourceController::class, 'plans'])->name('api.all.plans');
    Route::get('/sliders', [ResourceController::class, 'sliders'])->name('api.all.sliders');
    Route::get('/options', [ResourceController::class, 'options'])->name('api.options');
});

Route::post('/feedback/store', [UserFeedbackController::class, 'store'])->name('api.feedback.store');
