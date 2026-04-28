<?php

namespace Tests\Feature\Admin;

use App\Models\Experience;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExperienceTest extends TestCase
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
            'company' => 'Empresa Teste',
            'role' => 'Dev Backend',
            'description' => 'Descrição da experiência de trabalho.',
            'started_at' => '2023-01-15',
            'ended_at' => '2024-06-30',
            'is_visible' => 1,
            'order' => 0,
        ], $overrides);
    }

    // --- CRUD ---

    public function test_index_returns_200_for_admin(): void
    {
        $this->actingAsAdmin();

        $response = $this->get(route('admin.experiences.index'));

        $response->assertStatus(200);
    }

    public function test_create_returns_200(): void
    {
        $this->actingAsAdmin();

        $response = $this->get(route('admin.experiences.create'));

        $response->assertStatus(200);
    }

    public function test_store_with_valid_data_creates_record(): void
    {
        $this->actingAsAdmin();

        $response = $this->post(route('admin.experiences.store'), $this->validData());

        $response->assertRedirect(route('admin.experiences.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('experiences', ['company' => 'Empresa Teste']);
    }

    public function test_store_with_invalid_data_returns_errors(): void
    {
        $this->actingAsAdmin();

        $response = $this->post(route('admin.experiences.store'), []);

        $response->assertSessionHasErrors(['company', 'role', 'description', 'started_at', 'is_visible']);
        $this->assertDatabaseCount('experiences', 0);
    }

    public function test_edit_returns_200_with_record_data(): void
    {
        $this->actingAsAdmin();
        $experience = Experience::factory()->create();

        $response = $this->get(route('admin.experiences.edit', $experience->id));

        $response->assertStatus(200);
    }

    public function test_update_with_valid_data_changes_record(): void
    {
        $this->actingAsAdmin();
        $experience = Experience::factory()->create(['company' => 'Old Company']);

        $response = $this->put(
            route('admin.experiences.update', $experience->id),
            $this->validData(['company' => 'New Company'])
        );

        $response->assertRedirect(route('admin.experiences.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('experiences', ['id' => $experience->id, 'company' => 'New Company']);
    }

    public function test_update_with_invalid_data_returns_errors(): void
    {
        $this->actingAsAdmin();
        $experience = Experience::factory()->create(['company' => 'Keep This']);

        $response = $this->put(
            route('admin.experiences.update', $experience->id),
            $this->validData(['company' => ''])
        );

        $response->assertSessionHasErrors(['company']);
        $this->assertDatabaseHas('experiences', ['id' => $experience->id, 'company' => 'Keep This']);
    }

    public function test_destroy_removes_record(): void
    {
        $this->actingAsAdmin();
        $experience = Experience::factory()->create();

        $response = $this->delete(route('admin.experiences.destroy', $experience->id));

        $response->assertRedirect(route('admin.experiences.index'));
        $this->assertDatabaseMissing('experiences', ['id' => $experience->id]);
    }

    // --- Validação ---

    public function test_company_max_length_validation(): void
    {
        $this->actingAsAdmin();

        $response = $this->post(route('admin.experiences.store'), $this->validData([
            'company' => str_repeat('A', 151),
        ]));

        $response->assertSessionHasErrors(['company']);
    }

    public function test_started_at_cannot_be_in_the_future(): void
    {
        $this->actingAsAdmin();

        $response = $this->post(route('admin.experiences.store'), $this->validData([
            'started_at' => now()->addYear()->format('Y-m-d'),
        ]));

        $response->assertSessionHasErrors(['started_at']);
    }

    public function test_ended_at_must_be_after_started_at(): void
    {
        $this->actingAsAdmin();

        $response = $this->post(route('admin.experiences.store'), $this->validData([
            'started_at' => '2024-06-01',
            'ended_at' => '2024-01-01',
        ]));

        $response->assertSessionHasErrors(['ended_at']);
    }
}
