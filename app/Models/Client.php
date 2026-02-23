<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{

    protected $fillable = ['name', 'type', 'oib', 'email', 'phone', 'address'];

    /** @use HasFactory<\Database\Factories\ClientFactory> */
    use HasFactory;

    public function contacts()
    {
        return $this->hasMany(ClientContact::class);
    }
}
