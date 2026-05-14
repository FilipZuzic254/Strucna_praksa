<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSensor extends Model
{
    protected $fillable = ['product_id', 'sensor_id', 'min_value', 'max_value', 'note'];

    /** @use HasFactory<\Database\Factories\ProductSensorFactory> */
    use HasFactory;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function sensor()
    {
        return $this->belongsTo(Sensor::class);
    }

    public function sensorReadings()
    {
        return $this->hasMany(SensorReading::class);
    }
}
