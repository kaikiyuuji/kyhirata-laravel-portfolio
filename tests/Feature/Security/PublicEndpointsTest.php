<?php

namespace Tests\Feature\Security;

use App\Models\AboutMe;
use App\Models\Experience;
use App\Models\Project;
use App\Models\SocialLink;
use App\Models\Technology;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicEndpointsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper to return public headers, simulating a public request.
     */
    protected function publicHeaders(): array
    {
        return [
            'Accept' => 'application/json',
        ];
    }

    // --- Public Portfolio Endpoint ---

    public function test_it_returns_complete_portfolio_structure(): void
    {
        AboutMe::factory()->create(['is_active' => true]);
        Experience::factory()->count(2)->create(['is_active' => true]);
        Project::factory()->count(3)->featured()->create(['is_active' => true]);

        $response = $this->withHeaders($this->publicHeaders())
            ->getJson('/api/v1/portfolio');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'about',
                'experiences',
                'projects',
                'technologies',
                'social_links',
            ]);
    }

    public function test_portfolio_endpoint_returns_200_even_without_data(): void
    {
        $this->withHeaders($this->publicHeaders())
            ->getJson('/api/v1/portfolio')
            ->assertStatus(200);
    }

    // --- Public About Endpoint ---

    public function test_about_endpoint_returns_active_record(): void
    {
        AboutMe::factory()->create(['is_active' => false]);
        $active = AboutMe::factory()->create(['is_active' => true]);

        $response = $this->withHeaders($this->publicHeaders())
            ->getJson('/api/v1/about');

        $response->assertStatus(200)
            ->assertJsonPath('id', $active->id);
    }

    public function test_about_endpoint_does_not_expose_inactive_records(): void
    {
        AboutMe::factory()->create(['is_active' => false]);

        $response = $this->withHeaders($this->publicHeaders())
            ->getJson('/api/v1/about');

        $response->assertStatus(200)
            ->assertExactJson([]);
    }

    // --- Public Experiences Endpoint ---

    public function test_experiences_endpoint_returns_only_active_experiences(): void
    {
        Experience::factory()->count(3)->create(['is_active' => true]);
        Experience::factory()->count(2)->inactive()->create();

        $response = $this->withHeaders($this->publicHeaders())
            ->getJson('/api/v1/experiences');

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(3, $data);
    }

    public function test_experiences_endpoint_orders_by_current_role_and_date(): void
    {
        Experience::factory()->create(['is_active' => true, 'is_current' => false, 'start_date' => '2020-01-01']);
        $current = Experience::factory()->current()->create(['is_active' => true, 'start_date' => '2023-01-01']);

        $response = $this->withHeaders($this->publicHeaders())
            ->getJson('/api/v1/experiences');

        $response->assertStatus(200)
            ->assertJsonPath('0.id', $current->id);
    }

    // --- Public Projects Endpoint ---

    public function test_projects_endpoint_returns_active_public_projects_paginated(): void
    {
        Project::factory()->count(5)->create([
            'is_active' => true,
            'status'    => 'completed',
        ]);
        // Privados não devem aparecer
        Project::factory()->private()->create(['is_active' => true]);
        // Inativos não devem aparecer
        Project::factory()->inactive()->create();

        $response = $this->withHeaders($this->publicHeaders())
            ->getJson('/api/v1/projects');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'total', 'per_page', 'current_page']);

        $this->assertEquals(5, $response->json('total'));
    }

    public function test_projects_endpoint_does_not_expose_private_projects(): void
    {
        $private = Project::factory()->private()->create(['is_active' => true]);

        $response = $this->withHeaders($this->publicHeaders())
            ->getJson('/api/v1/projects');

        $ids = collect($response->json('data'))->pluck('id');
        $this->assertNotContains($private->id, $ids);
    }

    public function test_projects_endpoint_respects_custom_per_page_up_to_max_limit(): void
    {
        Project::factory()->count(10)->create(['is_active' => true, 'status' => 'completed']);

        // per_page=5 deve ser respeitado
        $this->withHeaders($this->publicHeaders())
            ->getJson('/api/v1/projects?per_page=5')
            ->assertStatus(200)
            ->assertJsonPath('per_page', 5);

        // per_page acima do máximo (50) deve ser limitado
        $response = $this->withHeaders($this->publicHeaders())
            ->getJson('/api/v1/projects?per_page=999');

        $this->assertLessThanOrEqual(50, $response->json('per_page'));
    }

    public function test_projects_endpoint_returns_nested_technologies(): void
    {
        $tech    = Technology::factory()->create(['is_active' => true]);
        $project = Project::factory()->create(['is_active' => true, 'status' => 'completed']);
        $project->technologies()->attach($tech->id, ['is_primary' => true]);

        $response = $this->withHeaders($this->publicHeaders())
            ->getJson('/api/v1/projects');

        $projectData = collect($response->json('data'))->firstWhere('id', $project->id);
        $this->assertNotEmpty($projectData['technologies']);
    }

    // --- Public Project by Slug ---

    public function test_project_by_slug_returns_correct_project(): void
    {
        $project = Project::factory()->create([
            'slug'      => 'meu-projeto',
            'is_active' => true,
            'status'    => 'completed',
        ]);

        $this->withHeaders($this->publicHeaders())
            ->getJson('/api/v1/projects/meu-projeto')
            ->assertStatus(200)
            ->assertJsonPath('id', $project->id)
            ->assertJsonPath('slug', 'meu-projeto');
    }

    public function test_project_by_slug_returns_404_for_non_existent_project(): void
    {
        $this->withHeaders($this->publicHeaders())
            ->getJson('/api/v1/projects/slug-que-nao-existe')
            ->assertStatus(404);
    }

    public function test_project_by_slug_returns_404_for_private_project(): void
    {
        Project::factory()->private()->create([
            'slug'      => 'projeto-privado',
            'is_active' => true,
        ]);

        $this->withHeaders($this->publicHeaders())
            ->getJson('/api/v1/projects/projeto-privado')
            ->assertStatus(404);
    }

    public function test_project_by_slug_returns_404_for_inactive_project(): void
    {
        Project::factory()->inactive()->create([
            'slug' => 'projeto-inativo',
        ]);

        $this->withHeaders($this->publicHeaders())
            ->getJson('/api/v1/projects/projeto-inativo')
            ->assertStatus(404);
    }

    // --- Public Technologies Endpoint ---

    public function test_technologies_endpoint_returns_only_active_technologies(): void
    {
        Technology::factory()->count(4)->create(['is_active' => true]);
        Technology::factory()->count(2)->inactive()->create();

        $response = $this->withHeaders($this->publicHeaders())
            ->getJson('/api/v1/technologies');

        $response->assertStatus(200);
        $this->assertCount(4, $response->json());
    }

    // --- Public Social Links Endpoint ---

    public function test_social_links_endpoint_returns_only_active_links_ordered(): void
    {
        SocialLink::factory()->create(['is_active' => true, 'sort_order' => 2]);
        SocialLink::factory()->create(['is_active' => true, 'sort_order' => 1]);
        SocialLink::factory()->inactive()->create();

        $response = $this->withHeaders($this->publicHeaders())
            ->getJson('/api/v1/social-links');

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(2, $data);
        // Ordenação por sort_order
        $this->assertLessThanOrEqual($data[1]['sort_order'], $data[0]['sort_order']);
    }

    // --- Public Contact Form ---

    public function test_contact_form_submits_valid_data(): void
    {
        $response = $this->withHeaders($this->publicHeaders())
            ->postJson('/api/v1/contact', [
                'name'    => 'João Silva',
                'email'   => 'joao@example.com',
                'subject' => 'Oportunidade de trabalho',
                'message' => 'Olá! Gostaria de conversar sobre uma vaga.',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['message']);

        $this->assertDatabaseHas('contacts', [
            'email'   => 'joao@example.com',
            'subject' => 'Oportunidade de trabalho',
        ]);
    }

    public function test_contact_form_rejects_incomplete_data(): void
    {
        $this->withHeaders($this->publicHeaders())
            ->postJson('/api/v1/contact', [
                'name' => 'João',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'subject', 'message']);
    }

    public function test_contact_form_rejects_invalid_email(): void
    {
        $this->withHeaders($this->publicHeaders())
            ->postJson('/api/v1/contact', [
                'name'    => 'Fulano',
                'email'   => 'email-invalido',
                'subject' => 'Assunto teste aqui',
                'message' => 'Mensagem de teste bem detalhada.',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_contact_form_detects_honeypot_and_blocks_bot(): void
    {
        $this->withHeaders($this->publicHeaders())
            ->postJson('/api/v1/contact', [
                'name'    => 'Bot',
                'email'   => 'bot@spam.com',
                'subject' => 'Assunto de spam',
                'message' => 'Mensagem de spam automatizada.',
                'website' => 'http://spam.com', // honeypot preenchido = bot
            ])
            ->assertStatus(422);
    }
}
