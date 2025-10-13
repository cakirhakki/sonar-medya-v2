<?php

namespace Tests\Unit;

use App\Models\PackageItem;
use App\Models\ServicePackage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
/** @group services */
class ServicePackageTotalsTest extends TestCase
{
    use RefreshDatabase;

    public function test_code_otomatik_uretilir(): void
    {
        $pkg = ServicePackage::factory()->create(['code' => null]);
        $this->assertNotNull($pkg->code);
        $this->assertStringStartsWith('PKG-', $pkg->code);
    }

    public function test_totals_from_items(): void
    {
        $pkg = ServicePackage::factory()->create();

        // 1. satır: 2 * 100 - 0 = 200; KDV %20 → 40; Toplam 240
        PackageItem::factory()->create([
            'service_package_id' => $pkg->id,
            'qty'                => 2,
            'unit_price'         => 100,
            'discount_amount'    => 0,
            'tax_rate_percent'   => 20,
        ]);

        // 2. satır: 1 * 50 - 10 = 40; KDV %8 → 3.2; Toplam 43.2
        PackageItem::factory()->create([
            'service_package_id' => $pkg->id,
            'qty'                => 1,
            'unit_price'         => 50,
            'discount_amount'    => 10,
            'tax_rate_percent'   => 8,
        ]);

        // computedSubtotal = 200 + 40 = 240
        $this->assertEquals(240.00, $pkg->computedSubtotal());

        // computedTax = 40 + 3.2 = 43.2
        $this->assertEquals(43.20, $pkg->computedTaxTotal());

        // computedTotal = 240 + 43.2 = 283.2
        $this->assertEquals(283.20, $pkg->computedTotal());

        // override_price yokken finalTotal = 283.2
        $this->assertEquals(283.20, $pkg->finalTotal());

        // override ver → finalTotal override’ı kullanır
        $pkg->update(['override_price' => 300]);
        $this->assertEquals(300.00, $pkg->finalTotal());
    }
}
