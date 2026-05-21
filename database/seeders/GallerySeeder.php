<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gallery;
use App\Models\GalleryImage;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $baseDir = storage_path('app/public/gallery/');
        
        foreach (glob($baseDir . '*', GLOB_ONLYDIR) as $galleryDir) {

            $galleryName = basename($galleryDir);
            
            $gallery = Gallery::create([
                'name' => $galleryName,
                'description' => 'Description for ' . $galleryName,
            ]);
            
            $sortOrder = 1;
            foreach (glob($galleryDir . '/*') as $imagePath) {
                GalleryImage::create([
                    'gallery_id' => $gallery->id,
                    'title' => basename($imagePath),
                    'image_path' => 'gallery/' . $galleryName . '/' . basename($imagePath),
                    'sort_order' => $sortOrder++,
                ]);
            }
        }
    }
}
