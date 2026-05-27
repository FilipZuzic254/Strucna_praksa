<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = ['file_name', 'file_path', 'type', 'inventory_item_id'];

    /** @use HasFactory<\Database\Factories\DocumentFactory> */
    use HasFactory;

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_documents');
    }

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }
}
