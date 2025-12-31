<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'vehicle_type',
        'plate_number',
        'is_verified',
    ];

    protected $casts = [
        'agent_id' => 'string',
        'is_verified' => 'boolean',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}
