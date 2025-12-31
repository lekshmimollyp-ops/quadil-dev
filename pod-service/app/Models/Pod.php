<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pod extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'agent_id',
        'type',
        'value',
        'is_verified',
        'captured_at',
    ];

    protected $casts = [
        'order_id' => 'string',
        'agent_id' => 'string',
        'is_verified' => 'boolean',
        'captured_at' => 'datetime',
    ];
}
