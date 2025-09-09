<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class SuperAdminRoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'admin', // Shield ile aynı guard
        ]);
    }
}