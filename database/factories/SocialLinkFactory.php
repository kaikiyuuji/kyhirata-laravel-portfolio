<?php

namespace Database\Factories;

use App\Models\SocialLink;
use Illuminate\Database\Eloquent\Factories\Factory;

class SocialLinkFactory extends Factory
{
    protected $model = SocialLink::class;

    public function definition(): array
    {
        return [
            'platform' => fake()->randomElement(['GitHub', 'LinkedIn', 'Twitter', 'YouTube']),
            'url' => fake()->url(),
            'icon' => 'fa-' . fake()->word(),
            'is_visible' => true,
            'order' => fake()->numberBetween(0, 100),
        ];
    }
}
