<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;


class PasswordResetController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Password reset link sent to your email address.'], 200)
            : response()->json(['error' => 'Error sending password reset link.'], 500);
    }


    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ]);
        $response = Password::reset(
            $validated,
            function ($user) use ($request) {
                $user->password = Hash::make($request->password);
                $user->save();

                $user->tokens()->delete();
            }
        );

        if ($response === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password reset successfully.'],200);
        } elseif ($response === Password::INVALID_USER) {
            return response()->json(['error' => 'User not found.'], 404);
        } elseif ($response === Password::INVALID_TOKEN) {
            return response()->json(['error' => 'Invalid or expired token.'], 400);
        }

        return response()->json(['error' => 'Unable to reset password.'], 500);
    }
}
