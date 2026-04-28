<?php

namespace Database\Factories;

use App\Models\SocialLink;
use Illuminate\Database\Eloquent\Factories\Factory;

class SocialLinkFactory extends Factory
{
    protected $model = SocialLink::class;

    public function definition(): array
    {
        $platform = fake()->randomElement(['GitHub', 'LinkedIn', 'X', 'YouTube', 'Instagram', 'Facebook']);
        
        $icons = [
            'GitHub' => 'devicon-github-original',
            'LinkedIn' => 'devicon-linkedin-plain',
            'X' => 'devicon-twitter-original',
            'YouTube' => 'devicon-youtube-plain',
            'Instagram' => 'devicon-instagram-plain',
            'Facebook' => 'devicon-facebook-plain',
        ];

        return [
            'platform' => $platform,
            'url' => fake()->url(),
            'icon' => $icons[$platform] ?? 'fas fa-link',
            'is_visible' => true,
            'order' => fake()->numberBetween(0, 100),
        ];
    }
}
