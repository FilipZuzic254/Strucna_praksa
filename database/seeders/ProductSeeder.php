<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\InventoryItem;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory()->count(20)->create()
            ->each(function ($product) {

                InventoryItem::factory()->count(rand(5, 10))->for($product)->inStock()->create();
                InventoryItem::factory()->count(rand(5, 10))->for($product)->delivered()->create();
                InventoryItem::factory()->count(rand(1, 5))->for($product)->replaced()->create();
                InventoryItem::factory()->count(rand(1, 5))->for($product)->faulty()->create();
            });
    }
}
