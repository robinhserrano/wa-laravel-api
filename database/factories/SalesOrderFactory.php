<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SalesOrder>
 */
class SalesOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amount_to_invoice' => fake()->randomFloat(2, 0, 10000),
            'amount_total' => fake()->randomFloat(2, 0, 10000),
            'amount_untaxed' => fake()->randomFloat(2, 0, 10000),
            'create_date' => now(),
            'delivery_status' => fake()->randomElement(['pending', 'partial', 'full']),
            'internal_note_display' => fake()->paragraph,
            'name' => fake()->unique()->uuid, // Ensure unique identifier
            'partner_id_contact_address' => fake()->address,
            'partner_id_display_name' => fake()->name,
            'partner_id_phone' => fake()->phoneNumber,
            'state' => fake()->randomElement(['draft', 'sale', 'void']),
            'x_studio_commission_paid' => fake()->boolean,
            'x_studio_invoice_payment_status' => fake()->randomElement(['pending', 'paid', 'failed']),
            'x_studio_payment_type' => fake()->word,
            'x_studio_referrer_processed' => fake()->boolean,
            'x_studio_sales_rep_1' => fake()->name,
            'x_studio_sales_source' => fake()->word,
        ];
    }
}
