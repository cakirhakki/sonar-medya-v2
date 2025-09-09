<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\RoleResource;
use App\Filament\Resources\RoleResource\Pages\EditRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\Helpers\ActsAsFilamentAdmin;
use Tests\TestCase;

class RoleResourceExtrasTest extends TestCase
{
    use RefreshDatabase, ActsAsFilamentAdmin;

    public function test_search_by_name_on_index(): void
    {
        $this->loginAsFilamentAdmin();

        Role::query()->create(['name' => 'editor', 'guard_name' => 'admin']);
        Role::query()->create(['name' => 'manager', 'guard_name' => 'admin']);

        $this->get(RoleResource::getUrl().'?tableSearch=edit')
            ->assertOk()
            ->assertSee('editor')
            ->assertDontSee('manager');
    }

    public function test_delete_role(): void
    {
        $this->loginAsFilamentAdmin();

        $role = Role::query()->create(['name' => 'to-delete', 'guard_name' => 'admin']);

        Livewire::test(EditRole::class, ['record' => $role->getKey()])
            ->callAction('delete');

        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }
}
