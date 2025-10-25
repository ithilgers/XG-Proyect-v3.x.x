<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Building;
use App\Models\Planet;
use Illuminate\Database\Eloquent\Factories\Factory;

class BuildingFactory extends Factory
{
    protected $model = Building::class;

    public function definition(): array
    {
        return [
            'planet_id' => Planet::factory(),
            'building_metal_mine' => $this->faker->numberBetween(0, 30),
            'building_crystal_mine' => $this->faker->numberBetween(0, 25),
            'building_deuterium_synthesizer' => $this->faker->numberBetween(0, 20),
            'building_solar_plant' => $this->faker->numberBetween(0, 30),
            'building_fusion_reactor' => $this->faker->numberBetween(0, 10),
            'building_robotic_factory' => $this->faker->numberBetween(0, 15),
            'building_nanite_factory' => $this->faker->numberBetween(0, 5),
            'building_shipyard' => $this->faker->numberBetween(0, 12),
            'building_metal_storage' => $this->faker->numberBetween(0, 12),
            'building_crystal_storage' => $this->faker->numberBetween(0, 12),
            'building_deuterium_storage' => $this->faker->numberBetween(0, 12),
            'building_research_laboratory' => $this->faker->numberBetween(0, 12),
            'building_terraformer' => 0,
            'building_alliance_depot' => 0,
            'building_missile_silo' => 0,
            'building_lunar_base' => 0,
            'building_sensor_phalanx' => 0,
            'building_jump_gate' => 0,
            'building_queue' => null,
        ];
    }

    /**
     * Indicate that the building has items in queue
     */
    public function withQueue(): static
    {
        return $this->state(fn (array $attributes) => [
            'building_queue' => [
                [
                    'building_id' => 1,
                    'level' => 5,
                    'start_time' => now()->toDateTimeString(),
                    'end_time' => now()->addHours(2)->toDateTimeString(),
                    'metal' => 1000,
                    'crystal' => 500,
                    'deuterium' => 0,
                ],
            ],
        ]);
    }
}
