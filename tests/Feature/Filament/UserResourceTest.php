<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Helpers\ActsAsFilamentAdmin;

class UserResourceTest extends TestCase
{
    use RefreshDatabase, ActsAsFilamentAdmin;

    public function test_guest_is_redirected_to_filament_login(): void
    {
        $this->disableGateBypass();

        $this->get(UserResource::getUrl())->assertRedirect('/admin/login');
    }

    public function test_index_loads_for_admin(): void
    {
        $this->loginAsFilamentAdmin();

        $this->get(UserResource::getUrl())->assertOk();
    }

    public function test_can_create_user_via_filament_form(): void
{
    $this->loginAsFilamentAdmin();

    Livewire::test(\App\Filament\Resources\UserResource\Pages\CreateUser::class)
        ->fillForm([
            'name'  => 'Demo User',
            'email' => 'demo.test@gmail.com',
            'phone' => '5551112233',

            // Karmaşık şifre (Büyük+küçük+rakam+sembol) → defaults() kurallarını sağlar
            'password' => 'Aa1!VeryStrong_1234',

            // Onay alanının adından emin olmadığımız için ikisini de dolduruyoruz
            'password_confirmation' => 'Aa1!VeryStrong_1234',
            'passwordConfirmation'  => 'Aa1!VeryStrong_1234',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('users', [
        'email' => 'demo.test@gmail.com',
        'name'  => 'Demo User',
        'phone' => '5551112233',
    ]);
}

public function test_can_update_user_via_filament_form(): void
{
    $this->loginAsFilamentAdmin();

    Livewire::test(\App\Filament\Resources\UserResource\Pages\CreateUser::class)
        ->fillForm([
            'name'  => 'Old Name',
            'email' => 'old.user@gmail.com',
            'phone' => '5550001122',
            'password' => 'Aa1!VeryStrong_1234',
            'password_confirmation' => 'Aa1!VeryStrong_1234',
            'passwordConfirmation'  => 'Aa1!VeryStrong_1234',
        ])->call('create');

    $user = \App\Models\User::whereEmail('old.user@gmail.com')->firstOrFail();

    Livewire::test(\App\Filament\Resources\UserResource\Pages\EditUser::class, ['record' => $user->getKey()])
        ->fillForm([
            'name'  => 'New Name',
            'phone' => '5550003344',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('users', [
        'id'    => $user->id,
        'name'  => 'New Name',
        'phone' => '5550003344',
    ]);
}

}
