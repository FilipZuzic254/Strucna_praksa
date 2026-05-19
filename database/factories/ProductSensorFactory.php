<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Sensor;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductSensor>
 */
class ProductSensorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'sensor_id' => Sensor::inRandomOrder()->first()->id ?? Sensor::factory()->create()->id,
            'min_value' => fake()->numberBetween(0, 50),
            'max_value' => fake()->numberBetween(51, 100),
            'note' => fake()->optional()->sentence(),
        ];
    }
}
