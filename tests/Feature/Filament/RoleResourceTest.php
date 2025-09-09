<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\RoleResource;
use App\Filament\Resources\RoleResource\Pages\CreateRole;
use App\Filament\Resources\RoleResource\Pages\EditRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\Helpers\ActsAsFilamentAdmin;
use Tests\TestCase;

class RoleResourceTest extends TestCase
{
    use RefreshDatabase, ActsAsFilamentAdmin;

    public function test_index_loads(): void
    {
        $this->loginAsFilamentAdmin();

        $this->get(RoleResource::getUrl())->assertOk();
    }

    public function test_create_role_guard_is_admin(): void
    {
        $this->loginAsFilamentAdmin();

        Livewire::test(CreateRole::class)
            ->fillForm([
                'name' => 'editor',
                // formda guard alanı yoksa modelde default 'admin' olmalı
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('roles', [
            'name'       => 'editor',
            'guard_name' => 'admin',
        ]);
    }

    public function test_can_rename_role_and_keep_guard(): void
    {
        $this->loginAsFilamentAdmin();

        $role = Role::query()->create(['name' => 'manager', 'guard_name' => 'admin']);

        Livewire::test(EditRole::class, ['record' => $role->getKey()])
            ->fillForm(['name' => 'operations-manager'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('roles', [
            'id'         => $role->id,
            'name'       => 'operations-manager',
            'guard_name' => 'admin',
        ]);
    }
}
