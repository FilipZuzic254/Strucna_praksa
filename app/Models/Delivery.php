<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = ['client_id', 'delivered_at', 'reference', 'note'];

    /** @use HasFactory<\Database\Factories\DeliveryFactory> */
    use HasFactory;

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(DeliveryItem::class);
    }
}
