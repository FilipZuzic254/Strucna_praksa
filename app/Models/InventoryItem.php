<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    protected $fillable = ['product_id', 'serial_number', 'status', 'purchased_at', 'installed_at', 'warranty_expires_at', 'notes'];

    protected $casts = [
        'purchased_at' => 'date',
        'installed_at' => 'date',
        'warranty_expires_at' => 'date',
    ];
        
    /** @use HasFactory<\Database\Factories\InventoryItemFactory> */
    use HasFactory;

    protected static function booted()
    {
        static::saving(function ($item) {
            if ($item->installed_at && !$item->warranty_expires_at) {
                $product = Product::find($item->product_id);

                if ($product) {
                    $item->warranty_expires_at =
                        $item->installed_at->copy()->addMonths($product->warranty_months);
                }
            }
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function serviceLogs()
    {
        return $this->hasMany(ServiceLog::class);
    }

    public function deliveryItem()
    {
        return $this->hasMany(DeliveryItem::class);
    } 

    public function sensorReadings()
    {
        return $this->hasMany(SensorReading::class);
    }
}
