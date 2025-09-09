<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class RoleService
{
    public static function splitRoleDataAndPermissionIds(array $data): array
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
}