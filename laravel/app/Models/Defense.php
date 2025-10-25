<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Defense extends BaseModel
{
    protected $table = 'defenses';
    protected $primaryKey = 'defense_id';

    protected $fillable = [
        'planet_id',
        'defense_rocket_launcher',
        'defense_light_laser',
        'defense_heavy_laser',
        'defense_gauss_cannon',
        'defense_ion_cannon',
        'defense_plasma_turret',
        'defense_small_shield_dome',
        'defense_large_shield_dome',
        'defense_anti_ballistic_missile',
        'defense_interplanetary_missile',
        'defense_queue',
    ];

    protected $casts = [
        'defense_rocket_launcher' => 'integer',
        'defense_light_laser' => 'integer',
        'defense_heavy_laser' => 'integer',
        'defense_gauss_cannon' => 'integer',
        'defense_ion_cannon' => 'integer',
        'defense_plasma_turret' => 'integer',
        'defense_small_shield_dome' => 'integer',
        'defense_large_shield_dome' => 'integer',
        'defense_anti_ballistic_missile' => 'integer',
        'defense_interplanetary_missile' => 'integer',
        'defense_queue' => 'array', // PostgreSQL JSONB
    ];

    /**
     * Get the planet that owns these defenses
     */
    public function planet(): BelongsTo
    {
        return $this->belongsTo(Planet::class, 'planet_id', 'planet_id');
    }

    /**
     * Get defense count by ID
     */
    public function getDefenseCount(int $defenseId): int
    {
        $defenseMap = [
            401 => 'defense_rocket_launcher',
            402 => 'defense_light_laser',
            403 => 'defense_heavy_laser',
            404 => 'defense_gauss_cannon',
            405 => 'defense_ion_cannon',
            406 => 'defense_plasma_turret',
            407 => 'defense_small_shield_dome',
            408 => 'defense_large_shield_dome',
            502 => 'defense_anti_ballistic_missile',
            503 => 'defense_interplanetary_missile',
        ];

        $field = $defenseMap[$defenseId] ?? null;

        return $field ? $this->{$field} : 0;
    }

    /**
     * Get total defense count
     */
    public function getTotalDefenses(): int
    {
        return $this->defense_rocket_launcher +
            $this->defense_light_laser +
            $this->defense_heavy_laser +
            $this->defense_gauss_cannon +
            $this->defense_ion_cannon +
            $this->defense_plasma_turret +
            $this->defense_small_shield_dome +
            $this->defense_large_shield_dome +
            $this->defense_anti_ballistic_missile +
            $this->defense_interplanetary_missile;
    }

    /**
     * Decrease defense count
     */
    public function decreaseDefense(int $defenseId, int $amount): void
    {
        $defenseMap = [
            401 => 'defense_rocket_launcher',
            402 => 'defense_light_laser',
            403 => 'defense_heavy_laser',
            404 => 'defense_gauss_cannon',
            405 => 'defense_ion_cannon',
            406 => 'defense_plasma_turret',
            407 => 'defense_small_shield_dome',
            408 => 'defense_large_shield_dome',
            502 => 'defense_anti_ballistic_missile',
            503 => 'defense_interplanetary_missile',
        ];

        $field = $defenseMap[$defenseId] ?? null;

        if ($field && $this->{$field} >= $amount) {
            $this->{$field} -= $amount;
            $this->save();
        }
    }
}
