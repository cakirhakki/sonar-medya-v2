<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->sentence(3);

        return [
            'service_category_id' => ServiceCategory::factory(),
            'name'               => $name,
            'slug'               => Str::slug($name),
            'excerpt'            => $this->faker->optional()->sentence(),
            'description'        => $this->faker->optional()->paragraph(),
            'is_active'          => true,
            'is_featured'        => false,
            'display_order'      => 0,
            'unit'               => $this->faker->randomElement(['adet','saat','gün']),
            'base_price'         => 1000,
            'setup_fee'          => 0,
            'tax_rate_percent'   => 20,
            'duration_minutes'   => 60,
        ];
    }
}
