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

    private function actingAsAdmin(): User
    {
        $admin = User::factory()->create([
            'email' => config('admin.email'),
            'password' => Hash::make('password'),
        ]);
        $this->actingAs($admin);

        return $admin;
    }

    private function validData(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Projeto Teste',
            'description' => 'Descrição completa do projeto.',
            'github_url' => 'https://github.com/user/repo',
            'demo_url' => 'https://demo.example.com',
            'show_project_button' => 1,
            'is_visible' => 1,
            'order' => 0,
        ], $overrides);
    }

    // --- CRUD ---

    public function test_index_returns_200_for_admin(): void
    {
        $this->actingAsAdmin();

        $response = $this->get(route('admin.projects.index'));

        $response->assertStatus(200);
    }

    public function test_create_returns_200(): void
    {
        $this->actingAsAdmin();

        $response = $this->get(route('admin.projects.create'));

        $response->assertStatus(200);
    }

    public function test_store_with_valid_data_creates_record(): void
    {
        $this->actingAsAdmin();

        $response = $this->post(route('admin.projects.store'), $this->validData());

        $response->assertRedirect(route('admin.projects.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('projects', ['title' => 'Projeto Teste']);
    }

    public function test_store_with_invalid_data_returns_errors(): void
    {
        $this->actingAsAdmin();

        $response = $this->post(route('admin.projects.store'), []);

        $response->assertSessionHasErrors(['title', 'description', 'show_project_button', 'is_visible']);
        $this->assertDatabaseCount('projects', 0);
    }

    public function test_edit_returns_200(): void
    {
        $this->actingAsAdmin();
        $project = Project::factory()->create();

        $response = $this->get(route('admin.projects.edit', $project->id));

        $response->assertStatus(200);
    }

    public function test_update_with_valid_data_changes_record(): void
    {
        $this->actingAsAdmin();
        $project = Project::factory()->create(['title' => 'Old Title']);

        $response = $this->put(
            route('admin.projects.update', $project->id),
            $this->validData(['title' => 'New Title'])
        );

        $response->assertRedirect(route('admin.projects.index'));
        $this->assertDatabaseHas('projects', ['id' => $project->id, 'title' => 'New Title']);
    }

    public function test_update_with_invalid_data_returns_errors(): void
    {
        $this->actingAsAdmin();
        $project = Project::factory()->create(['title' => 'Keep This']);

        $response = $this->put(
            route('admin.projects.update', $project->id),
            $this->validData(['title' => ''])
        );

        $response->assertSessionHasErrors(['title']);
        $this->assertDatabaseHas('projects', ['id' => $project->id, 'title' => 'Keep This']);
    }

    public function test_destroy_removes_record(): void
    {
        $this->actingAsAdmin();
        $project = Project::factory()->create();

        $response = $this->delete(route('admin.projects.destroy', $project->id));

        $response->assertRedirect(route('admin.projects.index'));
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    // --- Toggle ---

    public function test_toggle_inverts_show_project_button(): void
    {
        $this->actingAsAdmin();
        $project = Project::factory()->create(['show_project_button' => false]);

        $this->post(route('admin.projects.toggle', $project->id));

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'show_project_button' => true,
        ]);
    }

    // --- Technology pivot ---

    public function test_store_with_valid_technology_ids_creates_pivot_entries(): void
    {
        $this->actingAsAdmin();
        $techs = Technology::factory()->count(2)->create();

        $response = $this->post(route('admin.projects.store'), $this->validData([
            'technology_ids' => $techs->pluck('id')->toArray(),
        ]));

        $response->assertRedirect(route('admin.projects.index'));
        $project = Project::first();
        $this->assertCount(2, $project->technologies);
    }

    public function test_store_with_invalid_technology_ids_returns_error(): void
    {
        $this->actingAsAdmin();

        $response = $this->post(route('admin.projects.store'), $this->validData([
            'technology_ids' => [999, 888],
        ]));

        $response->assertSessionHasErrors(['technology_ids.0']);
        $this->assertDatabaseCount('projects', 0);
    }

    // --- Visibilidade pública ---

    public function test_invisible_project_does_not_appear_on_public_page(): void
    {
        Project::factory()->create(['is_visible' => false, 'title' => 'Hidden Project']);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('Hidden Project');
    }

    public function test_visible_project_appears_on_public_page(): void
    {
        Project::factory()->create(['is_visible' => true, 'title' => 'Visible Project']);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Visible Project');
    }

    // --- Validação ---

    public function test_github_url_must_start_with_github(): void
    {
        $this->actingAsAdmin();

        $response = $this->post(route('admin.projects.store'), $this->validData([
            'github_url' => 'https://gitlab.com/user/repo',
        ]));

        $response->assertSessionHasErrors(['github_url']);
    }
}
