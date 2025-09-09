<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Services\RoleService;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        [$roleData, $permissionIds] = RoleService::splitRoleDataAndPermissionIds($data);
        /** @var Role $role */
        $role = Role::query()->create([
            'name'       => $roleData['name'],
            'guard_name' => $roleData['guard_name'],
        ]);

        $role->syncPermissions(
            Permission::whereIn('id', $permissionIds)->get()
        );

        return $role;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

