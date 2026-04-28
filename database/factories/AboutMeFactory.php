<?php

namespace Database\Factories;

use App\Models\AboutMe;
use Illuminate\Database\Eloquent\Factories\Factory;

class AboutMeFactory extends Factory
{
    protected $model = AboutMe::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'title' => fake()->jobTitle(),
            'bio' => fake()->paragraphs(2, true),
            'avatar_path' => null,
            'email' => fake()->unique()->safeEmail(),
            'location' => fake()->city() . ', ' . fake()->country(),
            'is_available_for_work' => true,
        ];
    }
}
