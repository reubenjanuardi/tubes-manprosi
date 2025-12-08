<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'org_name',
        'org_type',
        'assessor_name',
        'assessor_position',
        'assessment_date',
        'total_score',
        'maturity_level',
        'status',
    ];

    protected $casts = [
        'assessment_date' => 'date',
        'total_score' => 'decimal:2',
    ];

    /**
     * Get the assessment responses for this assessment.
     */
    public function responses(): HasMany
    {
        return $this->hasMany(AssessmentResponse::class);
    }
}
