<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortLinkController;


Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/shortLinks', [ShortLinkController::class, 'index']);

    Route::post('/shortLink', [ShortLinkController::class, 'store']);

    Route::put('/shortLink/{shortLink}', [ShortLinkController::class, 'update']);

    Route::delete('/shortLink/{shortLink}', [ShortLinkController::class, 'destroy']);
});
