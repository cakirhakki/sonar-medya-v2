<?php

namespace Tests\Unit;

use App\Models\PackageItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
/** @group services */
class PackageItemTotalsTest extends TestCase
{
    use RefreshDatabase;

    public function test_line_calculations(): void
    {
        /** @var PackageItem $item */
        $item = PackageItem::factory()->create([
            'qty'              => 2.5,
            'unit_price'       => 100,
            'discount_amount'  => 20,
            'tax_rate_percent' => 20,
        ]);

        // Ara toplam: (2.5 * 100) - 20 = 230
        $this->assertEquals(230.00, $item->lineSubtotal());

        // Vergi: 230 * 0.20 = 46
        $this->assertEquals(46.00, $item->lineTaxAmount());

        // Toplam: 230 + 46 = 276
        $this->assertEquals(276.00, $item->lineTotal());
    }
}
