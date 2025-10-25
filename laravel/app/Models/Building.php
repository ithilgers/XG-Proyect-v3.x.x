<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Building extends BaseModel
{
    protected $table = 'buildings';
    protected $primaryKey = 'building_id';

    protected $fillable = [
        'planet_id',
        'building_metal_mine',
        'building_crystal_mine',
        'building_deuterium_synthesizer',
        'building_solar_plant',
        'building_fusion_reactor',
        'building_robotic_factory',
        'building_nanite_factory',
        'building_shipyard',
        'building_metal_storage',
        'building_crystal_storage',
        'building_deuterium_storage',
        'building_research_laboratory',
        'building_terraformer',
        'building_alliance_depot',
        'building_missile_silo',
        'building_lunar_base',
        'building_sensor_phalanx',
        'building_jump_gate',
        'building_queue',
    ];

    protected $casts = [
        'building_metal_mine' => 'integer',
        'building_crystal_mine' => 'integer',
        'building_deuterium_synthesizer' => 'integer',
        'building_solar_plant' => 'integer',
        'building_fusion_reactor' => 'integer',
        'building_robotic_factory' => 'integer',
        'building_nanite_factory' => 'integer',
        'building_shipyard' => 'integer',
        'building_metal_storage' => 'integer',
        'building_crystal_storage' => 'integer',
        'building_deuterium_storage' => 'integer',
        'building_research_laboratory' => 'integer',
        'building_terraformer' => 'integer',
        'building_alliance_depot' => 'integer',
        'building_missile_silo' => 'integer',
        'building_lunar_base' => 'integer',
        'building_sensor_phalanx' => 'integer',
        'building_jump_gate' => 'integer',
        'building_queue' => 'array', // PostgreSQL JSONB
    ];

    /**
     * Get the planet that owns this building set
     */
    public function planet(): BelongsTo
    {
        return $this->belongsTo(Planet::class, 'planet_id', 'planet_id');
    }

    /**
     * Get building level by ID
     */
    public function getBuildingLevel(int $buildingId): int
    {
        $buildingMap = [
            1 => 'building_metal_mine',
            2 => 'building_crystal_mine',
            3 => 'building_deuterium_synthesizer',
            4 => 'building_solar_plant',
            12 => 'building_fusion_reactor',
            14 => 'building_robotic_factory',
            15 => 'building_nanite_factory',
            21 => 'building_shipyard',
            22 => 'building_metal_storage',
            23 => 'building_crystal_storage',
            24 => 'building_deuterium_storage',
            31 => 'building_research_laboratory',
            33 => 'building_terraformer',
            34 => 'building_alliance_depot',
            44 => 'building_missile_silo',
            41 => 'building_lunar_base',
            42 => 'building_sensor_phalanx',
            43 => 'building_jump_gate',
        ];

        $field = $buildingMap[$buildingId] ?? null;

        return $field ? $this->{$field} : 0;
    }

    /**
     * Check if a building is currently being built
     */
    public function isBuildingInQueue(int $buildingId): bool
    {
        if (!$this->building_queue) {
            return false;
        }

        foreach ($this->building_queue as $item) {
            if ($item['building_id'] === $buildingId) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get queue count
     */
    public function getQueueCount(): int
    {
        return is_array($this->building_queue) ? count($this->building_queue) : 0;
    }
}
