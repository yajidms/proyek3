<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $auth = $request->header('Authorization');
        if (! $auth || ! str_starts_with($auth, 'Bearer ')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $token = substr($auth, 7);

        try {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        $request->attributes->set('jwt', $decoded);
        return $next($request);
    }
}
