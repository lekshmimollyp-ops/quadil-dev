<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class City extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'id',
        'state_id',
        'name',
        'state',
        'country_code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function serviceAreas()
    {
        return $this->hasMany(ServiceArea::class);
    }
}
