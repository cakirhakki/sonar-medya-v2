<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\ServiceResource\Pages\CreateService;
use App\Filament\Resources\ServiceResource\Pages\EditService;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Helpers\ActsAsFilamentAdmin;
use Tests\TestCase;

class ServiceResourceUpdateSameNameTest extends TestCase
{
    use RefreshDatabase, ActsAsFilamentAdmin;

    public function test_can_update_service_without_changing_name(): void
    {
        $this->loginAsFilamentAdmin();

        Livewire::test(CreateService::class)
            ->fillForm([
                'name' => 'Sakal',
                'price' => 150,
                'duration_minutes' => 20,
            ])->call('create');

        $service = Service::where('name', 'Sakal')->firstOrFail();

        Livewire::test(EditService::class, ['record' => $service->getKey()])
            ->fillForm([
                'name' => 'Sakal', // aynı isim!
                'price' => 175,
                'duration_minutes' => 25,
            ])
            ->call('save')
            ->assertHasNoFormErrors(); // ← Burada hata olmamalı
    }
}
