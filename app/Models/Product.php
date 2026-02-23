<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'sku', 'manufacturer', 'warranty_months'];

    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;
}
