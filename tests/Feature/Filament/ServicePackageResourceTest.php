<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\ServicePackageResource;
use App\Filament\Resources\ServicePackageResource\Pages\CreateServicePackage;
use App\Filament\Resources\ServicePackageResource\Pages\EditServicePackage;
use App\Models\Service;
use App\Models\ServicePackage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Helpers\ActsAsFilamentAdmin;
use Tests\TestCase;

class ServicePackageResourceTest extends TestCase
{
    use RefreshDatabase, ActsAsFilamentAdmin;

    public function test_index_loads(): void
    {
        $this->loginAsFilamentAdmin();
        $this->get(ServicePackageResource::getUrl())->assertOk();
    }

    public function test_can_create_package_with_items(): void
    {
        $this->loginAsFilamentAdmin();

        $cut   = Service::query()->create(['name' => 'Kesim', 'price' => 300]);
        $shave = Service::query()->create(['name' => 'Sakal', 'price' => 150]);

        Livewire::test(CreateServicePackage::class)
            ->fillForm([
                'name' => 'Bakım Paketi',
                'items' => [
                    ['service_id' => $cut->id, 'quantity' => 1],
                    ['service_id' => $shave->id, 'quantity' => 2],
                ],
                // varsa diğer alanlar
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $pkg = ServicePackage::where('name', 'Bakım Paketi')->firstOrFail();

        $this->assertSame(2, $pkg->services()->count());
        $this->assertSame(1, (int) $pkg->services()->find($cut->id)->pivot->quantity);
        $this->assertSame(2, (int) $pkg->services()->find($shave->id)->pivot->quantity);
    }

    public function test_can_update_package_items(): void
    {
        $this->loginAsFilamentAdmin();

        $wash = Service::query()->create(['name' => 'Yıkama', 'price' => 100]);
        $dry  = Service::query()->create(['name' => 'Fön', 'price' => 200]);

        // create
        Livewire::test(CreateServicePackage::class)
            ->fillForm([
                'name' => 'Groom Paket',
                'items' => [
                    ['service_id' => $wash->id, 'quantity' => 1],
                ],
            ])->call('create');

        $pkg = ServicePackage::where('name', 'Groom Paket')->firstOrFail();

        // edit: öğe setini değiştir
        Livewire::test(EditServicePackage::class, ['record' => $pkg->getKey()])
            ->fillForm([
                'items' => [
                    ['service_id' => $wash->id, 'quantity' => 2],
                    ['service_id' => $dry->id,  'quantity' => 1],
                ],
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $pkg->refresh();
        $this->assertSame(2, (int) $pkg->services()->find($wash->id)->pivot->quantity);
        $this->assertSame(1, (int) $pkg->services()->find($dry->id)->pivot->quantity);
    }
}
