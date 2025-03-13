<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [UserController::class, 'show']);
    Route::put('user', [UserController::class, 'update']);
    Route::delete('user',[UserController::class, 'destroy']);
});


Route::middleware('auth:sanctum')->get('/verify-token', function (Request $request) {
    return response()->json(['token' => $request->bearerToken()], 200);
});


require __DIR__ . '/api/shortLink.php';
require __DIR__ . "/api/auth.php";
