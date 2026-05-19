<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryItem extends Model
{
    protected $fillable = ['delivery_id', 'inventory_item_id'];

    /** @use HasFactory<\Database\Factories\DeliveryItemFactory> */
    use HasFactory;

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }
}
