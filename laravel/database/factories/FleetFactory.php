<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Fleet;
use App\Models\User;
use App\Models\Planet;
use Illuminate\Database\Eloquent\Factories\Factory;

class FleetFactory extends Factory
{
    protected $model = Fleet::class;

    public function definition(): array
    {
        $startGalaxy = $this->faker->numberBetween(1, 9);
        $startSystem = $this->faker->numberBetween(1, 499);
        $startPlanet = $this->faker->numberBetween(1, 15);

        $endGalaxy = $this->faker->numberBetween(1, 9);
        $endSystem = $this->faker->numberBetween(1, 499);
        $endPlanet = $this->faker->numberBetween(1, 15);

        return [
            'fleet_owner' => User::factory(),
            'fleet_mission' => $this->faker->numberBetween(1, 15),
            'fleet_amount' => $this->faker->numberBetween(10, 100),
            'fleet_composition' => [
                '202' => $this->faker->numberBetween(5, 20),
                '204' => $this->faker->numberBetween(10, 50),
            ],
            'fleet_start_time' => now(),
            'fleet_start_galaxy' => $startGalaxy,
            'fleet_start_system' => $startSystem,
            'fleet_start_planet' => $startPlanet,
            'fleet_start_type' => 1,
            'fleet_start_planet_id' => null,
            'fleet_end_time' => now()->addHours(2),
            'fleet_end_stay' => 0,
            'fleet_end_galaxy' => $endGalaxy,
            'fleet_end_system' => $endSystem,
            'fleet_end_planet' => $endPlanet,
            'fleet_end_type' => 1,
            'fleet_end_planet_id' => null,
            'fleet_target_user_id' => null,
            'fleet_resource_metal' => $this->faker->numberBetween(0, 10000),
            'fleet_resource_crystal' => $this->faker->numberBetween(0, 5000),
            'fleet_resource_deuterium' => $this->faker->numberBetween(0, 2000),
            'fleet_mess' => 0,
            'fleet_group' => 0,
        ];
    }

    /**
     * Indicate that the fleet is returning
     */
    public function returning(): static
    {
        return $this->state(fn (array $attributes) => [
            'fleet_mess' => 1,
        ]);
    }

    /**
     * Indicate that the fleet has arrived
     */
    public function arrived(): static
    {
        return $this->state(fn (array $attributes) => [
            'fleet_end_time' => now()->subHour(),
        ]);
    }
}
