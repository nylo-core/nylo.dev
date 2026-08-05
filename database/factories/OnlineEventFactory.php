<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OnlineEvent>
 */
class OnlineEventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('+1 day', '+1 month');

        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'start_date' => $startDate,
            'end_date' => (clone $startDate)->modify('+2 hours'),
            'link' => $this->faker->url(),
        ];
    }

    /**
     * Event that is currently in progress.
     */
    public function happeningNow(): static
    {
        return $this->state(fn (array $attributes) => [
            'start_date' => now()->subHour(),
            'end_date' => now()->addHour(),
        ]);
    }

    /**
     * Event that has already ended.
     */
    public function past(): static
    {
        return $this->state(fn (array $attributes) => [
            'start_date' => now()->subDays(7),
            'end_date' => now()->subDays(7)->addHours(2),
        ]);
    }
}
