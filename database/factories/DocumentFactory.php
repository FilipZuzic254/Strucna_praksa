<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;

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

        $filename = fake()->unique()->word() . '.txt';

        Storage::disk('public/documents')->put($filename, fake()->text(2000));

        return [
            'file_name' => $filename,
            'file_path' => 'documents/' . $filename,
        ];
    }

    public function connectedProducts($max = 3){
        return $this->afterCreating(function ($document) use ($max) {

            $products = Product::inRandomOrder()->limit(rand(1, $max))->pluck('id');

            $document->products()->attach($products);
        });
    }
}
