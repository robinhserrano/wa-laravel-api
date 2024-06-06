<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderLine>
 */
class OrderLineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sales_order_id' => fake()->numberBetween(1, 100), // Assuming some sales orders exist
            'product' => fake()->word,
            'description' => fake()->optional()->paragraph,
            'quantity' => fake()->numberBetween(1, 10),
            'unit_price' => fake()->randomFloat(2, 0, 100),
            'tax_excl' => fake()->randomFloat(2, 0, 100),
            'disc' => fake()->randomFloat(2, 0, 10),
            'taxes' => fake()->optional()->word,
            'delivered' => fake()->boolean,
            'invoiced' => fake()->boolean,
        ];
    }
}
