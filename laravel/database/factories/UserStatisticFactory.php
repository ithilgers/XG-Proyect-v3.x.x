<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\UserStatistic;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserStatisticFactory extends Factory
{
    protected $model = UserStatistic::class;

    public function definition(): array
    {
        $buildingPoints = $this->faker->numberBetween(1000, 100000);
        $defensePoints = $this->faker->numberBetween(500, 50000);
        $shipPoints = $this->faker->numberBetween(1000, 200000);
        $researchPoints = $this->faker->numberBetween(500, 50000);
        $totalPoints = $buildingPoints + $defensePoints + $shipPoints + $researchPoints;

        $buildingRank = $this->faker->numberBetween(1, 10000);
        $defenseRank = $this->faker->numberBetween(1, 10000);
        $shipRank = $this->faker->numberBetween(1, 10000);
        $researchRank = $this->faker->numberBetween(1, 10000);
        $totalRank = $this->faker->numberBetween(1, 10000);

        return [
            'user_id' => User::factory(),
            'stat_buildings_points' => $buildingPoints,
            'stat_buildings_rank' => $buildingRank,
            'stat_buildings_old_rank' => $buildingRank + $this->faker->numberBetween(-100, 100),
            'stat_defenses_points' => $defensePoints,
            'stat_defenses_rank' => $defenseRank,
            'stat_defenses_old_rank' => $defenseRank + $this->faker->numberBetween(-100, 100),
            'stat_ships_points' => $shipPoints,
            'stat_ships_rank' => $shipRank,
            'stat_ships_old_rank' => $shipRank + $this->faker->numberBetween(-100, 100),
            'stat_research_points' => $researchPoints,
            'stat_research_rank' => $researchRank,
            'stat_research_old_rank' => $researchRank + $this->faker->numberBetween(-100, 100),
            'stat_total_points' => $totalPoints,
            'stat_total_rank' => $totalRank,
            'stat_total_old_rank' => $totalRank + $this->faker->numberBetween(-100, 100),
            'stat_update_time' => now(),
        ];
    }

    /**
     * Indicate top 100 player
     */
    public function topPlayer(): static
    {
        return $this->state(fn (array $attributes) => [
            'stat_total_points' => $this->faker->numberBetween(500000, 10000000),
            'stat_total_rank' => $this->faker->numberBetween(1, 100),
        ]);
    }
}
