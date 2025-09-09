<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveTypeSeeder extends Seeder
{
    public function run()
    {
        DB::table('leave_types')->insert([
            [
                'name' => 'annual',
                'description' => 'Annual leave (cuti tahunan)',
                'default_quota' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'sick',
                'description' => 'Sick leave (cuti sakit)',
                'default_quota' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'maternity',
                'description' => 'Maternity leave (cuti melahirkan)',
                'default_quota' => 90,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
