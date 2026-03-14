<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = ['filename'];

    /** @use HasFactory<\Database\Factories\DocumentFactory> */
    use HasFactory;

    public function products()
    {
        return $this->hasMany(ProductDocument::class);
    }

}
