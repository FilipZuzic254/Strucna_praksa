<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\ProductDocument;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $filename = fake()->unique()->word() . '.pdf';

        Storage::disk('public')->put($filename, fake()->text(2000));

        return [
            'filename' => $filename,
        ];
    }

    public function connectedProducts($max = 3){
        return $this->has(
            ProductDocument::factory()
            ->state(function () {
                $product = Product::inRandomOrder()->first();

                return [
                    'product_id' => $product->id,
                ];
            })
            ->count(rand(1, $max)),
            'products'
        );
    }
}
