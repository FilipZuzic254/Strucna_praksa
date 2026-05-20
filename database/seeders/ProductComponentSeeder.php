<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Component;
use App\Models\ProductComponent;

class ProductComponentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::get()
            ->each(function ($product) {
                $components = Component::inRandomOrder()->limit(rand(1, 3))->get();

                foreach ($components as $component) {
                    ProductComponent::create([
                        'product_id' => $product->id,
                        'component_id' => $component->id,
                    ]);
                }
            });
    }
}
