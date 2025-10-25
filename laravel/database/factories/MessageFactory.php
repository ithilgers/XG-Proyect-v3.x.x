<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        return [
            'message_sender' => User::factory(),
            'message_receiver' => User::factory(),
            'message_subject' => $this->faker->sentence(),
            'message_text' => $this->faker->paragraph(),
            'message_time' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'message_type' => $this->faker->numberBetween(0, 5),
            'message_read' => $this->faker->boolean(),
        ];
    }

    /**
     * Indicate that the message is unread
     */
    public function unread(): static
    {
        return $this->state(fn (array $attributes) => [
            'message_read' => false,
        ]);
    }
}
