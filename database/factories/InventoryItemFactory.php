<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\ServiceLog;
use App\Models\Delivery;
use App\Models\DeliveryItem;
use App\Models\InventoryItem;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InventoryItem>
 */
class InventoryItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        return [
            'serial_number' => fake()->unique()->bothify('SN-*********'),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function inStock()
    {
        return $this->state(function () {
            return [
                'status' => 'in_stock',
                'purchased_at' => null,
                'warranty_expires_at' => null,
            ];
        });
    }

    public function delivered()
    {
        return $this->state(function () {
            $date = fake()->dateTimeBetween('-1 year', 'now');

            return [
                'status' => 'delivered',
                'purchased_at' => $date,
            ];
        })->has(
            DeliveryItem::factory()->state(function (array $attributes, InventoryItem $item) {
                $delivery = Delivery::inRandomOrder()->first();

                return [
                    'delivery_id' => $delivery->id,
                ];
            }),
            'deliveryItem'
        );
    }

    public function faulty()
    {
        return $this->state(function () {
            $date = fake()->dateTimeBetween('-1 year', 'now');

            return [
                'status' => 'faulty',
                'purchased_at' => $date,
            ];
        })->has(
            ServiceLog::factory()->state(function (array $attributes, InventoryItem $item) {

                $endDate = $item->warranty_expires_at && $item->warranty_expires_at < now() ? $item->warranty_expires_at : now();

                return [
                    'performed_at' => fake()->dateTimeBetween($item->purchased_at, $endDate),
                    'action' => 'reported_fault',
                    'description' => 'Item reported as faulty.',
                ];
            }),
            'serviceLogs'
        )->has(
            DeliveryItem::factory()->state(function (array $attributes, InventoryItem $item) {
                $delivery = Delivery::inRandomOrder()->first();

                return [
                    'delivery_id' => $delivery->id,
                ];
            }),
            'deliveryItem'
        );
    }

    public function replaced()
    {
        return $this->state(function () {
            $date = fake()->dateTimeBetween('-1 year', 'now');

            return [
                'status' => 'replaced',
                'purchased_at' => $date,
            ];
        })->has(
            ServiceLog::factory()->state(function (array $attributes, InventoryItem $item) {

                $endDate = $item->warranty_expires_at && $item->warranty_expires_at < now() ? $item->warranty_expires_at : now();

                return [
                    'performed_at' => fake()->dateTimeBetween($item->purchased_at, $endDate),
                    'action' => 'replacement',
                    'description' => 'Item marked as replaced due to fault.',
                ];
            }),
            'serviceLogs'
        )->has(
            DeliveryItem::factory()->state(function (array $attributes, InventoryItem $item) {
                $delivery = Delivery::inRandomOrder()->first();

                return [
                    'delivery_id' => $delivery->id,
                ];
            }),
            'deliveryItem'
        );
    }


}
