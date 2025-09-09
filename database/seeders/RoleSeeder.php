<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::firstOrCreate(['name' => 'admin'], ['label' => 'Administrator']);
        Role::firstOrCreate(
            ['name' => 'verifikator'],
            ['label' => 'Verifikator']
        );
        Role::firstOrCreate(['name' => 'user'], ['label' => 'User Biasa']);
    }
}
