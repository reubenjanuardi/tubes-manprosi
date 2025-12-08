<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentResponse extends Model
{
    protected $fillable = [
        'assessment_id',
        'indicator_id',
        'score',
        'evidence_text',
        'document_path',
    ];

    protected $casts = [
        'score' => 'integer',
        'indicator_id' => 'integer',
    ];

    /**
     * Get the assessment that owns this response.
     */
    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }
}
