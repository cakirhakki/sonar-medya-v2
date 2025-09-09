<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_dashboard_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
    }

    public function test_verified_customer_can_see_dashboard(): void
    {
        /** @var \App\Models\Customer $customer */
        $customer = Customer::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->actingAs($customer, 'customer');
        $this->get('/dashboard')->assertOk();
    }
}
