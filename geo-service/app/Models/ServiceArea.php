<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ServiceArea extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'city_id',
        'name',
        'geo_polygon',
        'center_lat',
        'center_lng',
        'radius_km',
        'is_active',
    ];

    protected $casts = [
        'geo_polygon' => 'array',
        'center_lat' => 'decimal:8',
        'center_lng' => 'decimal:8',
        'radius_km' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
