<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Helpers\ActsAsFilamentAdmin;
use Tests\TestCase;

class UserResourceExtrasTest extends TestCase
{
    use RefreshDatabase, ActsAsFilamentAdmin;

    public function test_search_by_email_on_index(): void
    {
        $this->loginAsFilamentAdmin();

        User::factory()->create(['name' => 'Alpha', 'email' => 'alpha@example.com']);
        User::factory()->create(['name' => 'Beta',  'email' => 'beta@example.com']);

        $this->get(UserResource::getUrl().'?tableSearch=beta@')
            ->assertOk()
            ->assertSee('beta@example.com')
            ->assertDontSee('alpha@example.com');
    }

    public function test_self_delete_is_blocked_if_your_policy_or_action_blocks_it(): void
    {
        $admin = $this->loginAsFilamentAdmin();

        // Kendi kaydı
        Livewire::test(EditUser::class, ['record' => $admin->getKey()])
            ->callAction('delete')
            ->assertHasActionErrors(); // Policy/action engelliyorsa hata bekleriz
    }
}
