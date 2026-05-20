<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComponentItem extends Model
{
    protected $fillable = ['component_id', 'inventory_item_id', 'serial_number', 'status', 'installed_at', 'warranty_expires_at'];

    /** @use HasFactory<\Database\Factories\ComponentItemFactory> */
    use HasFactory;

    protected static function booted()
    {
        static::saving(function ($item) {
            if ($item->installed_at && !$item->warranty_expires_at) {
                $component = Component::find($item->component_id);

                if ($component) {
                    $item->warranty_expires_at =
                        $item->installed_at->copy()->addMonths($component->warranty_months);
                }
            }

            if ($item->isDirty('inventory_item_id') && $item->inventory_item_id) {
                static::where('inventory_item_id', $item->inventory_item_id)
                    ->where('component_id', $item->component_id)
                    ->where('id', '!=', $item->id)
                    ->where('status', 'installed')
                    ->update(['status' => 'faulty']);
            }
        });
    }

    public function component()
    {
        return $this->belongsTo(Component::class);
    }

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }
}
