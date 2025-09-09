<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\RoleRepositoryInterface;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    protected $users;
    protected $roles;

    public function __construct(UserRepositoryInterface $users, RoleRepositoryInterface $roles)
    {
        $this->users = $users;
        $this->roles = $roles;
    }

    public function registerUser(array $data)
    {
        $role = $this->roles->getRoleByName('user');
        return $this->users->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $role->id,
            'is_verified' => false,
        ]);
    }

    public function authenticate(array $credentials)
    {
        return auth()->attempt($credentials);
    }

    public function login(array $credentials)
    {
        $token = JWTAuth::attempt($credentials);
        if (!$token) {
            return ['token' => null];
        }
        $user = JWTAuth::user();
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => $user,
            'token' => $token // for internal check
        ];
    }

    public function getAuthenticatedUser()
    {
        $user = JWTAuth::user();
        if (!$user) {
            return null;
        }
        $data = $user->toArray();
        if (isset($user->role)) {
            $data['role_name'] = $user->role->name;
        }
        return $data;
    }

    public function refreshToken()
    {
        $newToken = JWTAuth::refresh(JWTAuth::getToken());
        $user = JWTAuth::user();
        return [
            'access_token' => $newToken,
            'token_type'   => 'bearer',
            'expires_in'   => JWTAuth::factory()->getTTL() * 60,
            'user'         => $user
        ];
    }
}
