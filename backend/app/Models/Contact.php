<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'organization_name',
        'full_name',
        'email',
        'service',
        'message',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];
}
