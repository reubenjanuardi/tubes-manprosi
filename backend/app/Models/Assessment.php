<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        // Phase 2 fields
        'name',
        'description',
        'start_date',
        'end_date',
        'assessment_status',
        'created_by',
        'updated_by',
        'indicator_config',
        'settings',
        'total_participants',
        'completed_responses',
        'completion_rate',
        'is_template',
    ];

    protected $casts = [
        'assessment_date' => 'date',
        'completed_at' => 'datetime',
        'total_score' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'indicator_config' => 'array',
        'settings' => 'array',
        'completion_rate' => 'decimal:2',
        'is_template' => 'boolean',
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

    /**
     * Get the creator of this assessment (Phase 2)
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the last updater of this assessment (Phase 2)
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the indicators for this assessment (Phase 2)
     * Using the new assessment_indicator_pivot table as per PRD
     */
    public function indicators(): BelongsToMany
    {
        return $this->belongsToMany(Indicator::class, 'assessment_indicator_pivot')
            ->withPivot([
                'display_order_in_assessment',
                'is_active_in_assessment',
                'custom_weight',
                'created_at'
            ])
            ->orderBy('assessment_indicator_pivot.display_order_in_assessment');
    }

    /**
     * Check if assessment is active
     */
    public function isActive(): bool
    {
        return $this->assessment_status === 'active';
    }

    /**
     * Check if assessment is completed
     */
    public function isCompleted(): bool
    {
        return $this->assessment_status === 'completed';
    }

    /**
     * Check if assessment can be edited
     */
    public function canEdit(): bool
    {
        return in_array($this->assessment_status, ['draft', 'active']);
    }
}
