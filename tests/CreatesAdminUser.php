<?php

namespace Tests;

use App\Models\User;
use Spatie\Permission\Models\Role;

trait CreatesAdminUser
{
    protected function makeAdmin(?array $overrides = []): User
    {
        $roleName = config('filament-shield.super_admin.name', 'super_admin');

        $role = Role::firstOrCreate(
            ['name' => $roleName, 'guard_name' => 'admin'],
            []
        );

        $user = User::factory()->create($overrides);
        $user->assignRole($role);

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        return $user;
    }
}
