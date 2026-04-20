<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemperatureReading extends Model
{
    protected $fillable = [ 'inventory_item_id', 'temperature', 'is_faulty' ];
    /** @use HasFactory<\Database\Factories\TemperatureReadingFactory> */
    use HasFactory;

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }
}
