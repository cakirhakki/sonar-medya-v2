<?php

namespace Database\Factories;

use App\Models\ServiceFaq;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFaqFactory extends Factory
{
    protected $model = ServiceFaq::class;

    public function definition(): array
    {
        return [
            'question'    => $this->faker->sentence(6),
            'answer'      => $this->faker->optional()->paragraph(),
            'sort_order'  => 0,
            'faqable_type'=> null, // testte set edilecek
            'faqable_id'  => null,
        ];
    }
}
