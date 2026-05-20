<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Component;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ComponentItem>
 */
class ComponentItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'component_id' => Component::factory(),
            'inventory_item_id' => null,
            'serial_number' => fake()->unique()->bothify('SN-######'),
            'status' => 'in_stock',
            'installed_at' => null,
            'warranty_expires_at' => null,
        ];
    }
}
