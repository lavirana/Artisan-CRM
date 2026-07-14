<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function apiLogin(Request $request)
    {
        // 1. Validate the incoming input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'nullable|string', // Useful for distinguishing tokens (e.g., "React App", "Postman")
        ]);

        // 2. Locate the user record in the database
        $user = User::where('email', $request->email)->first();

        // 3. Verify user existence and password hash matches
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials do not match our records.'],
            ]);
        }

        // 4. Generate the secure personal access token
        $deviceName = $request->input('device_name', 'Default Device');
        $token = $user->createToken($deviceName)->plainTextToken;

        // 5. Return the user profile alongside the token as JSON
        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ], 200);
    }
}