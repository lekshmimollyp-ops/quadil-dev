<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PricingRule extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'tenant_id',
        'base_fare',
        'per_km_rate',
        'per_kg_rate',
        'is_active',
    ];

    protected $casts = [
        'base_fare' => 'decimal:2',
        'per_km_rate' => 'decimal:2',
        'per_kg_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
