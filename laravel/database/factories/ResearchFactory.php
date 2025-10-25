<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Research;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResearchFactory extends Factory
{
    protected $model = Research::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'research_espionage_technology' => $this->faker->numberBetween(0, 15),
            'research_computer_technology' => $this->faker->numberBetween(0, 20),
            'research_weapons_technology' => $this->faker->numberBetween(0, 20),
            'research_shielding_technology' => $this->faker->numberBetween(0, 20),
            'research_armour_technology' => $this->faker->numberBetween(0, 20),
            'research_energy_technology' => $this->faker->numberBetween(0, 20),
            'research_hyperspace_technology' => $this->faker->numberBetween(0, 15),
            'research_combustion_drive' => $this->faker->numberBetween(0, 20),
            'research_impulse_drive' => $this->faker->numberBetween(0, 17),
            'research_hyperspace_drive' => $this->faker->numberBetween(0, 15),
            'research_laser_technology' => $this->faker->numberBetween(0, 15),
            'research_ion_technology' => $this->faker->numberBetween(0, 10),
            'research_plasma_technology' => $this->faker->numberBetween(0, 8),
            'research_intergalactic_research_network' => $this->faker->numberBetween(0, 12),
            'research_astrophysics' => $this->faker->numberBetween(0, 25),
            'research_graviton_technology' => 0,
            'research_queue' => null,
            'research_current_research' => null,
        ];
    }

    /**
     * Indicate that research is in progress
     */
    public function withActiveResearch(): static
    {
        return $this->state(fn (array $attributes) => [
            'research_current_research' => 106,
            'research_queue' => [
                [
                    'research_id' => 106,
                    'level' => 5,
                    'start_time' => now()->toDateTimeString(),
                    'end_time' => now()->addHours(4)->toDateTimeString(),
                ],
            ],
        ]);
    }
}
