<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PressureReading extends Model
{
    protected $fillable = [ 'inventory_item_id', 'pressure', 'is_faulty' ];
    /** @use HasFactory<\Database\Factories\PressureReadingFactory> */
    use HasFactory;

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }
}
