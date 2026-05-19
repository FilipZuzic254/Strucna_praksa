<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientContact extends Model
{
    
    protected $fillable = ['client_id', 'first_name', 'last_name', 'email', 'position', 'phone'];

    /** @use HasFactory<\Database\Factories\ClientContactFactory> */
    use HasFactory;

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
