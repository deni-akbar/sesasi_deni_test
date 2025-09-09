<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $adminRole = Role::where('name', 'admin')->first();
        $verRole = Role::where('name', 'verifikator')->first();
        $userRole = Role::where('name', 'user')->first();
        User::firstOrCreate(['email' => 'admin@example.com'], [
            'name' => 'Admin',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
            'is_verified' => true
        ]);
        User::firstOrCreate(['email' => 'verif@example.com'], [
            'name' => 'Verifikator',
            'password' => Hash::make('password'),
            'role_id' => $verRole->id,
            'is_verified' => true
        ]);
        User::firstOrCreate(['email' => 'user@example.com'], [
            'name' => 'User',
            'password' => Hash::make('password'),
            'role_id' => $userRole->id,
            'is_verified' => false
        ]);
        User::firstOrCreate(['email' => 'user99@example.com'], [
            'name' => 'User',
            'password' => Hash::make('password'),
            'role_id' => $userRole->id,
            'is_verified' => false
        ]);
    }
}
