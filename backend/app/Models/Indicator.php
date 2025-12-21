<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class Indicator extends Model
{
    protected $fillable = [
        'group_name',
        'indicator_text',
        'type',
        'scale_values',
        'scale_labels',
        'display_order',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'scale_values' => 'array',
        'scale_labels' => 'array',
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Scope to get only active indicators
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc');
    }

    /**
     * Relationship to User who created the indicator
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all assessments using this indicator (Phase 2)
     */
    public function assessments(): BelongsToMany
    {
        return $this->belongsToMany(Assessment::class, 'assessment_indicator_pivot')
            ->withPivot([
                'display_order_in_assessment',
                'is_active_in_assessment',
                'custom_weight',
                'created_at'
            ]);
    }

    /**
     * Update indicator version after any CRUD operation
     */
    protected static function booted()
    {
        static::created(function ($indicator) {
            self::incrementVersion($indicator, 'Created new indicator');
        });

        static::updated(function ($indicator) {
            self::incrementVersion($indicator, 'Updated indicator');
        });

        static::deleted(function ($indicator) {
            self::incrementVersion($indicator, 'Deleted indicator');
        });
    }

    /**
     * Increment indicator version using SyncTracking
     */
    private static function incrementVersion($indicator, string $description)
    {
        SyncTracking::incrementVersion(
            'indicators',
            $indicator->created_by ?? auth()->id(),
            $description
        );
    }

    /**
     * Get current indicator version from SyncTracking
     */
    public static function getCurrentVersion()
    {
        $tracking = SyncTracking::getVersion('indicators');
        return $tracking['version'];
    }

    /**
     * Get last updated timestamp from SyncTracking
     */
    public static function getLastUpdated()
    {
        $tracking = SyncTracking::getVersion('indicators');
        return $tracking['last_updated_at'];
    }
}
