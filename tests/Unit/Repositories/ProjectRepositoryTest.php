<?php

namespace Tests\Unit\Repositories;

use App\Models\Project;
use App\Models\Technology;
use App\Repositories\Eloquent\ProjectRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected ProjectRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new ProjectRepository(new Project());
    }

    public function test_find_by_id_returns_existing_project(): void
    {
        $project = Project::factory()->create();

        $found = $this->repo->findById($project->id);

        $this->assertNotNull($found);
        $this->assertEquals($project->id, $found->id);
    }

    public function test_find_by_id_returns_null_for_non_existent_id(): void
    {
        $this->assertNull($this->repo->findById(999999));
    }

    public function test_find_or_fail_throws_exception_for_non_existent_id(): void
    {
        $this->expectException(ModelNotFoundException::class);
        $this->repo->findOrFail(999999);
    }

    public function test_create_persists_project_correctly(): void
    {
        $data = [
            'title'       => 'Repo Test Project',
            'slug'        => 'repo-test-project',
            'description' => str_repeat('Descrição. ', 10),
            'is_active'   => true,
            'status'      => 'completed',
        ];

        $project = $this->repo->create($data);

        $this->assertNotNull($project->id);
        $this->assertDatabaseHas('projects', ['slug' => 'repo-test-project']);
    }

    public function test_update_modifies_project_and_returns_updated_instance(): void
    {
        $project = Project::factory()->create(['title' => 'Antigo']);

        $updated = $this->repo->update($project, ['title' => 'Novo Título']);

        $this->assertEquals('Novo Título', $updated->title);
        $this->assertDatabaseHas('projects', [
            'id'    => $project->id,
            'title' => 'Novo Título'
        ]);
    }

    public function test_delete_performs_soft_delete(): void
    {
        $project = Project::factory()->create();

        $this->repo->delete($project);

        $this->assertSoftDeleted('projects', ['id' => $project->id]);
    }

    public function test_find_by_slug_returns_project(): void
    {
        $project = Project::factory()->create(['slug' => 'unique-slug-test']);

        $found = $this->repo->findBySlug('unique-slug-test');

        $this->assertNotNull($found);
        $this->assertEquals($project->id, $found->id);
    }

    public function test_find_by_slug_returns_null_for_non_existent_slug(): void
    {
        $this->assertNull($this->repo->findBySlug('nao-existe'));
    }

    public function test_get_featured_returns_only_active_featured_projects(): void
    {
        Project::factory()->count(2)->featured()->create(['is_active' => true]);
        Project::factory()->create(['is_featured' => false, 'is_active' => true]);
        Project::factory()->inactive()->create(['is_featured' => true]);

        $featured = $this->repo->getFeatured();

        $this->assertCount(2, $featured);
    }

    public function test_get_public_paginated_excludes_private_and_inactive_projects(): void
    {
        Project::factory()->count(3)->create(['is_active' => true, 'status' => 'completed']);
        Project::factory()->private()->create(['is_active' => true]);
        Project::factory()->inactive()->create();

        $result = $this->repo->getPublicPaginated(10);

        $this->assertEquals(3, $result->total());
    }

    public function test_sync_technologies_attaches_correct_pivot_data(): void
    {
        $project = Project::factory()->create();
        $tech1   = Technology::factory()->create();
        $tech2   = Technology::factory()->create();

        $this->repo->syncTechnologies(
            $project,
            [$tech1->id, $tech2->id],
            [$tech1->id]
        );

        $this->assertDatabaseHas('project_technology', [
            'project_id'    => $project->id,
            'technology_id' => $tech1->id,
            'is_primary'    => true,
        ]);

        $this->assertDatabaseHas('project_technology', [
            'project_id'    => $project->id,
            'technology_id' => $tech2->id,
            'is_primary'    => false,
        ]);
    }

    public function test_sync_technologies_removes_old_technologies(): void
    {
        $project = Project::factory()->create();
        $tech1   = Technology::factory()->create();
        $tech2   = Technology::factory()->create();

        // Vincula ambas inicialmente
        $this->repo->syncTechnologies($project, [$tech1->id, $tech2->id]);

        // Atualiza para somente tech1
        $this->repo->syncTechnologies($project, [$tech1->id]);

        $this->assertDatabaseMissing('project_technology', [
            'project_id'    => $project->id,
            'technology_id' => $tech2->id,
        ]);
    }
}
