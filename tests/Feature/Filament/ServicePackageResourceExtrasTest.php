<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\ServicePackageResource;
use App\Filament\Resources\ServicePackageResource\Pages\EditServicePackage;
use App\Models\ServicePackage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Helpers\ActsAsFilamentAdmin;
use Tests\Helpers\ModelSoftDeletes;
use Tests\TestCase;

class ServicePackageResourceExtrasTest extends TestCase
{
    use RefreshDatabase, ActsAsFilamentAdmin, ModelSoftDeletes;

    public function test_search_by_name_on_index(): void
    {
        $this->loginAsFilamentAdmin();

        ServicePackage::query()->create(['name' => 'Bakım Paketi']);
        ServicePackage::query()->create(['name' => 'Hızlı Paket']);

        $this->get(ServicePackageResource::getUrl().'?tableSearch=Bakım')
            ->assertOk()
            ->assertSee('Bakım Paketi')
            ->assertDontSee('Hızlı Paket');
    }

    public function test_delete_and_optional_restore(): void
    {
        $this->loginAsFilamentAdmin();

        $pkg = ServicePackage::query()->create(['name' => 'Silinecek Paket']);

        Livewire::test(EditServicePackage::class, ['record' => $pkg->getKey()])
            ->callAction('delete');

        $pkg->refresh();

        if ($this->modelUsesSoftDeletes(ServicePackage::class)) {
            $this->assertSoftDeleted('service_packages', ['id' => $pkg->id]);

            Livewire::test(EditServicePackage::class, ['record' => $pkg->getKey()])
                ->callAction('restore');

            $this->assertDatabaseHas('service_packages', ['id' => $pkg->id, 'deleted_at' => null]);
        } else {
            $this->assertDatabaseMissing('service_packages', ['id' => $pkg->id]);
        }
    }
}
