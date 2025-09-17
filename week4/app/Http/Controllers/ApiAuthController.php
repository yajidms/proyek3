<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiAuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $validated['email'])->first();
        if (! $user || ! Hash::check($validated['password'], $user->password) || ! $user->is_active) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $now = time();
        $exp = $now + 60 * 60; // 1 hour
        $roles = $user->getRoleNames()->toArray();
        $payload = [
            'iss' => config('app.url'),
            'iat' => $now,
            'nbf' => $now,
            'exp' => $exp,
            'sub' => $user->id,
            'role' => $roles,
        ];

        $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');
        return response()->json(['token' => $token]);
    }

    public function profile(Request $request)
    {
        $jwt = $request->attributes->get('jwt');
        $user = User::find($jwt->sub);
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $user->getRoleNames(),
        ]);
    }

    public function courses()
    {
        return Course::orderBy('code')->get();
    }
}
