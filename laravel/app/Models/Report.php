<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends BaseModel
{
    protected $table = 'reports';
    protected $primaryKey = 'report_id';

    protected $fillable = [
        'user_id',
        'report_type',
        'report_title',
        'report_time',
        'report_galaxy',
        'report_system',
        'report_planet',
        'report_data',
        'report_read',
    ];

    protected $casts = [
        'report_time' => 'datetime',
        'report_galaxy' => 'integer',
        'report_system' => 'integer',
        'report_planet' => 'integer',
        'report_data' => 'array', // PostgreSQL JSONB
        'report_read' => 'boolean',
    ];

    /**
     * Get the user that owns this report
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get coordinates as string
     */
    public function getCoordinatesAttribute(): string
    {
        return "{$this->report_galaxy}:{$this->report_system}:{$this->report_planet}";
    }

    /**
     * Mark report as read
     */
    public function markAsRead(): void
    {
        if (!$this->report_read) {
            $this->update(['report_read' => true]);
        }
    }

    /**
     * Check if report is combat report
     */
    public function isCombatReport(): bool
    {
        return $this->report_type === 'combat';
    }

    /**
     * Check if report is espionage report
     */
    public function isEspionageReport(): bool
    {
        return $this->report_type === 'espionage';
    }

    /**
     * Scope: Get unread reports
     */
    public function scopeUnread($query)
    {
        return $query->where('report_read', false);
    }

    /**
     * Scope: Get reports by type
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('report_type', $type);
    }

    /**
     * Scope: Get recent reports
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('report_time', '>=', now()->subDays($days));
    }
}
