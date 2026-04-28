<?php

namespace Tests\Feature\Public;

use App\Models\AboutMe;
use App\Models\Experience;
use App\Models\Project;
use App\Models\SocialLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioTest extends TestCase
{
    use RefreshDatabase;

    // --- Página pública ---

    public function test_home_returns_200(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_about_me_name_appears_in_html(): void
    {
        AboutMe::create([
            'name' => 'Kaiki Hirata',
            'title' => 'Full Stack Dev',
            'bio' => 'Bio de teste.',
            'email' => 'kaiki@test.com',
            'is_available_for_work' => true,
        ]);

        $response = $this->get('/');

        $response->assertSee('Kaiki Hirata');
    }

    // --- Experiências ---

    public function test_visible_experience_appears_on_page(): void
    {
        Experience::factory()->create([
            'company' => 'Visible Corp',
            'is_visible' => true,
        ]);

        $response = $this->get('/');

        $response->assertSee('Visible Corp');
    }

    public function test_invisible_experience_does_not_appear_on_page(): void
    {
        Experience::factory()->create([
            'company' => 'Hidden Corp',
            'is_visible' => false,
        ]);

        $response = $this->get('/');

        $response->assertDontSee('Hidden Corp');
    }

    // --- Projetos ---

    public function test_visible_project_appears_on_page(): void
    {
        Project::factory()->create([
            'title' => 'Public Project',
            'is_visible' => true,
        ]);

        $response = $this->get('/');

        $response->assertSee('Public Project');
    }

    public function test_invisible_project_does_not_appear_on_page(): void
    {
        Project::factory()->create([
            'title' => 'Secret Project',
            'is_visible' => false,
        ]);

        $response = $this->get('/');

        $response->assertDontSee('Secret Project');
    }

    public function test_project_with_show_button_true_renders_ver_projeto(): void
    {
        Project::factory()->create([
            'is_visible' => true,
            'show_project_button' => true,
            'demo_url' => 'https://demo.example.com',
        ]);

        $response = $this->get('/');

        $response->assertSee('Ver Projeto');
    }

    public function test_project_with_show_button_false_does_not_render_ver_projeto(): void
    {
        Project::factory()->create([
            'is_visible' => true,
            'show_project_button' => false,
            'demo_url' => 'https://demo.example.com',
        ]);

        $response = $this->get('/');

        $response->assertDontSee('Ver Projeto');
    }

    // --- Links Sociais ---

    public function test_visible_social_link_appears_on_page(): void
    {
        SocialLink::factory()->create([
            'platform' => 'GitHub',
            'is_visible' => true,
        ]);

        $response = $this->get('/');

        $response->assertSee('GitHub');
    }

    public function test_invisible_social_link_does_not_appear_on_page(): void
    {
        SocialLink::factory()->create([
            'platform' => 'SecretPlatform',
            'is_visible' => false,
        ]);

        $response = $this->get('/');

        $response->assertDontSee('SecretPlatform');
    }
}
