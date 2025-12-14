<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentProgress extends Model
{
    protected $table = 'assessment_progress';

    protected $fillable = [
        'user_id',
        'assessment_id',
        'progress_data',
        'saved_at',
    ];

    protected $casts = [
        'progress_data' => 'array',
        'saved_at' => 'datetime',
    ];

    /**
     * Get the user that owns this progress
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the assessment (if created)
     */
    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }
}
