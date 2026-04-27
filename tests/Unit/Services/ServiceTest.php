<?php

namespace Tests\Unit\Services;

use App\Models\AboutMe;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Technology;
use App\Repositories\Eloquent\ProjectRepository;
use App\Services\AboutMeService;
use App\Services\ExperienceService;
use App\Services\ProjectService;
use App\Services\TechnologyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    // ─────────────────────────────────────────────────────────────────
    // AboutMeService Tests
    // ─────────────────────────────────────────────────────────────────

    public function test_about_me_service_get_active_returns_only_active_record(): void
    {
        $service = new AboutMeService();
        AboutMe::factory()->create(['is_active' => false]);
        $active = AboutMe::factory()->create(['is_active' => true]);

        $result = $service->getActive();

        $this->assertNotNull($result);
        $this->assertEquals($active->id, $result->id);
    }

    public function test_about_me_service_create_deactivates_previous_records_when_creating_active(): void
    {
        $service = new AboutMeService();
        $old = AboutMe::factory()->create(['is_active' => true]);

        $service->create([
            'title'               => 'Novo',
            'description'         => str_repeat('Bio. ', 15),
            'years_of_experience' => 3,
            'is_active'           => true,
        ]);

        $this->assertFalse(AboutMe::find($old->id)->is_active);
    }

    public function test_about_me_service_create_does_not_deactivate_others_if_new_is_not_active(): void
    {
        $service = new AboutMeService();
        $old = AboutMe::factory()->create(['is_active' => true]);

        $service->create([
            'title'               => 'Rascunho',
            'description'         => str_repeat('Bio. ', 15),
            'years_of_experience' => 3,
            'is_active'           => false,
        ]);

        $this->assertTrue(AboutMe::find($old->id)->is_active);
    }

    public function test_about_me_service_update_maintains_atomicity_in_transaction(): void
    {
        $service = new AboutMeService();
        $record = AboutMe::factory()->create(['is_active' => true]);
        $other  = AboutMe::factory()->create(['is_active' => true]);

        $updated = $service->update($record, [
            'title'     => 'Atualizado',
            'is_active' => true,
        ]);

        $this->assertEquals('Atualizado', $updated->title);
        // Outro registro deve ter sido desativado
        $this->assertFalse(AboutMe::find($other->id)->is_active);
    }

    public function test_about_me_service_delete_performs_soft_delete(): void
    {
        $service = new AboutMeService();
        $record = AboutMe::factory()->create();

        $service->delete($record);

        $this->assertSoftDeleted('about_mes', ['id' => $record->id]);
    }

    public function test_about_me_service_restore_recovers_deleted_record(): void
    {
        $service = new AboutMeService();
        $record = AboutMe::factory()->create();
        $record->delete();

        $restored = $service->restore($record->id);

        $this->assertNull($restored->deleted_at);
        $this->assertDatabaseHas('about_mes', ['id' => $record->id, 'deleted_at' => null]);
    }

    // ─────────────────────────────────────────────────────────────────
    // ExperienceService Tests
    // ─────────────────────────────────────────────────────────────────

    public function test_experience_service_get_active_returns_only_active_experiences_ordered(): void
    {
        $service = new ExperienceService();
        Experience::factory()->count(2)->create(['is_active' => true]);
        Experience::factory()->inactive()->create();

        $result = $service->getActive();

        $this->assertCount(2, $result);
    }

    public function test_experience_service_create_ensures_only_one_current_experience(): void
    {
        $service = new ExperienceService();
        $first = Experience::factory()->current()->create();

        $service->create([
            'company'         => 'Nova Empresa',
            'position'        => 'Dev',
            'employment_type' => 'full_time',
            'start_date'      => '2024-01-01',
            'is_current'      => true,
            'is_active'       => true,
        ]);

        $this->assertFalse(Experience::find($first->id)->is_current);
    }

    public function test_experience_service_create_with_is_current_true_nullifies_end_date(): void
    {
        $service = new ExperienceService();
        $exp = $service->create([
            'company'         => 'Empresa Atual',
            'position'        => 'Dev',
            'employment_type' => 'full_time',
            'start_date'      => '2024-01-01',
            'end_date'        => '2025-01-01',
            'is_current'      => true,
            'is_active'       => true,
        ]);

        $this->assertNull($exp->end_date);
    }

    // ─────────────────────────────────────────────────────────────────
    // TechnologyService Tests
    // ─────────────────────────────────────────────────────────────────

    public function test_technology_service_create_generates_slug_automatically(): void
    {
        $service = new TechnologyService();
        $tech = $service->create([
            'name'      => 'Vue JS',
            'category'  => 'frontend',
            'is_active' => true,
        ]);

        $this->assertEquals('vue-js', $tech->slug);
    }

    public function test_technology_service_update_regenerates_slug_when_changing_name(): void
    {
        $service = new TechnologyService();
        $tech = Technology::factory()->create(['name' => 'React', 'slug' => 'react']);

        $updated = $service->update($tech, ['name' => 'React Native']);

        $this->assertEquals('react-native', $updated->slug);
    }

    public function test_technology_service_delete_detaches_technology_from_projects_before_deleting(): void
    {
        $service = new TechnologyService();
        $tech    = Technology::factory()->create();
        $project = Project::factory()->create();
        $project->technologies()->attach($tech->id, ['is_primary' => false]);

        $service->delete($tech);

        $this->assertDatabaseMissing('technologies', ['id' => $tech->id]);
        $this->assertDatabaseMissing('project_technology', ['technology_id' => $tech->id]);
    }

    // ─────────────────────────────────────────────────────────────────
    // ProjectService Tests
    // ─────────────────────────────────────────────────────────────────

    public function test_project_service_create_generates_unique_slug_from_title(): void
    {
        $repo    = new ProjectRepository(new Project());
        $service = new ProjectService($repo);

        $project = $service->create([
            'title'       => 'Meu Projeto Incrível',
            'description' => str_repeat('Descrição. ', 15),
            'is_active'   => true,
            'status'      => 'completed',
        ]);

        $this->assertEquals('meu-projeto-incrivel', $project->slug);
    }

    public function test_project_service_creates_unique_slugs_for_identical_titles(): void
    {
        $repo    = new ProjectRepository(new Project());
        $service = new ProjectService($repo);

        $p1 = $service->create([
            'title'       => 'Projeto Duplicado',
            'description' => str_repeat('Descrição. ', 15),
            'is_active'   => true,
            'status'      => 'completed',
        ]);

        $p2 = $service->create([
            'title'       => 'Projeto Duplicado',
            'description' => str_repeat('Descrição. ', 15),
            'is_active'   => true,
            'status'      => 'completed',
        ]);

        $this->assertNotEquals($p1->slug, $p2->slug);
        $this->assertEquals('projeto-duplicado-1', $p2->slug);
    }

    public function test_project_service_find_public_by_slug_returns_null_for_private_project(): void
    {
        $repo    = new ProjectRepository(new Project());
        $service = new ProjectService($repo);

        Project::factory()->private()->create([
            'slug'      => 'secreto',
            'is_active' => true,
        ]);

        $result = $service->findPublicBySlug('secreto');

        $this->assertNull($result);
    }

    public function test_project_service_find_public_by_slug_returns_null_for_inactive_project(): void
    {
        $repo    = new ProjectRepository(new Project());
        $service = new ProjectService($repo);

        Project::factory()->inactive()->create(['slug' => 'inativo']);

        $this->assertNull($service->findPublicBySlug('inativo'));
    }

    public function test_project_service_toggle_project_button_inverts_state(): void
    {
        $repo    = new ProjectRepository(new Project());
        $service = new ProjectService($repo);

        $project = Project::factory()->create(['show_project_button' => true]);

        $result = $service->toggleProjectButton($project);

        $this->assertFalse($result->show_project_button);

        // Segundo toggle
        $result2 = $service->toggleProjectButton($result);
        $this->assertTrue($result2->show_project_button);
    }

    public function test_project_service_assert_technologies_exist_throws_exception_for_invalid_ids(): void
    {
        $repo    = new ProjectRepository(new Project());
        $service = new ProjectService($repo);

        $this->expectException(InvalidArgumentException::class);

        $service->create([
            'title'          => 'Projeto com Tech Inválida',
            'description'    => str_repeat('Desc. ', 15),
            'technology_ids' => [99999],
            'is_active'      => true,
            'status'         => 'completed',
        ]);
    }
}
