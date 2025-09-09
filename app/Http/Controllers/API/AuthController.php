<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterUserRequest;
use App\Services\AuthService;

class AuthController extends Controller
{
    protected $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function register(RegisterUserRequest $request)
    {
        $validated = $request->validated();
        $user = $this->service->registerUser($validated);
        return response()->json(['message' => 'User registered. Await verification.', 'data' => $user], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $result = $this->service->login($credentials);
        if (!$result['token']) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
        return response()->json($result);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function me()
    {
        $user = $this->service->getAuthenticatedUser();
        return response()->json($user);
    }

    public function refresh()
    {
        try {
            $result = $this->service->refreshToken();
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token invalid or expired'], 401);
        }
    }
}
