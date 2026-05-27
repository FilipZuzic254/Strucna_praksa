<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'serial_number',
        'kpd_code_id',
        'unit_price',
        'unit_of_measure',
        'discount',
        'tax_rate',
        'tax_exemption_id',
        'description',
        'warranty_months',
        'gallery_id',
        'header_image_id',
    ];

    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function inStockItems()
    {
        return $this->hasMany(InventoryItem::class)
            ->where('status', 'in_stock');
    }
    
    public function deliveredItems()
    {
        return $this->hasMany(InventoryItem::class)
            ->whereNot('status', 'in_stock');
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class, 'product_documents');
    }

    public function kpdCode()
    {
        return $this->belongsTo(KpdCode::class);
    }

    public function taxExemption()
    {
        return $this->belongsTo(TaxExemption::class);
    }

    public function productSensors()
    {
        return $this->hasMany(ProductSensor::class);
    }

    public function components()
    {
        return $this->belongsToMany(Component::class, 'product_components');
    }

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }

    public function headerImage()
    {
        return $this->belongsTo(GalleryImage::class, 'header_image_id');
    }
}
