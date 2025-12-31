<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'url',
        'secret',
        'events',
        'is_active',
    ];

    protected $casts = [
        'tenant_id' => 'string',
        'events' => 'array',
        'is_active' => 'boolean',
    ];
}
