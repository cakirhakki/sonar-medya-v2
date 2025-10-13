<?php

namespace Tests\Unit;

use App\Models\Service;
use App\Models\ServiceFaq;
use App\Models\ServicePackage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
/** @group services */
class ServiceFaqPolymorphTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_ile_morphMany_calisir(): void
    {
        $service = Service::factory()->create();
        $faq = ServiceFaq::factory()->create([
            'faqable_type' => Service::class,
            'faqable_id'   => $service->id,
        ]);

        $this->assertEquals($service->id, $faq->faqable->id);
        $this->assertEquals(1, $service->faqs()->count());
    }

    public function test_package_ile_morphMany_calisir(): void
    {
        $package = ServicePackage::factory()->create();
        $faq = ServiceFaq::factory()->create([
            'faqable_type' => ServicePackage::class,
            'faqable_id'   => $package->id,
        ]);

        $this->assertEquals($package->id, $faq->faqable->id);
        $this->assertEquals(1, $package->faqs()->count());
    }

    public function test_ordered_scope(): void
    {
        $service = Service::factory()->create();
        ServiceFaq::factory()->create(['faqable_type' => Service::class, 'faqable_id' => $service->id, 'sort_order' => 2]);
        ServiceFaq::factory()->create(['faqable_type' => Service::class, 'faqable_id' => $service->id, 'sort_order' => 1]);

        $orders = $service->faqs()->ordered()->pluck('sort_order')->toArray();
        $this->assertEquals([1,2], $orders);
    }
}
