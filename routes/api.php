<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\PasswordResetController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'show']);
    Route::put('/user', [UserController::class, 'update']);
    Route::delete('/user', [UserController::class, 'destroy']);
});

Route::post('password/forgot', [PasswordResetController::class, 'sendResetLinkEmail'])->middleware('throttle:6,1');
Route::post('password/reset', [PasswordResetController::class, 'resetPassword'])->middleware('throttle:6,1');

require __DIR__ . '/api/shortLink.php';
require __DIR__ . "/api/auth.php";
