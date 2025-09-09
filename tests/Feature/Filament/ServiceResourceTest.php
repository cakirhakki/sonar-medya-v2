<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\ServiceResource;
use App\Filament\Resources\ServiceResource\Pages\CreateService;
use App\Filament\Resources\ServiceResource\Pages\EditService;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Helpers\ActsAsFilamentAdmin;
use Tests\TestCase;

class ServiceResourceTest extends TestCase
{
    use RefreshDatabase, ActsAsFilamentAdmin;

    public function test_index_loads(): void
    {
        $this->loginAsFilamentAdmin();

        $this->get(ServiceResource::getUrl())->assertOk();
    }

    public function test_can_create_service(): void
    {
        $this->loginAsFilamentAdmin();

        Livewire::test(CreateService::class)
            ->fillForm([
                'name' => 'Saç Kesimi',
                'price' => 350,
                'duration_minutes' => 30, // <-- GEREKLİ ALAN
                // 'is_active' => true,  // varsa
                // 'description' => '...', // varsa
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('services', [
            'name' => 'Saç Kesimi',
            'price' => 350,
            'duration_minutes' => 30,
        ]);
    }

    public function test_can_update_service(): void
    {
        $this->loginAsFilamentAdmin();

        // önce oluştur
        Livewire::test(\App\Filament\Resources\ServiceResource\Pages\CreateService::class)
            ->fillForm([
                'name' => 'Sakal',
                'price' => 150,
                'duration_minutes' => 20,
            ])
            ->call('create');

        $service = \App\Models\Service::where('name', 'Sakal')->firstOrFail();

        // edit: ADI FARKLI YAP → unique/özel kural çakışmalarını garantili şekilde aş
        Livewire::test(\App\Filament\Resources\ServiceResource\Pages\EditService::class, ['record' => $service->getKey()])
            ->fillForm([
                'name' => 'Sakal Deluxe', // <-- FARKLI & BENZERSİZ isim
                'price' => 175,
                'duration_minutes' => 25,
                // 'slug' => 'sakal-deluxe', // istersen açıkça gönder; yoksa Request kendisi üretir
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('services', [
            'id' => $service->id,
            'name' => 'Sakal Deluxe',
            'price' => 175,
            'duration_minutes' => 25,
        ]);
    }
}
