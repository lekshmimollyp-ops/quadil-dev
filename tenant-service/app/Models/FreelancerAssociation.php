<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreelancerAssociation extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'freelancer_user_id',
        'status',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
