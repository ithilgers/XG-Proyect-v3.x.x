<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AllianceStatistic extends BaseModel
{
    protected $table = 'alliance_statistics';
    protected $primaryKey = 'stat_id';

    protected $fillable = [
        'alliance_id',
        'stat_buildings_points',
        'stat_buildings_rank',
        'stat_buildings_old_rank',
        'stat_defenses_points',
        'stat_defenses_rank',
        'stat_defenses_old_rank',
        'stat_ships_points',
        'stat_ships_rank',
        'stat_ships_old_rank',
        'stat_research_points',
        'stat_research_rank',
        'stat_research_old_rank',
        'stat_total_points',
        'stat_total_rank',
        'stat_total_old_rank',
        'stat_total_members',
        'stat_update_time',
    ];

    protected $casts = [
        'stat_buildings_points' => 'integer',
        'stat_buildings_rank' => 'integer',
        'stat_buildings_old_rank' => 'integer',
        'stat_defenses_points' => 'integer',
        'stat_defenses_rank' => 'integer',
        'stat_defenses_old_rank' => 'integer',
        'stat_ships_points' => 'integer',
        'stat_ships_rank' => 'integer',
        'stat_ships_old_rank' => 'integer',
        'stat_research_points' => 'integer',
        'stat_research_rank' => 'integer',
        'stat_research_old_rank' => 'integer',
        'stat_total_points' => 'integer',
        'stat_total_rank' => 'integer',
        'stat_total_old_rank' => 'integer',
        'stat_total_members' => 'integer',
        'stat_update_time' => 'datetime',
    ];

    /**
     * Get the alliance that owns these statistics
     */
    public function alliance(): BelongsTo
    {
        return $this->belongsTo(Alliance::class, 'alliance_id', 'alliance_id');
    }

    /**
     * Get rank change for total points
     */
    public function getTotalRankChangeAttribute(): int
    {
        return $this->stat_total_old_rank - $this->stat_total_rank;
    }

    /**
     * Get average points per member
     */
    public function getAveragePointsAttribute(): float
    {
        if ($this->stat_total_members === 0) {
            return 0;
        }

        return $this->stat_total_points / $this->stat_total_members;
    }

    /**
     * Scope: Get top alliances
     */
    public function scopeTopAlliances($query, int $limit = 100)
    {
        return $query->orderBy('stat_total_rank')
            ->limit($limit);
    }
}
