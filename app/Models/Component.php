<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    protected $fillable = ['name', 'description', 'warranty_months'];

    /** @use HasFactory<\Database\Factories\ComponentFactory> */
    use HasFactory;

    public function items()
    {
        return $this->hasMany(ComponentItem::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_components');
    }
}
