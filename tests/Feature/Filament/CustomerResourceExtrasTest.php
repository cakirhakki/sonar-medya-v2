<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\CustomerResource\Pages\EditCustomer;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Helpers\ActsAsFilamentAdmin;
use Tests\Helpers\ModelSoftDeletes;
use Tests\TestCase;

class CustomerResourceExtrasTest extends TestCase
{
    use RefreshDatabase, ActsAsFilamentAdmin, ModelSoftDeletes;

    public function test_search_by_name_or_phone_on_index(): void
    {
        $this->loginAsFilamentAdmin();

        Customer::factory()->create(['name' => 'Ali Veli', 'phone' => '5551112233']);
        Customer::factory()->create(['name' => 'Mehmet',  'phone' => '5559998877']);

        $this->get(CustomerResource::getUrl().'?tableSearch=Ali')
            ->assertOk()
            ->assertSee('Ali Veli')
            ->assertDontSee('Mehmet');

        $this->get(CustomerResource::getUrl().'?tableSearch=999')
            ->assertOk()
            ->assertSee('Mehmet')
            ->assertDontSee('Ali Veli');
    }

    public function test_delete_and_optional_restore(): void
    {
        $this->loginAsFilamentAdmin();

        $c = Customer::factory()->create(['name' => 'Silinecek Müşteri']);

        // Delete (Edit sayfasındaki DeleteAction)
        Livewire::test(EditCustomer::class, ['record' => $c->getKey()])
            ->callAction('delete');

        $c->refresh();

        if ($this->modelUsesSoftDeletes(Customer::class)) {
            $this->assertSoftDeleted('customers', ['id' => $c->id]);

            // Restore (Edit sayfasında varsa RestoreAction)
            Livewire::test(EditCustomer::class, ['record' => $c->getKey()])
                ->callAction('restore');

            $this->assertDatabaseHas('customers', ['id' => $c->id, 'deleted_at' => null]);
        } else {
            $this->assertDatabaseMissing('customers', ['id' => $c->id]);
        }
    }
}
