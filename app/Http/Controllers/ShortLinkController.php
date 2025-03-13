<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

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


    public function store(Request $request)
    {
        $user = $request->user();
        $shortLink = ShortLink::create([
            'user_id' => $user->id,
            'short_link' => Str::random(6),
            'original_link' => $request->original_link,
            'expire_at' => now()->addDays(7)
        ]);

        if (!$shortLink) {
            return response()->json(['error' => 'No se pudo crear el enlace'], 500);
        }

        return response()->json(['message' => 'Enlace creado correctamente'], 200);
    }

    public function update(ShortLink $shortLink, Request $request)
    {
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
}
