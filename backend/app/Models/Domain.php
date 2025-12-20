<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Domain extends Model
{
    //
    protected $fillable = [
        'name',
        'weight',
        'color'
    ];

    /**
     * Relasi ke Subdomain (Satu Domain memiliki banyak Subdomain)
     */
    public function subdomains(): HasMany
    {
        return $this->hasMany(Subdomain::class);
    }
}
