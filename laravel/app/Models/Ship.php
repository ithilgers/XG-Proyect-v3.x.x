<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ship extends BaseModel
{
    protected $table = 'ships';
    protected $primaryKey = 'ship_id';

    protected $fillable = [
        'planet_id',
        'ship_small_cargo_ship',
        'ship_large_cargo_ship',
        'ship_light_fighter',
        'ship_heavy_fighter',
        'ship_cruiser',
        'ship_battleship',
        'ship_colony_ship',
        'ship_recycler',
        'ship_espionage_probe',
        'ship_bomber',
        'ship_solar_satellite',
        'ship_destroyer',
        'ship_deathstar',
        'ship_battlecruiser',
        'ship_queue',
    ];

    protected $casts = [
        'ship_small_cargo_ship' => 'integer',
        'ship_large_cargo_ship' => 'integer',
        'ship_light_fighter' => 'integer',
        'ship_heavy_fighter' => 'integer',
        'ship_cruiser' => 'integer',
        'ship_battleship' => 'integer',
        'ship_colony_ship' => 'integer',
        'ship_recycler' => 'integer',
        'ship_espionage_probe' => 'integer',
        'ship_bomber' => 'integer',
        'ship_solar_satellite' => 'integer',
        'ship_destroyer' => 'integer',
        'ship_deathstar' => 'integer',
        'ship_battlecruiser' => 'integer',
        'ship_queue' => 'array', // PostgreSQL JSONB
    ];

    /**
     * Get the planet that owns these ships
     */
    public function planet(): BelongsTo
    {
        return $this->belongsTo(Planet::class, 'planet_id', 'planet_id');
    }

    /**
     * Get ship count by ID
     */
    public function getShipCount(int $shipId): int
    {
        $shipMap = [
            202 => 'ship_small_cargo_ship',
            203 => 'ship_large_cargo_ship',
            204 => 'ship_light_fighter',
            205 => 'ship_heavy_fighter',
            206 => 'ship_cruiser',
            207 => 'ship_battleship',
            208 => 'ship_colony_ship',
            209 => 'ship_recycler',
            210 => 'ship_espionage_probe',
            211 => 'ship_bomber',
            212 => 'ship_solar_satellite',
            213 => 'ship_destroyer',
            214 => 'ship_deathstar',
            215 => 'ship_battlecruiser',
        ];

        $field = $shipMap[$shipId] ?? null;

        return $field ? $this->{$field} : 0;
    }

    /**
     * Get total ship count
     */
    public function getTotalShips(): int
    {
        return $this->ship_small_cargo_ship +
            $this->ship_large_cargo_ship +
            $this->ship_light_fighter +
            $this->ship_heavy_fighter +
            $this->ship_cruiser +
            $this->ship_battleship +
            $this->ship_colony_ship +
            $this->ship_recycler +
            $this->ship_espionage_probe +
            $this->ship_bomber +
            $this->ship_solar_satellite +
            $this->ship_destroyer +
            $this->ship_deathstar +
            $this->ship_battlecruiser;
    }

    /**
     * Decrease ship count
     */
    public function decreaseShip(int $shipId, int $amount): void
    {
        $shipMap = [
            202 => 'ship_small_cargo_ship',
            203 => 'ship_large_cargo_ship',
            204 => 'ship_light_fighter',
            205 => 'ship_heavy_fighter',
            206 => 'ship_cruiser',
            207 => 'ship_battleship',
            208 => 'ship_colony_ship',
            209 => 'ship_recycler',
            210 => 'ship_espionage_probe',
            211 => 'ship_bomber',
            212 => 'ship_solar_satellite',
            213 => 'ship_destroyer',
            214 => 'ship_deathstar',
            215 => 'ship_battlecruiser',
        ];

        $field = $shipMap[$shipId] ?? null;

        if ($field && $this->{$field} >= $amount) {
            $this->{$field} -= $amount;
            $this->save();
        }
    }

    /**
     * Increase ship count
     */
    public function increaseShip(int $shipId, int $amount): void
    {
        $shipMap = [
            202 => 'ship_small_cargo_ship',
            203 => 'ship_large_cargo_ship',
            204 => 'ship_light_fighter',
            205 => 'ship_heavy_fighter',
            206 => 'ship_cruiser',
            207 => 'ship_battleship',
            208 => 'ship_colony_ship',
            209 => 'ship_recycler',
            210 => 'ship_espionage_probe',
            211 => 'ship_bomber',
            212 => 'ship_solar_satellite',
            213 => 'ship_destroyer',
            214 => 'ship_deathstar',
            215 => 'ship_battlecruiser',
        ];

        $field = $shipMap[$shipId] ?? null;

        if ($field) {
            $this->{$field} += $amount;
            $this->save();
        }
    }
}
