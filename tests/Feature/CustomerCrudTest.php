<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_a_customer(): void
    {
        $customer = Customer::factory()->create([
            'email' => 'crud_create@example.com',
            'name'  => 'Create Test',
        ]);

        $this->assertDatabaseHas('customers', [
            'id'    => $customer->id,
            'email' => 'crud_create@example.com',
            'name'  => 'Create Test',
        ]);
    }

    public function test_updates_a_customer(): void
    {
        $customer = Customer::factory()->create(['name' => 'Old Name']);

        $customer->update(['name' => 'New Name']);

        $this->assertSame('New Name', $customer->fresh()->name);
        $this->assertDatabaseHas('customers', [
            'id'   => $customer->id,
            'name' => 'New Name',
        ]);
    }

    public function test_soft_deletes_a_customer(): void
    {
        $customer = Customer::factory()->create();
        $customer->delete();

        $this->assertSoftDeleted('customers', ['id' => $customer->id]);
    }
}
