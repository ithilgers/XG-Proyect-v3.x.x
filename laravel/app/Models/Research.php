<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Research extends BaseModel
{
    protected $table = 'research';
    protected $primaryKey = 'research_id';

    protected $fillable = [
        'user_id',
        'research_espionage_technology',
        'research_computer_technology',
        'research_weapons_technology',
        'research_shielding_technology',
        'research_armour_technology',
        'research_energy_technology',
        'research_hyperspace_technology',
        'research_combustion_drive',
        'research_impulse_drive',
        'research_hyperspace_drive',
        'research_laser_technology',
        'research_ion_technology',
        'research_plasma_technology',
        'research_intergalactic_research_network',
        'research_astrophysics',
        'research_graviton_technology',
        'research_queue',
        'research_current_research',
    ];

    protected $casts = [
        'research_espionage_technology' => 'integer',
        'research_computer_technology' => 'integer',
        'research_weapons_technology' => 'integer',
        'research_shielding_technology' => 'integer',
        'research_armour_technology' => 'integer',
        'research_energy_technology' => 'integer',
        'research_hyperspace_technology' => 'integer',
        'research_combustion_drive' => 'integer',
        'research_impulse_drive' => 'integer',
        'research_hyperspace_drive' => 'integer',
        'research_laser_technology' => 'integer',
        'research_ion_technology' => 'integer',
        'research_plasma_technology' => 'integer',
        'research_intergalactic_research_network' => 'integer',
        'research_astrophysics' => 'integer',
        'research_graviton_technology' => 'integer',
        'research_queue' => 'array', // PostgreSQL JSONB
    ];

    /**
     * Get the user that owns this research
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get research level by ID
     */
    public function getResearchLevel(int $researchId): int
    {
        $researchMap = [
            106 => 'research_espionage_technology',
            108 => 'research_computer_technology',
            109 => 'research_weapons_technology',
            110 => 'research_shielding_technology',
            111 => 'research_armour_technology',
            113 => 'research_energy_technology',
            114 => 'research_hyperspace_technology',
            115 => 'research_combustion_drive',
            117 => 'research_impulse_drive',
            118 => 'research_hyperspace_drive',
            120 => 'research_laser_technology',
            121 => 'research_ion_technology',
            122 => 'research_plasma_technology',
            123 => 'research_intergalactic_research_network',
            124 => 'research_astrophysics',
            199 => 'research_graviton_technology',
        ];

        $field = $researchMap[$researchId] ?? null;

        return $field ? $this->{$field} : 0;
    }

    /**
     * Check if research is currently in progress
     */
    public function hasActiveResearch(): bool
    {
        return !empty($this->research_current_research);
    }

    /**
     * Get max planet count based on astrophysics
     */
    public function getMaxPlanets(): int
    {
        return 1 + $this->research_astrophysics;
    }

    /**
     * Get max expedition slots based on astrophysics
     */
    public function getMaxExpeditions(): int
    {
        return (int) floor($this->research_astrophysics / 3);
    }
}
