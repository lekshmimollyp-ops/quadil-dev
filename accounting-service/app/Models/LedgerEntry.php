<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LedgerEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'entry_type',
        'amount',
        'description',
        'reference_type',
        'reference_id',
    ];

    protected $casts = [
        'tenant_id' => 'string',
        'amount' => 'decimal:2',
    ];
}
