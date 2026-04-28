<?php

namespace Tests\Feature\Security;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class IdorTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::factory()->create([
            'email' => config('admin.email'),
            'password' => Hash::make('password'),
        ]);
    }

    private function createNonAdmin(): User
    {
        return User::factory()->create([
            'email' => 'regular@test.com',
            'password' => Hash::make('password'),
        ]);
    }

    public function test_non_admin_cannot_edit_existing_project(): void
    {
        $user = $this->createNonAdmin();
        $project = Project::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.projects.edit', $project->id));

        // AdminOnly middleware redirects to /
        $response->assertRedirect('/');
    }

    public function test_non_admin_cannot_update_existing_project(): void
    {
        $user = $this->createNonAdmin();
        $project = Project::factory()->create(['title' => 'Original']);

        $response = $this->actingAs($user)->put(route('admin.projects.update', $project->id), [
            'title' => 'Hacked',
            'description' => 'Hacked description.',
            'show_project_button' => 1,
            'is_visible' => 1,
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('projects', ['id' => $project->id, 'title' => 'Original']);
    }

    public function test_non_admin_cannot_delete_existing_project(): void
    {
        $user = $this->createNonAdmin();
        $project = Project::factory()->create();

        $response = $this->actingAs($user)->delete(route('admin.projects.destroy', $project->id));

        $response->assertRedirect('/');
        $this->assertDatabaseHas('projects', ['id' => $project->id]);
    }

    public function test_admin_gets_404_for_nonexistent_project_edit(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('admin.projects.edit', 99999));

        // Repository findById should throw ModelNotFoundException -> 404
        $response->assertStatus(404);
    }

    public function test_admin_gets_404_for_nonexistent_project_update(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->put(route('admin.projects.update', 99999), [
            'title' => 'Ghost',
            'description' => 'Description.',
            'show_project_button' => 1,
            'is_visible' => 1,
        ]);

        $response->assertStatus(404);
    }

    public function test_admin_gets_404_for_nonexistent_project_delete(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->delete(route('admin.projects.destroy', 99999));

        $response->assertStatus(404);
    }
}
