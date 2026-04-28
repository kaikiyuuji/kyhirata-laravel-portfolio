<?php

namespace Database\Seeders;

use App\Models\AboutMe;
use App\Models\Experience;
use App\Models\Project;
use App\Models\SocialLink;
use App\Models\Technology;
use Illuminate\Database\Seeder;

class DemoPortfolioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. About Me (Única linha requerida)
        if (AboutMe::count() === 0) {
            AboutMe::factory()->create([
                'name' => 'Desenvolvedor Demo',
                'title' => 'Full Stack Developer',
                'email' => config('admin.email'),
            ]);
        }

        // 2. Experiences
        if (Experience::count() === 0) {
            Experience::factory(4)->create();
        }

        // 3. Technologies
        if (Technology::count() === 0) {
            $techs = Technology::factory(10)->create();

            // 4. Projects & Pivot
            if (Project::count() === 0) {
                Project::factory(5)->create()->each(function (Project $project) use ($techs) {
                    $randomTechs = $techs->random(rand(2, 4));
                    
                    foreach ($randomTechs as $index => $tech) {
                        $project->technologies()->attach($tech->id, [
                            'is_primary' => $index === 0, // Primeira tech será a principal
                        ]);
                    }
                });
            }
        }

        // 5. Social Links
        if (SocialLink::count() === 0) {
            SocialLink::factory(4)->create();
        }
    }
}
