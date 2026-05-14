<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    protected $fillable = ['name', 'unit'];

    /** @use HasFactory<\Database\Factories\SensorFactory> */
    use HasFactory;

    public function productSensors()
    {
        return $this->hasMany(ProductSensor::class);
    }
}
