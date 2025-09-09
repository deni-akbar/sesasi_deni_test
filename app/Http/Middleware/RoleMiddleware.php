<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        if (!in_array($user->role->name, $roles)) {
            return response()->json(['error' => 'Forbidden - insufficient permission'], 403);
        }
        return $next($request);
    }
}
