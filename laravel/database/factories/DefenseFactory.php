<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Defense;
use App\Models\Planet;
use Illuminate\Database\Eloquent\Factories\Factory;

class DefenseFactory extends Factory
{
    protected $model = Defense::class;

    public function definition(): array
    {
        return [
            'planet_id' => Planet::factory(),
            'defense_rocket_launcher' => $this->faker->numberBetween(0, 100),
            'defense_light_laser' => $this->faker->numberBetween(0, 80),
            'defense_heavy_laser' => $this->faker->numberBetween(0, 50),
            'defense_gauss_cannon' => $this->faker->numberBetween(0, 30),
            'defense_ion_cannon' => $this->faker->numberBetween(0, 20),
            'defense_plasma_turret' => $this->faker->numberBetween(0, 10),
            'defense_small_shield_dome' => $this->faker->numberBetween(0, 1),
            'defense_large_shield_dome' => $this->faker->numberBetween(0, 1),
            'defense_anti_ballistic_missile' => $this->faker->numberBetween(0, 20),
            'defense_interplanetary_missile' => $this->faker->numberBetween(0, 10),
            'defense_queue' => null,
        ];
    }
}
