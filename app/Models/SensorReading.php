<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorReading extends Model
{
    protected $fillable = ['inventory_item_id', 'product_sensor_id', 'value'];

    /** @use HasFactory<\Database\Factories\SensorReadingFactory> */
    use HasFactory;

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function productSensor()
    {
        return $this->belongsTo(ProductSensor::class);
    }
}
