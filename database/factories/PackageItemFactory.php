<?php

namespace Database\Factories;

use App\Models\PackageItem;
use App\Models\Service;
use App\Models\ServicePackage;
use Illuminate\Database\Eloquent\Factories\Factory;

class PackageItemFactory extends Factory
{
    protected $model = PackageItem::class;

    public function definition(): array
    {
        return [
            'service_package_id' => ServicePackage::factory(),
            'parent_item_id'     => null,
            'service_id'         => null, // snapshot ile custom da olabilir
            'type'               => 'custom',
            'name'               => $this->faker->words(3, true),
            'description'        => $this->faker->optional()->sentence(),
            'unit'               => 'adet',
            'qty'                => 1,
            'unit_price'         => 100,
            'discount_amount'    => 0,
            'tax_rate_percent'   => 20,
            'snapshot_json'      => null,
            'sort_order'         => 0,
        ];
    }

    public function serviceLinked(): static
    {
        return $this->state(function () {
            return [
                'type'       => 'service',
                'service_id' => Service::factory(),
            ];
        });
    }
}
