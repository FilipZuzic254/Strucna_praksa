<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    protected $fillable = ['gallery_id', 'image_path', 'sort_order', 'title'];

    /** @use HasFactory<\Database\Factories\GalleryImageFactory> */
    use HasFactory;

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }
}
