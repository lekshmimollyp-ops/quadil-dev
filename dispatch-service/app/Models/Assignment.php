<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'driver_id',
        'status',
        'remarks',
    ];

    protected $casts = [
        'order_id' => 'string',
        'driver_id' => 'string',
    ];
}
