<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductComponent extends Model
{
    protected $fillable = ['product_id', 'component_id'];

    /** @use HasFactory<\Database\Factories\ProductComponentFactory> */
    use HasFactory;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function component()
    {
        return $this->belongsTo(Component::class);
    }
}
