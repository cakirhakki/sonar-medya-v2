<?php

namespace Tests\Unit;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
/** @group services */
class ServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_relations(): void
    {
        $cat = ServiceCategory::factory()->create();
        $srv = Service::factory()->create(['service_category_id' => $cat->id]);

        $this->assertEquals($cat->id, $srv->category->id);
    }

    public function test_slug_otomatik_ve_benzersiz(): void
    {
        $s1 = Service::factory()->create(['name' => 'Kurumsal Site', 'slug' => 'kurumsal-site']);
        $s2 = Service::factory()->create(['name' => 'Kurumsal Site', 'slug' => null]);

        $this->assertNotNull($s2->slug);
        $this->assertNotEquals($s1->slug, $s2->slug);
    }

    public function test_scopes(): void
    {
        Service::factory()->create(['is_active' => true, 'is_featured' => true, 'display_order' => 1]);
        Service::factory()->create(['is_active' => true, 'is_featured' => false, 'display_order' => 2]);
        Service::factory()->create(['is_active' => false, 'is_featured' => true, 'display_order' => 3]);

        $this->assertCount(2, Service::active()->get());
        $this->assertCount(2, Service::featured()->get());
        $this->assertEquals([1,2,3], Service::ordered()->pluck('display_order')->toArray());
    }
}
