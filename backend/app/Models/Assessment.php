<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assessment extends Model
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'organization_id',
        'org_name',
        'org_type',
        'assessor_name',
        'assessor_position',
        'assessment_date',
        'total_score',
        'maturity_level',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'assessment_date' => 'date',
        'completed_at' => 'datetime',
        'total_score' => 'decimal:2',
    ];

    /**
     * Get the assessment responses for this assessment.
     */
    public function responses(): HasMany
    {
        return $this->hasMany(AssessmentResponse::class);
    }

    /**
     * Get the user that owns this assessment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the organization for this assessment
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
