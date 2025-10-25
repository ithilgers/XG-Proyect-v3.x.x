<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'report_type' => $this->faker->randomElement(['combat', 'espionage', 'transport', 'deployment', 'harvest', 'colonization']),
            'report_title' => $this->faker->sentence(),
            'report_time' => $this->faker->dateTimeBetween('-7 days', 'now'),
            'report_galaxy' => $this->faker->numberBetween(1, 9),
            'report_system' => $this->faker->numberBetween(1, 499),
            'report_planet' => $this->faker->numberBetween(1, 15),
            'report_data' => [
                'attacker' => ['name' => $this->faker->userName(), 'losses' => $this->faker->numberBetween(0, 10000)],
                'defender' => ['name' => $this->faker->userName(), 'losses' => $this->faker->numberBetween(0, 10000)],
                'loot' => ['metal' => $this->faker->numberBetween(0, 5000), 'crystal' => $this->faker->numberBetween(0, 2500), 'deuterium' => $this->faker->numberBetween(0, 1000)],
                'debris' => ['metal' => $this->faker->numberBetween(0, 3000), 'crystal' => $this->faker->numberBetween(0, 1500)],
                'winner' => $this->faker->randomElement(['attacker', 'defender', 'draw']),
            ],
            'report_read' => $this->faker->boolean(),
        ];
    }

    /**
     * Indicate that the report is unread
     */
    public function unread(): static
    {
        return $this->state(fn (array $attributes) => [
            'report_read' => false,
        ]);
    }

    /**
     * Indicate that the report is a combat report
     */
    public function combat(): static
    {
        return $this->state(fn (array $attributes) => [
            'report_type' => 'combat',
        ]);
    }

    /**
     * Indicate that the report is an espionage report
     */
    public function espionage(): static
    {
        return $this->state(fn (array $attributes) => [
            'report_type' => 'espionage',
        ]);
    }
}
