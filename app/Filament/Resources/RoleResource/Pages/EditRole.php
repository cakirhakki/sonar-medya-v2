<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function splitRoleDataAndPermissionIds(array $data): array
    {
        $permIds = [];

        foreach (array_keys($data) as $key) {
            if (Str::startsWith($key, 'permissions_')) {
                $permIds = array_merge($permIds, Arr::wrap($data[$key] ?? []));
                unset($data[$key]);
            }
        }

        $permIds = Permission::query()
            ->where('guard_name', 'admin')
            ->whereIn('id', $permIds)
            ->pluck('id')
            ->all();

        $data['guard_name'] = 'admin';

        return [$data, array_values(array_unique($permIds))];
    }

    /** Filament v3 imzası: önce $record, sonra $data */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        /** @var Role $record */
        [$roleData, $permissionIds] = $this->splitRoleDataAndPermissionIds($data);

        $record->update([
            'name'       => $roleData['name'],
            'guard_name' => $roleData['guard_name'],
        ]);

        $record->syncPermissions(
            Permission::whereIn('id', $permissionIds)->get()
        );

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
