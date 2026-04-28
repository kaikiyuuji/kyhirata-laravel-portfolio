<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $title = fake()->sentence(3);
        
        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . fake()->unique()->word(),
            'description' => fake()->paragraphs(2, true),
            'thumbnail_path' => null,
            'github_url' => fake()->optional()->url(),
            'demo_url' => fake()->optional()->url(),
            'show_project_button' => fake()->boolean(80),
            'is_visible' => fake()->boolean(90),
            'order' => fake()->numberBetween(0, 100),
        ];
    }
}
