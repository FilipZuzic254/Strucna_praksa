<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'sku', 'manufacturer', 'warranty_months'];

    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function inStockItems()
    {
        return $this->hasMany(InventoryItem::class)
            ->where('status', 'in_stock');
    }
    
    public function deliveredItems()
    {
        return $this->hasMany(InventoryItem::class)
            ->whereNot('status', 'in_stock');
    }
}
