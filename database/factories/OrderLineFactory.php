<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

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
        $faker = Faker::create();
        return [
            'sales_order_id' => $faker->numberBetween(1, 100), // Assuming some sales orders exist
            'product' => $faker->word,
            'description' => $faker->optional()->paragraph,
            'quantity' => $faker->numberBetween(1, 10),
            'unit_price' => $faker->randomFloat(2, 0, 100),
            'tax_excl' => $faker->randomFloat(2, 0, 100),
            'disc' => $faker->randomFloat(2, 0, 10),
            'taxes' => $faker->optional()->word,
            'delivered' => $faker->boolean,
            'invoiced' => $faker->boolean,
        ];
    }
}
