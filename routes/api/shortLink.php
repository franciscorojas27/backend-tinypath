<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortLinkController;


Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/shortLinks', [ShortLinkController::class, 'index']);

    Route::post('/shortLinks', [ShortLinkController::class, 'store']);

    Route::put('/shortLinks/{shortLink}', [ShortLinkController::class, 'update']);

    Route::delete('/shortLinks/{shortLink}', [ShortLinkController::class, 'destroy']);
});
