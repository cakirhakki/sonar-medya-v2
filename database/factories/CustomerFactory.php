<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'name'                => $this->faker->name(),
            'email'               => $this->faker->unique()->safeEmail(),
            'password'            => 'password', // modelde 'password' => 'hashed' ise otomatik hashlenir
            'phone'               => null,
            'birth_date'          => $this->faker->optional()->date(),
            'gender'              => $this->faker->optional()->randomElement(['male','female','other']),
            'address'             => $this->faker->optional()->address(),
            'loyalty_points'      => 0,
            'receive_newsletters' => false,
            'email_verified_at'   => now(),
            'avatar_path'         => null, // varsa migration'ında
        ];
    }
}
