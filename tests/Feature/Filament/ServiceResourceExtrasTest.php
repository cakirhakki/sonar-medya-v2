<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\ServiceResource;
use App\Filament\Resources\ServiceResource\Pages\EditService;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Helpers\ActsAsFilamentAdmin;
use Tests\Helpers\ModelSoftDeletes;
use Tests\TestCase;

class ServiceResourceExtrasTest extends TestCase
{
    use RefreshDatabase, ActsAsFilamentAdmin, ModelSoftDeletes;

    public function test_search_by_name_on_index(): void
    {
        $this->loginAsFilamentAdmin();

        Service::query()->create(['name' => 'Saç Kesimi', 'price' => 300]);
        Service::query()->create(['name' => 'Sakal', 'price' => 150]);

        $this->get(ServiceResource::getUrl().'?tableSearch=Saç')
            ->assertOk()
            ->assertSee('Saç Kesimi')
            ->assertDontSee('Sakal');

        $this->get(ServiceResource::getUrl().'?tableSearch=Sakal')
            ->assertOk()
            ->assertSee('Sakal')
            ->assertDontSee('Saç Kesimi');
    }

    public function test_delete_and_optional_restore(): void
    {
        $this->loginAsFilamentAdmin();

        $s = Service::query()->create(['name' => 'Silinecek Servis', 'price' => 123]);

        Livewire::test(EditService::class, ['record' => $s->getKey()])
            ->callAction('delete');

        $s->refresh();

        if ($this->modelUsesSoftDeletes(Service::class)) {
            $this->assertSoftDeleted('services', ['id' => $s->id]);

            Livewire::test(EditService::class, ['record' => $s->getKey()])
                ->callAction('restore');

            $this->assertDatabaseHas('services', ['id' => $s->id, 'deleted_at' => null]);
        } else {
            $this->assertDatabaseMissing('services', ['id' => $s->id]);
        }
    }
}
