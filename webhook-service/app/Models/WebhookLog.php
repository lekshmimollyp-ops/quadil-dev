<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'event',
        'request_payload',
        'response_code',
        'status',
    ];

    protected $casts = [
        'tenant_id' => 'string',
        'request_payload' => 'array',
    ];
}
