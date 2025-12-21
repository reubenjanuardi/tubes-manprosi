<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SyncTracking extends Model
{
    protected $table = 'sync_tracking';
    
    public $timestamps = false; // We manage last_updated_at manually

    protected $fillable = [
        'component_name',
        'last_updated_at',
        'version_number',
        'updated_by',
        'change_description',
    ];

    protected $casts = [
        'last_updated_at' => 'datetime',
        'version_number' => 'integer',
    ];

    /**
     * Relationship to User who made the change
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Increment version for a component
     */
    public static function incrementVersion(string $component, ?int $userId = null, ?string $description = null): void
    {
        $tracking = self::where('component_name', $component)->first();
        
        if ($tracking) {
            $tracking->version_number++;
            $tracking->last_updated_at = now();
            $tracking->updated_by = $userId;
            $tracking->change_description = $description;
            $tracking->save();
        } else {
            // Create new tracking if not exists
            self::create([
                'component_name' => $component,
                'last_updated_at' => now(),
                'version_number' => 1,
                'updated_by' => $userId,
                'change_description' => $description ?? 'Initial version',
            ]);
        }
    }

    /**
     * Get version info for a component
     */
    public static function getVersion(string $component): array
    {
        $tracking = self::where('component_name', $component)->first();
        
        if (!$tracking) {
            return [
                'version' => 1,
                'last_updated_at' => now()->toIso8601String(),
            ];
        }

        return [
            'version' => $tracking->version_number,
            'last_updated_at' => $tracking->last_updated_at->toIso8601String(),
        ];
    }
}
