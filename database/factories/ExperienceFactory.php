<?php

namespace Database\Factories;

use App\Models\Experience;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExperienceFactory extends Factory
{
    protected $model = Experience::class;

    public function definition(): array
    {
        $startedAt = fake()->dateTimeBetween('-5 years', '-1 year');
        $endedAt = fake()->optional(0.7)->dateTimeBetween($startedAt, 'now');

        return [
            'company' => fake()->company(),
            'role' => fake()->jobTitle(),
            'description' => fake()->paragraph(),
            'started_at' => $startedAt->format('Y-m-d'),
            'ended_at' => $endedAt ? $endedAt->format('Y-m-d') : null,
            'is_visible' => fake()->boolean(90),
            'order' => fake()->numberBetween(0, 100),
        ];
    }
}
