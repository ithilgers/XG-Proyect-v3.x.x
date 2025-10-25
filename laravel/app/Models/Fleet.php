<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fleet extends BaseModel
{
    protected $table = 'fleets';
    protected $primaryKey = 'fleet_id';

    protected $fillable = [
        'fleet_owner',
        'fleet_mission',
        'fleet_amount',
        'fleet_composition',
        'fleet_start_time',
        'fleet_start_galaxy',
        'fleet_start_system',
        'fleet_start_planet',
        'fleet_start_type',
        'fleet_start_planet_id',
        'fleet_end_time',
        'fleet_end_stay',
        'fleet_end_galaxy',
        'fleet_end_system',
        'fleet_end_planet',
        'fleet_end_type',
        'fleet_end_planet_id',
        'fleet_target_user_id',
        'fleet_resource_metal',
        'fleet_resource_crystal',
        'fleet_resource_deuterium',
        'fleet_mess',
        'fleet_group',
    ];

    protected $casts = [
        'fleet_mission' => 'integer',
        'fleet_amount' => 'integer',
        'fleet_composition' => 'array', // PostgreSQL JSONB
        'fleet_start_time' => 'datetime',
        'fleet_start_galaxy' => 'integer',
        'fleet_start_system' => 'integer',
        'fleet_start_planet' => 'integer',
        'fleet_start_type' => 'integer',
        'fleet_end_time' => 'datetime',
        'fleet_end_stay' => 'integer',
        'fleet_end_galaxy' => 'integer',
        'fleet_end_system' => 'integer',
        'fleet_end_planet' => 'integer',
        'fleet_end_type' => 'integer',
        'fleet_resource_metal' => 'decimal:2',
        'fleet_resource_crystal' => 'decimal:2',
        'fleet_resource_deuterium' => 'decimal:2',
        'fleet_mess' => 'integer',
        'fleet_group' => 'integer',
    ];

    /**
     * Get the fleet owner
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'fleet_owner', 'user_id');
    }

    /**
     * Get the start planet
     */
    public function startPlanet(): BelongsTo
    {
        return $this->belongsTo(Planet::class, 'fleet_start_planet_id', 'planet_id');
    }

    /**
     * Get the end planet
     */
    public function endPlanet(): BelongsTo
    {
        return $this->belongsTo(Planet::class, 'fleet_end_planet_id', 'planet_id');
    }

    /**
     * Get the target user
     */
    public function targetUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'fleet_target_user_id', 'user_id');
    }

    /**
     * Get start coordinates as string
     */
    public function getStartCoordinatesAttribute(): string
    {
        return "{$this->fleet_start_galaxy}:{$this->fleet_start_system}:{$this->fleet_start_planet}";
    }

    /**
     * Get end coordinates as string
     */
    public function getEndCoordinatesAttribute(): string
    {
        return "{$this->fleet_end_galaxy}:{$this->fleet_end_system}:{$this->fleet_end_planet}";
    }

    /**
     * Check if fleet has arrived
     */
    public function hasArrived(): bool
    {
        return now()->greaterThan($this->fleet_end_time);
    }

    /**
     * Check if fleet is returning
     */
    public function isReturning(): bool
    {
        return $this->fleet_mess === 1;
    }

    /**
     * Get ship count from composition
     */
    public function getShipCount(int $shipId): int
    {
        if (!is_array($this->fleet_composition)) {
            return 0;
        }

        return (int) ($this->fleet_composition[(string) $shipId] ?? 0);
    }

    /**
     * Scope: Get active fleets
     */
    public function scopeActive($query)
    {
        return $query->where('fleet_end_time', '>', now());
    }

    /**
     * Scope: Get fleets by mission
     */
    public function scopeByMission($query, int $mission)
    {
        return $query->where('fleet_mission', $mission);
    }

    /**
     * Scope: Get fleets targeting coordinates
     */
    public function scopeTargeting($query, int $galaxy, int $system, int $planet)
    {
        return $query->where('fleet_end_galaxy', $galaxy)
            ->where('fleet_end_system', $system)
            ->where('fleet_end_planet', $planet);
    }
}
