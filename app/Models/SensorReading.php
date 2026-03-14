<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\SensorReadingObserver;

#[ObservedBy([SensorReadingObserver::class])]

class SensorReading extends Model
{
    protected $fillable = ['inventory_item_id', 'temperature', 'pressure'];

    /** @use HasFactory<\Database\Factories\SensorReadingFactory> */
    use HasFactory;

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }
}
