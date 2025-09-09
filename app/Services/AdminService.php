<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\RoleRepositoryInterface;
use App\Repositories\LeaveRepositoryInterface;

class AdminService
{
    protected $users;
    protected $roles;
    protected $leaves;

    public function __construct(UserRepositoryInterface $users, RoleRepositoryInterface $roles, LeaveRepositoryInterface $leaves)
    {
        $this->users = $users;
        $this->roles = $roles;
        $this->leaves = $leaves;
    }

    public function getAllUsers()
    {
        return $this->users->allWithRole();
    }

    public function findUser($id)
    {
        return $this->users->find($id);
    }

    public function createVerifier(array $validated)
    {
        $role = $this->roles->getRoleByName('verifikator');
        return $this->users->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $role->id,
            'is_verified' => true,
        ]);
    }

    public function promoteToVerifier($id)
    {
        $user = $this->users->find($id);
        $role = $this->roles->getRoleByName('verifikator');
        $user->role_id = $role->id;
        $user->is_verified = true;
        $user->save();
        return $user;
    }

    public function resetPassword($id, $password)
    {
        $user = $this->users->find($id);
        $user->password = Hash::make($password);
        $user->save();
    }

    public function getAllLeaveRequests($req)
    {
        return $this->leaves->getAllWithUser($req);
    }
}
