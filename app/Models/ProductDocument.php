<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDocument extends Model
{
    protected $fillable = ['product_id', 'document_id'];

    /** @use HasFactory<\Database\Factories\ProductDocumentFactory> */
    use HasFactory;

    /*
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
    */

}
