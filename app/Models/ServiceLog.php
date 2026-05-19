<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceLog extends Model
{
    protected $fillable = ['inventory_item_id', 'performed_at', 'action', 'description'];
    /** @use HasFactory<\Database\Factories\ServiceLogFactory> */
    use HasFactory;

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }
}
