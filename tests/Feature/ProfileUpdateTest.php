<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_update_profile_and_phone_is_normalized(): void
    {
        /** @var \App\Models\Customer $customer */
        $customer = Customer::factory()->create([
            'email_verified_at' => now(),
            'phone'             => '+905551111111',
        ]);

        $this->actingAs($customer, 'customer');

        $resp = $this->patch(route('profile.update'), [
            'name'  => 'New Name',
            'email' => $customer->email, // unique'a takılmamak için aynı email
            'phone' => '05455899873',    // normalize sonucu: +905455899873
        ]);

        $resp->assertSessionHasNoErrors();

        $customer->refresh();
        $this->assertSame('New Name', $customer->name);
        $this->assertSame('+905455899873', $customer->phone);
    }
}
