<?php

namespace App\Http\Controllers\Auth;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
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

        if (!$user->update($request->all())) return response()->json(['error' => 'Could not update user'], 500);

        return response()->json(['message' => 'User updated successfully.'], 200);
    }
    public function destroy(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'password' => ['required', 'string', 'min:8']
        ]);

        if (!Hash::check($request->input('password'), $user->password)) {
            return response()->json(['error' => 'Incorrect password.'], 401);
        }

        try {
            $user->tokens()->delete();
            $user->delete();
            return response()->json(['message' => 'User deleted successfully.'], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Database error. Could not delete user.'], 500);
        } catch (Exception $e) {
            return response()->json(['error' => 'Unexpected error.'], 500);
        }
    }
}
