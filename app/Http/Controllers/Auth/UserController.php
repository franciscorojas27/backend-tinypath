<?php

namespace App\Http\Controllers\Auth;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function show(Request $request)
    {
        return $request->user();
    }
    public function update(UpdateUserRequest $request)
    {
        $user = $request->user();

        if (!$user->update($request->all())) return response()->json(['error' => 'No se pudo actualizar el usuario'], 500);

        return response()->json(['message' => 'Usuario actualizado correctamente'], 200);
    }
    public function destroy(Request $request)
    {
        $user = $request->user();

        if (!Hash::check($request->input('password'), $user->password)) {
            return response()->json(['error' => 'Contraseña incorrecta'], 401);
        }

        try {
            $user->tokens()->delete();
            $user->delete();
            return response()->json(['message' => 'Usuario eliminado correctamente'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'No se pudo eliminar el usuario', 'details' => $e->getMessage()], 500);
        }
    }
}
