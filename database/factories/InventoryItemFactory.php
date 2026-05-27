<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\ServiceLog;
use App\Models\Delivery;
use App\Models\DeliveryItem;
use App\Models\InventoryItem;
use App\Models\ProductComponent;
use App\Models\ComponentItem;
use App\Models\Document;
use Illuminate\Support\Carbon;

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
            'product_id' => Product::factory(),
            'sku' => fake()->unique()->bothify('??-*********'),
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
            $purchace_date = Carbon::instance(
                fake()->dateTimeBetween('-1 year', 'now')
            );

            $installation_date = fake()->dateTimeBetween(
                $purchace_date, 
                $purchace_date->copy()->addMonths(2)
            );

            return [
                'status' => 'delivered',
                'purchased_at' => $purchace_date,
                'installed_at' => $installation_date,
            ];
        })
        ->afterCreating(function (InventoryItem $item) {
            $productComponents = ProductComponent::where('product_id', $item->product_id)->get();

            foreach ($productComponents as $component) {
                $itemComponent = ComponentItem::where('component_id', $component->component_id)
                    ->where('status', 'in_stock')
                    ->first();
                
                if ($itemComponent) {
                    $itemComponent->update([
                        'inventory_item_id' => $item->id,
                        'status' => 'installed',
                        'installed_at' => $item->installed_at,
                    ]);
                }
            }
        })
        ->has(
            DeliveryItem::factory()->state(function (array $attributes, InventoryItem $item) {
                $delivery = Delivery::inRandomOrder()->first();

                return [
                    'delivery_id' => $delivery->id,
                ];
            }),
            'deliveryItem'
        )
        ->has(
            Document::factory()->state(function () {
                return [
                    'type' => 'financial',
                ];
            }),
            'documents'
        );
    }



    public function faulty()
    {
        return $this->state(function () {
            $purchace_date = Carbon::instance(
                fake()->dateTimeBetween('-1 year', 'now')
            );

            $installation_date = fake()->dateTimeBetween(
                $purchace_date, 
                $purchace_date->copy()->addMonths(2)
            );

            return [
                'status' => 'faulty',
                'purchased_at' => $purchace_date,
                'installed_at' => $installation_date,
            ];
        })
        ->afterCreating(function (InventoryItem $item) {
            $productComponents = ProductComponent::where('product_id', $item->product_id)->get();

            foreach ($productComponents as $component) {
                $itemComponent = ComponentItem::where('component_id', $component->component_id)
                    ->where('status', 'in_stock')
                    ->first();
                
                if ($itemComponent) {
                    $itemComponent->update([
                        'inventory_item_id' => $item->id,
                        'status' => 'installed',
                        'installed_at' => $item->installed_at,
                    ]);
                }
            }
        })
        ->has(
            ServiceLog::factory()->state(function (array $attributes, InventoryItem $item) {

                $endDate = $item->warranty_expires_at && $item->warranty_expires_at < now() ? $item->warranty_expires_at : now();

                return [
                    'performed_at' => fake()->dateTimeBetween($item->purchased_at, $endDate),
                    'action' => 'reported_fault',
                    'description' => 'Item reported as faulty.',
                ];
            }),
            'serviceLogs'
        )
        ->has(
            DeliveryItem::factory()->state(function (array $attributes, InventoryItem $item) {
                $delivery = Delivery::inRandomOrder()->first();

                return [
                    'delivery_id' => $delivery->id,
                ];
            }),
            'deliveryItem'
        )
        ->has(
            Document::factory()->state(function () {
                return [
                    'type' => 'financial',
                ];
            }),
            'documents'
        );
    }



    public function replaced()
    {
        return $this->state(function () {
            $purchace_date = Carbon::instance(
                fake()->dateTimeBetween('-1 year', 'now')
            );

            $installation_date = fake()->dateTimeBetween(
                $purchace_date, 
                $purchace_date->copy()->addMonths(2)
            );

            return [
                'status' => 'replaced',
                'purchased_at' => $purchace_date,
                'installed_at' => $installation_date,
            ];
        })
        ->afterCreating(function (InventoryItem $item) {
            $productComponents = ProductComponent::where('product_id', $item->product_id)->get();

            foreach ($productComponents as $component) {
                $itemComponent = ComponentItem::where('component_id', $component->component_id)
                    ->where('status', 'in_stock')
                    ->first();
                
                if ($itemComponent) {
                    $itemComponent->update([
                        'inventory_item_id' => $item->id,
                        'status' => 'installed',
                        'installed_at' => $item->installed_at,
                    ]);
                }
            }
        })
        ->has(
            ServiceLog::factory()->state(function (array $attributes, InventoryItem $item) {

                $endDate = $item->warranty_expires_at && $item->warranty_expires_at < now() ? $item->warranty_expires_at : now();

                return [
                    'performed_at' => fake()->dateTimeBetween($item->purchased_at, $endDate),
                    'action' => 'replacement',
                    'description' => 'Item marked as replaced due to fault.',
                ];
            }),
            'serviceLogs'
        )
        ->has(
            DeliveryItem::factory()->state(function (array $attributes, InventoryItem $item) {
                $delivery = Delivery::inRandomOrder()->first();

                return [
                    'delivery_id' => $delivery->id,
                ];
            }),
            'deliveryItem'
        )
        ->has(
            Document::factory()->state(function () {
                return [
                    'type' => 'financial',
                ];
            }),
            'documents'
        );
    }


}
