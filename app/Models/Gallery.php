<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = ['name', 'description'];

    /** @use HasFactory<\Database\Factories\GalleryFactory> */
    use HasFactory;

    public function images()
    {
        return $this->hasMany(GalleryImage::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
