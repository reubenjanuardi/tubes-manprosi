<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Indicator extends Model
{
    //
    protected $fillable = ['subdomain_id', 'code', 'name'];

    /**
     * Relasi ke Subdomain (Indikator dimiliki oleh satu Subdomain)
     */
    public function subdomain(): BelongsTo
    {
        return $this->belongsTo(Subdomain::class);
    }
}
