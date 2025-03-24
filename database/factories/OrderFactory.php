<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'requested_on' => $this->faker->dateTime(),
            'remarks' => $this->faker->sentence(),
            'signature' => $this->faker->text(),
            'signed_at' => $this->faker->dateTime(),
        ];
    }
}
