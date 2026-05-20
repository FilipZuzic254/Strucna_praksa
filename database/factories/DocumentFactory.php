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

        $files = [
            'Korisnicka_Podrska.docx',
            'Uvjeti_Koristenja_Demo.docx',
            'Uvod_u_Sustav.docx',
            'konfiguracija_napomena.txt',
            'podaci_primjer.txt',
            'procitaj_me.txt',
            'quick_start_guide.pdf',
            'sample_manual.pdf',
            'technical_specs.pdf',
            'verzija_povijest.txt'
        ];

        $filename = fake()->randomElement($files);
        
        $sourcePath = database_path('seeders/data/dummy_files/' . $filename);
        $uniqueFilename = fake()->unique()->numerify('####') . '_' . $filename;
        $storagePath = 'documents/' . $uniqueFilename;

        Storage::disk('public')->put(
            $storagePath,
            file_get_contents($sourcePath)
        );

        return [
            'file_name' => $uniqueFilename,
            'file_path' => $storagePath,
        ];
    }

    public function connectedProducts($max = 3){
        return $this->afterCreating(function ($document) use ($max) {

            $products = Product::inRandomOrder()->limit(rand(1, $max))->pluck('id');

            $document->products()->attach($products);
        });
    }
}
