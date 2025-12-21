<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'format',
        'created_by',
        'parameters',
        'filters',
        'is_scheduled',
        'schedule_frequency',
        'schedule_config',
        'last_generated_at',
        'next_generation_at',
        'file_path',
        'file_size',
        'status',
        'error_message',
        'email_recipients',
    ];

    protected $casts = [
        'parameters' => 'array',
        'filters' => 'array',
        'schedule_config' => 'array',
        'email_recipients' => 'array',
        'is_scheduled' => 'boolean',
        'last_generated_at' => 'datetime',
        'next_generation_at' => 'datetime',
    ];

    /**
     * Get the user who created this report
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Check if report is ready
     */
    public function isReady(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if report generation failed
     */
    public function hasFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Check if report is currently generating
     */
    public function isGenerating(): bool
    {
        return $this->status === 'generating';
    }
}
