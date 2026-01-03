<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Tenant extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'id',
        'type',
        'name',
        'domain',
        'settings',
        'parent_tenant_id',
        'is_active',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    public function freelancers()
    {
        return $this->hasMany(FreelancerAssociation::class);
    }

    public function children()
    {
        return $this->hasMany(Tenant::class, 'parent_tenant_id')->with('children');
    }
}
