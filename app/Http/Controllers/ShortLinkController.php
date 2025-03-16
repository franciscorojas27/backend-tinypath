<?php

namespace App\Http\Controllers;

use BaconQrCode\Writer;
use App\Models\ShortLink;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exceptions\ShortLinkException;
use App\Http\Requests\ShortLinkRequest;
use BaconQrCode\Renderer\GDLibRenderer;

class ShortLinkController extends Controller
{
    public function index(Request $request)
    {
        $shortLinks = $request->user()?->shortLinks()->paginate(21);

        if ($shortLinks->isEmpty()) {
            throw new ShortLinkException("No links found for the user.", 404);
        }

        return response()->json($shortLinks);
    }
    public function store(ShortLinkRequest $request)
    {
        $user = $request->user();
        $shortLink = ShortLink::create([
            'user_id' => $user->id,
            'original_link' => $request->original_link,
            'short_link' => $request->short_link ?? $this->generateShortLink(),
            'expire_at' => now()->addDays(7),
        ]);
        if (!$shortLink) {
            throw new ShortLinkException("Failed to create the link.", 422);
        }

        return response()->json(['message' => 'Link created successfully.'], 201);
    }

    public function update(ShortLink $shortLink, ShortLinkRequest $request)
    {
        if ($request->user()->id !== $shortLink->user_id) {
            throw new ShortLinkException("You do not have permission to update this link.", 401);
        }

        $shortLink->update([
            'original_link' => $request->original_link,
            'short_link' => $request->short_link ?? $shortLink->short_link,
        ]);
        return response()->json(['message' => 'Link updated successfully.'], 200);
    }

    public function destroy(ShortLink $shortLink, Request $request)
    {
        if ($request->user()->id !== $shortLink->user_id) {
            throw new ShortLinkException("You do not have permission to delete this link.", 401);
        }

        $shortLink->delete();

        return response()->json(['message' => 'Link deleted successfully.'], 200);
    }
    public function generateQr(ShortLink $shortLink, Request $request)
    {
        if ($shortLink->user_id !== $request->user()->id) {
            throw new ShortLinkException("You do not have permission to generate QR of this link.", 401);
        }
        $renderer = new GDLibRenderer(400);
        $writer = new Writer($renderer);
        $qrCode = $writer->writeString(config('app.url') . '/' . $shortLink->short_link, '');

        return response($qrCode, 200)->header('Content-Type', 'image/png');
    }
    private function generateShortLink()
    {
        do {
            $slug = Str::random(8);
        } while (ShortLink::where('short_link', $slug)->exists());

        return $slug;
    }
}
