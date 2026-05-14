<?php

namespace Database\Factories;

use App\Models\KpdCode;
use App\Models\TaxExemption;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $taxRate = fake()->randomElement(['0%', '5%', '13%', '25%']);

        if ($taxRate === '0%') {
            $exemptionId = TaxExemption::inRandomOrder()->first()->id;
        } else {
            $exemptionId = null;
        }

        return [
            'name' => fake()->word(),
            'sku' => fake()->unique()->bothify('???####???'),
            'kpd_code_id' => KpdCode::inRandomOrder()->first()->id ?? null,
            'unit_price' => fake()->randomFloat(2, 10, 1000),
            'unit_of_measure' => fake()->randomElement(['piece', 'kg', 'l', 'month', 'day', 'hour']),
            'discount' => fake()->numberBetween(0, 100),
            'tax_rate' => $taxRate,
            'tax_exemption_id' => $exemptionId,
            'description' => fake()->optional()->sentence(),
            'warranty_months' => fake()->randomElement([6, 12, 24, 36, 48, 60]),
        ];
    }
}
