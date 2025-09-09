<?php

namespace Tests\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http; // <-- EKLE
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

trait ActsAsFilamentAdmin
{
    protected function loginAsFilamentAdmin(?User $user = null): User
    {
        $user ??= User::factory()->create();

        // HIBP "uncompromised" kontrolü dış istek yapmasın
        Http::fake(); // <-- EKLE

        $this->grantFilamentAccess($user);
        $this->actingAs($user, 'admin');

        if (method_exists($this, 'withoutVite')) {
            $this->withoutVite();
        }

        return $user;
    }

    // tests/Helpers/ActsAsFilamentAdmin.php

    protected function grantFilamentAccess(User $user): void
    {
        $guard = 'admin';

        // Spatie izin cache'ini temizle (test sürecinde kritik)
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // Panel erişim izni
        $panelPermission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'access_admin', 'guard_name' => $guard]);

        // Hepsinde ortak eylemler (Filament Shield kalıbı)
        $actions = ['view_any', 'view', 'create', 'update', 'delete', 'delete_any', 'restore', 'restore_any', 'force_delete', 'force_delete_any', 'replicate', 'reorder'];

        // Tüm kaynak anahtarları (model snake_case)
        // Projende olanları koruduk: user, service, service_package, role, customer
        $resources = ['user', 'service', 'service_package', 'role', 'customer'];

        $permIds = [$panelPermission->id];

        foreach ($resources as $res) {
            foreach ($actions as $act) {
                $name = "{$act}_{$res}";
                $p = \Spatie\Permission\Models\Permission::firstOrCreate([
                    'name' => $name,
                    'guard_name' => $guard,
                ]);
                $permIds[] = $p->id;
            }
        }

        // Rol oluştur / güncelle ve kullanıcıya ver
        $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'test-admin', 'guard_name' => $guard]);

        $role->syncPermissions(\Spatie\Permission\Models\Permission::whereIn('id', $permIds)->get());

        if (!$user->hasRole($role)) {
            $user->assignRole($role);
        }
    }

    protected function disableGateBypass(): void
    {
        Gate::before(function () {
            return null;
        });
    }
}
