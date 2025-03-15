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
            throw new ShortLinkException("No se encontraron enlaces para el usuario", 404);
        }

        return response()->json($shortLinks);
    }
    public function store(ShortLinkRequest $ShortLinkRequest, Request $request)
    {
        $user = $request->user();
        $shortLink = ShortLink::create([
            'user_id' => $user->id,
            'original_link' => $ShortLinkRequest->original_link,
            'short_link' => $ShortLinkRequest->short_link ?? $this->generateShortLink(),
            'expire_at' => now()->addDays(7),
        ]);
        if (!$shortLink) {
            throw new ShortLinkException("No se pudo crear el enlace", 500);
        }

        return response()->json(['message' => 'Enlace creado correctamente'], 200);
    }

    public function update(ShortLink $shortLink, ShortLinkRequest $request)
    {

        if ($request->user()->id !== $shortLink->user_id) {
            throw new ShortLinkException("No tienes permiso para actualizar este enlace", 403);
        }

        $shortLink->update($request->all());

        return response()->json(['message' => 'Enlace actualizado correctamente'], 200);
    }

    public function destroy(ShortLink $shortLink, Request $request)
    {

        if ($request->user()->id !== $shortLink->user_id) {
            throw new ShortLinkException("No tienes permiso para actualizar este enlace", 403);
        }

        $shortLink->delete();

        return response()->json(['message' => 'Enlace eliminado correctamente'], 200);
    }

    private function generateShortLink()
    {
        do {
            $slug = Str::random(8);
        } while (ShortLink::where('short_link', $slug)->exists());

        return $slug;
    }
    public function generateQr(ShortLink $shortLink, Request $request)
    {
        if (!$shortLink->user_id !== $request->user()->id) {
            throw new ShortLinkException("No se pudo crear el código QR", 500);
        }
        $renderer = new GDLibRenderer(400);
        $writer = new Writer($renderer);    
        $qrCode = $writer->writeString(config('app.url') . '/' . $shortLink->short_link, '');

        return response($qrCode, 200)->header('Content-Type', 'image/png');
    }
}
