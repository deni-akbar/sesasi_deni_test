<?php

namespace App\Repositories;

use App\Models\Role;

class RoleRepository implements RoleRepositoryInterface
{
    public function getRoleByName($name)
    {
        return Role::where('name', $name)->first();
    }
}
