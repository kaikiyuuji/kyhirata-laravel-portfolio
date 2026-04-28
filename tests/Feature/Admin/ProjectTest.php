<?php

namespace Tests\Feature\Admin;

use App\Models\Project;
use App\Models\Technology;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Creates and authenticates an admin, returning the user and the token.
     */
    protected function actingAsAdmin(): array
    {
        $admin = User::factory()->admin()->create([
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/api/v1/admin/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        return [$admin, $response->json('token')];
    }

    /**
     * Creates a regular user, returning the user and a token.
     */
    protected function actingAsUser(): array
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'password' => Hash::make('password'),
        ]);

        $token = method_exists($user, 'createToken') 
            ? $user->createToken('test-token')->plainTextToken 
            : 'dummy-token';

        return [$user, $token];
    }

    // --- Index ---

    public function test_admin_can_list_all_projects(): void
    {
        [$admin, $token] = $this->actingAsAdmin();
        Project::factory()->count(3)->create();

        $response = $this->withToken($token)
            ->getJson('/api/v1/admin/projects');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'total', 'per_page']);
    }

    public function test_it_blocks_listing_without_authentication(): void
    {
        $this->getJson('/api/v1/admin/projects')->assertStatus(401);
    }

    public function test_it_blocks_listing_for_non_admin_users(): void
    {
        [$user, $token] = $this->actingAsUser();

        $this->withToken($token)
            ->getJson('/api/v1/admin/projects')
            ->assertStatus(403);
    }

    // --- Show ---

    public function test_admin_can_view_specific_project(): void
    {
        [$admin, $token] = $this->actingAsAdmin();
        $project = Project::factory()->create();

        $response = $this->withToken($token)
            ->getJson("/api/v1/admin/projects/{$project->id}");

        $response->assertStatus(200)
            ->assertJsonPath('id', $project->id)
            ->assertJsonPath('title', $project->title);
    }

    public function test_it_returns_404_for_non_existent_project(): void
    {
        [$admin, $token] = $this->actingAsAdmin();

        $this->withToken($token)
            ->getJson('/api/v1/admin/projects/999999')
            ->assertStatus(404);
    }

    // --- Store ---

    public function test_admin_can_create_project_with_valid_data(): void
    {
        [$admin, $token] = $this->actingAsAdmin();

        $payload = [
            'title'               => 'Meu Projeto Teste',
            'description'         => str_repeat('Descrição detalhada. ', 10),
            'short_description'   => 'Resumo do projeto.',
            'github_url'          => 'https://github.com/user/repo',
            'show_project_button' => true,
            'is_featured'         => false,
            'is_active'           => true,
            'status'              => 'completed',
        ];

        $response = $this->withToken($token)
            ->postJson('/api/v1/admin/projects', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('title', 'Meu Projeto Teste')
            ->assertJsonStructure(['id', 'slug', 'title', 'github_url']);

        $this->assertDatabaseHas('projects', ['title' => 'Meu Projeto Teste']);
    }

    public function test_admin_can_create_project_with_technologies(): void
    {
        [$admin, $token] = $this->actingAsAdmin();
        $tech1 = Technology::factory()->create();
        $tech2 = Technology::factory()->create();

        $payload = [
            'title'                  => 'Projeto Com Techs',
            'description'            => str_repeat('Descrição. ', 10),
            'technology_ids'         => [$tech1->id, $tech2->id],
            'primary_technology_ids' => [$tech1->id],
            'is_active'              => true,
            'status'                 => 'completed',
        ];

        $response = $this->withToken($token)
            ->postJson('/api/v1/admin/projects', $payload);

        $response->assertStatus(201);

        $projectId = $response->json('id');
        $this->assertDatabaseHas('project_technology', [
            'project_id'    => $projectId,
            'technology_id' => $tech1->id,
            'is_primary'    => true,
        ]);
    }

    public function test_it_rejects_project_without_title(): void
    {
        [$admin, $token] = $this->actingAsAdmin();

        $response = $this->withToken($token)
            ->postJson('/api/v1/admin/projects', [
                'description' => 'Sem título',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_it_rejects_github_url_with_http_protocol(): void
    {
        [$admin, $token] = $this->actingAsAdmin();

        $response = $this->withToken($token)
            ->postJson('/api/v1/admin/projects', [
                'title'       => 'Projeto Inseguro',
                'description' => str_repeat('desc ', 10),
                'github_url'  => 'http://github.com/user/repo', // HTTP não permitido
                'is_active'   => true,
                'status'      => 'completed',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['github_url']);
    }

    public function test_it_rejects_too_short_description(): void
    {
        [$admin, $token] = $this->actingAsAdmin();

        $response = $this->withToken($token)
            ->postJson('/api/v1/admin/projects', [
                'title'       => 'Título OK',
                'description' => 'x',
                'is_active'   => true,
                'status'      => 'completed',
            ]);

        $response->assertStatus(422);
    }

    public function test_it_blocks_creation_without_authentication(): void
    {
        $this->postJson('/api/v1/admin/projects', [
            'title' => 'Hacked Project',
        ])->assertStatus(401);
    }

    // --- Update ---

    public function test_admin_can_update_existing_project(): void
    {
        [$admin, $token] = $this->actingAsAdmin();
        $project = Project::factory()->create();

        $response = $this->withToken($token)
            ->putJson("/api/v1/admin/projects/{$project->id}", [
                'title'       => 'Título Atualizado',
                'description' => $project->description,
                'is_active'   => true,
                'status'      => 'completed',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('title', 'Título Atualizado');

        $this->assertDatabaseHas('projects', [
            'id'    => $project->id,
            'title' => 'Título Atualizado',
        ]);
    }

    public function test_admin_can_update_project_technologies(): void
    {
        [$admin, $token] = $this->actingAsAdmin();
        $project = Project::factory()->create();
        $tech    = Technology::factory()->create();

        $this->withToken($token)
            ->putJson("/api/v1/admin/projects/{$project->id}", [
                'description'    => $project->description,
                'technology_ids' => [$tech->id],
                'is_active'      => true,
                'status'         => 'completed',
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas('project_technology', [
            'project_id'    => $project->id,
            'technology_id' => $tech->id,
        ]);
    }

    public function test_it_blocks_update_for_non_admin_users(): void
    {
        [$user, $token] = $this->actingAsUser();
        $project = Project::factory()->create();

        $this->withToken($token)
            ->putJson("/api/v1/admin/projects/{$project->id}", [
                'title' => 'Hack',
            ])->assertStatus(403);
    }

    // --- Delete ---

    public function test_admin_can_soft_delete_project(): void
    {
        [$admin, $token] = $this->actingAsAdmin();
        $project = Project::factory()->create();

        $this->withToken($token)
            ->deleteJson("/api/v1/admin/projects/{$project->id}")
            ->assertStatus(204);

        $this->assertSoftDeleted('projects', ['id' => $project->id]);
    }

    public function test_it_blocks_deletion_without_authentication(): void
    {
        $project = Project::factory()->create();

        $this->deleteJson("/api/v1/admin/projects/{$project->id}")
            ->assertStatus(401);
    }

    // --- Toggle Button ---

    public function test_admin_can_toggle_show_project_button(): void
    {
        [$admin, $token] = $this->actingAsAdmin();
        $project = Project::factory()->create(['show_project_button' => true]);

        $response = $this->withToken($token)
            ->patchJson("/api/v1/admin/projects/{$project->id}/toggle-button");

        $response->assertStatus(200)
            ->assertJsonPath('show_project_button', false);

        $this->assertDatabaseHas('projects', [
            'id'                  => $project->id,
            'show_project_button' => false,
        ]);

        // Toggle novamente
        $this->withToken($token)
            ->patchJson("/api/v1/admin/projects/{$project->id}/toggle-button")
            ->assertJsonPath('show_project_button', true);
    }

    public function test_it_blocks_toggle_button_without_authentication(): void
    {
        $project = Project::factory()->create();

        $this->patchJson("/api/v1/admin/projects/{$project->id}/toggle-button")
            ->assertStatus(401);
    }
}

