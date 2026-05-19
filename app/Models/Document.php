<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = ['file_name', 'file_path'];

    /** @use HasFactory<\Database\Factories\DocumentFactory> */
    use HasFactory;

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_documents');
    }

}
