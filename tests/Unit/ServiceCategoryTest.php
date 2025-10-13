<?php

namespace Tests\Unit;

use App\Models\ServiceCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
/** @group services */
class ServiceCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_slug_otomatik_ve_benzersiz_olur(): void
    {
        // İlk kayıt: slug sabit
        $c1 = ServiceCategory::factory()->create([
            'name' => 'Web Tasarım',
            'slug' => 'web-tasarim',
        ]);

        // İkinci kayıt: slug boş -> model otomatik üretmeli ve benzersiz olmalı
        $c2 = ServiceCategory::factory()->create([
            'name' => 'Web Tasarım',
            'slug' => null,
        ]);

        $this->assertNotNull($c2->slug);
        $this->assertNotEquals($c1->slug, $c2->slug);
    }

    public function test_scopes_active_ve_ordered(): void
    {
        // Sıralamayı deterministik hale getirmek için display_order'ı açıkça veriyoruz
        $cat1 = ServiceCategory::create([
            'name'          => 'A',
            'is_active'     => true,
            'display_order' => 1,
        ]);

        $cat2 = ServiceCategory::create([
            'name'          => 'B',
            'is_active'     => true,
            'display_order' => 2,
        ]);

        $cat3 = ServiceCategory::create([
            'name'          => 'C',
            'is_active'     => false,
            'display_order' => 3,
        ]);

        // active() yalnızca aktif olanları döndürmeli
        $active = ServiceCategory::query()->active()->get();
        $this->assertCount(2, $active);
        $this->assertTrue($active->contains('id', $cat1->id));
        $this->assertTrue($active->contains('id', $cat2->id));
        $this->assertFalse($active->contains('id', $cat3->id));

        // ordered() display_order'a göre artan sıralama yapmalı
        $ordered = ServiceCategory::query()->ordered()->pluck('display_order')->toArray();
        $this->assertEquals([1, 2, 3], $ordered);
    }
}
