<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    protected $fillable = [
        'name',
        'type',
        'address',
        'phone',
        'email',
        'website',
        'description',
    ];

    /**
     * Get assessments for this organization
     */
    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }
}
