<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Ship;
use App\Models\Planet;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShipFactory extends Factory
{
    protected $model = Ship::class;

    public function definition(): array
    {
        return [
            'planet_id' => Planet::factory(),
            'ship_small_cargo_ship' => $this->faker->numberBetween(0, 100),
            'ship_large_cargo_ship' => $this->faker->numberBetween(0, 50),
            'ship_light_fighter' => $this->faker->numberBetween(0, 200),
            'ship_heavy_fighter' => $this->faker->numberBetween(0, 100),
            'ship_cruiser' => $this->faker->numberBetween(0, 50),
            'ship_battleship' => $this->faker->numberBetween(0, 25),
            'ship_colony_ship' => $this->faker->numberBetween(0, 2),
            'ship_recycler' => $this->faker->numberBetween(0, 20),
            'ship_espionage_probe' => $this->faker->numberBetween(0, 50),
            'ship_bomber' => $this->faker->numberBetween(0, 10),
            'ship_solar_satellite' => $this->faker->numberBetween(0, 100),
            'ship_destroyer' => $this->faker->numberBetween(0, 5),
            'ship_deathstar' => 0,
            'ship_battlecruiser' => $this->faker->numberBetween(0, 10),
            'ship_queue' => null,
        ];
    }

    /**
     * Indicate that the ship has items in queue
     */
    public function withQueue(): static
    {
        return $this->state(fn (array $attributes) => [
            'ship_queue' => [
                [
                    'ship_id' => 204,
                    'amount' => 10,
                    'start_time' => now()->toDateTimeString(),
                    'end_time' => now()->addHours(1)->toDateTimeString(),
                    'metal' => 3000,
                    'crystal' => 1000,
                    'deuterium' => 0,
                ],
            ],
        ]);
    }
}
