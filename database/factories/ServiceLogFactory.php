<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\InventoryItem;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceLog>
 */
class ServiceLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'inventory_item_id' => InventoryItem::factory(),
            'performed_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'action' => fake()->randomElement(['reported_fault', 'replacement', 'maintenance']),
            'description' => fake()->optional()->sentence(),
        ];
    }
}
