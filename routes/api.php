<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\PasswordResetController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users/profile', [UserController::class, 'show']);
    Route::put('/users/profile', [UserController::class, 'update']);
    Route::delete('/users/profile', [UserController::class, 'destroy']);
});

Route::post('password/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->middleware('throttle:6,1');
Route::post('password/reset-password', [PasswordResetController::class, 'resetPassword'])->middleware('throttle:6,1');

require __DIR__ . '/api/shortLink.php';
require __DIR__ . "/api/auth.php";
