<?php

namespace Database\Factories;

use App\Models\Technology;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class TechnologyFactory extends Factory
{
    protected $model = Technology::class;

    public function definition(): array
    {
        $name = fake()->unique()->word();
        
        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'color' => fake()->hexColor(),
        ];
    }
}
