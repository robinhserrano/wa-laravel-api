<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LandingPrice>
 */
class LandingPriceFactory extends Factory
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
            'name' => $faker->word,
            'internal_reference' => $faker->word,
            'product_category' => $faker->word,
            'installation_service' => $faker->randomFloat(2, 0, 100),
            'supply_only' => $faker->randomFloat(2, 0, 100),
        ];
    }
}
