<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpdCode extends Model
{
    protected $fillable = [
        'code', 
        'name'
    ];
    
    /** @use HasFactory<\Database\Factories\KpdCodeFactory> */
    use HasFactory;

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
