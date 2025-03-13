<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\ShortLinkRequest;

class ShortLinkController extends Controller
{
    public function index(Request $request)
    {
        $shortLinks = $request->user()?->shortLinks()
            ->paginate(21);

        if ($shortLinks->isEmpty()) {
            return response()->json(
                ['error' => 'No se encontraron enlaces para el usuario'],
                404
            );
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
            'expire_at' => now()->addDays(7)
        ]);

        if (!$shortLink) {
            return response()->json(['error' => 'No se pudo crear el enlace'], 500);
        }

        return response()->json(['message' => 'Enlace creado correctamente'], 200);
    }

    public function update(ShortLink $shortLink, ShortLinkRequest $request)
    {
        if ($request->user()->id !== $shortLink->user_id) {
            return response()->json(['error' => 'No tienes permiso para eliminar este enlace'], 403);
        }

        if (!$shortLink->update($request->all())) {
            return response()->json(['error' => 'No se pudo actualizar el enlace'], 500);
        }
        return response()->json(['message' => 'Enlace actualizado correctamente'], 200);
    }

    public function destroy(ShortLink $shortLink, Request $request)
    {
        if ($request->user()->id !== $shortLink->user_id) {
            return response()->json(['error' => 'No tienes permiso para eliminar este enlace'], 403);
        }

        if (!$shortLink) {
            return response()->json(['error' => 'El enlace no existe'], 404);
        }

        if (!$shortLink->delete()) {
            return response()->json(['error' => 'No se pudo eliminar el enlace'], 500);
        }

        return response()->json(['message' => 'Enlace eliminado correctamente'], 200);
    }

    private function generateShortLink()
    {
        do {
            $slug = Str::random(8);
        } while (ShortLink::where('short_link', $slug)->exists());

        return $slug;
    }
}
