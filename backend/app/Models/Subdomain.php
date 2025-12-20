<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subdomain extends Model
{
    //
    protected $fillable = ['domain_id', 'name', 'weight'];

    /**
     * Relasi ke Domain (Subdomain dimiliki oleh satu Domain)
     */
    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    /**
     * Relasi ke Indicator (Satu Subdomain memiliki banyak Indikator)
     */
    public function indicators(): HasMany
    {
        return $this->hasMany(Indicator::class);
    }
}
