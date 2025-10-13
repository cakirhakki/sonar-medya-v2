<?php

namespace Database\Factories;

use App\Models\ServicePackage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServicePackageFactory extends Factory
{
    protected $model = ServicePackage::class;

    public function definition(): array
    {
        return [
            'name'           => $this->faker->unique()->sentence(3),
            'code'           => null, // model boot doldurur
            'description'    => $this->faker->optional()->sentence(),
            'currency'       => 'TRY',
            'currency_rate'  => null,
            'override_price' => null,
            'status'         => ServicePackage::STATUS_DRAFT,
            'sent_at'        => null,
            'accepted_at'    => null,
            'locked_at'      => null,
            'expires_at'     => null,
        ];
    }
}
