<?php

use App\Models\ShortLink;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/{shortLink}', function ($shortLink, Request $request) {

    $shortLink = ShortLink::where('short_link', $shortLink)->first();

    $shortLink->metrics()->create([
        'ip_user' => $request->ip(),
        'user_agent' => $request->userAgent(),
        'country' => 'placeholder',
        'city' => 'placeholder',
        'device' => match (true) {
            Str::contains($request->userAgent(), 'Mobile') => 'mobile',
            Str::contains($request->userAgent(), ['Tablet', 'iPad', 'Android']) => 'tablet',
            default => 'desktop',
        },
        'referrer' => match (true) {
            Str::contains($request->server('HTTP_REFERER'), 'facebook') => 'facebook',
            Str::contains($request->server('HTTP_REFERER'), 'twitter') => 'twitter',
            Str::contains($request->server('HTTP_REFERER'), 'instagram') => 'instagram',
            Str::contains($request->server('HTTP_REFERER'), 'tiktok') => 'tiktok',
            default => $request->server('HTTP_REFERER', 'direct'),
        },
    ]);
    return redirect()->away($shortLink->original_link);
});
