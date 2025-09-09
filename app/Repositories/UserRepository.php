<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function allWithRole()
    {
        return User::with('role')->get();
    }

    public function find($id)
    {
        return User::find($id);
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update($id, array $data)
    {
        $user = User::findOrFail($id);
        $user->update($data);
        return $user;
    }
}
