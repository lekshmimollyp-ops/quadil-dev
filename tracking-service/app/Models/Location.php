<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'order_id',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'agent_id' => 'string',
        'order_id' => 'string',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];
}
