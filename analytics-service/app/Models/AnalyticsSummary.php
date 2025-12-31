<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalyticsSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'total_orders',
        'total_revenue',
        'completed_orders',
        'cancelled_orders',
        'last_order_at',
    ];

    protected $casts = [
        'tenant_id' => 'string',
        'total_revenue' => 'decimal:2',
        'last_order_at' => 'datetime',
    ];
}
