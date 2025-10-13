<?php

namespace Database\Factories;

use App\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ServiceCategoryFactory extends Factory
{
    protected $model = ServiceCategory::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);

        return [
            'name'          => $name,
            'slug'          => Str::slug($name),
            'description'   => $this->faker->optional()->sentence(),
            'is_active'     => true,
            'display_order' => $this->faker->numberBetween(0, 50),
        ];
    }
}
