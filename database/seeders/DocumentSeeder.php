<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Storage::disk('public')->deleteDirectory('documents');
        Storage::disk('public')->makeDirectory('documents');
        
        Document::factory()
            ->connectedProducts(4)
            ->count(10)
            ->create();
    }
}
