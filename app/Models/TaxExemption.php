<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxExemption extends Model
{
    protected $fillable = [
        'code', 
        'description'
    ];

    /** @use HasFactory<\Database\Factories\TaxExemptionFactory> */
    use HasFactory;

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
