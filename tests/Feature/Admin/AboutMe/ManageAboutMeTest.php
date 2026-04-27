<?php

namespace Tests\Feature\Admin\AboutMe;

use App\Models\AboutMe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ManageAboutMeTest extends TestCase
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

    public function test_it_allows_admin_to_create_about_me_record(): void
    {
        [$admin, $token] = $this->actingAsAdmin();

        $response = $this->withToken($token)
            ->postJson('/api/v1/admin/about-me', [
                'title'               => 'Backend Developer',
                'description'         => str_repeat('Descrição. ', 20),
                'years_of_experience' => 5,
                'availability_status' => 'open',
                'is_active'           => true,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('title', 'Backend Developer');
    }

    public function test_it_deactivates_other_records_when_activating_a_new_one(): void
    {
        [$admin, $token] = $this->actingAsAdmin();

        $existing = AboutMe::factory()->create(['is_active' => true]);

        $this->withToken($token)
            ->postJson('/api/v1/admin/about-me', [
                'title'               => 'Novo Sobre Mim Ativo',
                'description'         => str_repeat('Bio. ', 20),
                'years_of_experience' => 3,
                'is_active'           => true,
            ])
            ->assertStatus(201);

        // O registro anterior deve estar inativo
        $this->assertDatabaseHas('about_mes', [
            'id'        => $existing->id,
            'is_active' => false,
        ]);
    }

    public function test_it_allows_admin_to_list_records(): void
    {
        [$admin, $token] = $this->actingAsAdmin();
        AboutMe::factory()->count(2)->create();

        $this->withToken($token)
            ->getJson('/api/v1/admin/about-me')
            ->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    public function test_it_allows_admin_to_soft_delete_and_restore_record(): void
    {
        [$admin, $token] = $this->actingAsAdmin();
        $record = AboutMe::factory()->create();

        // Deletar
        $this->withToken($token)
            ->deleteJson("/api/v1/admin/about-me/{$record->id}")
            ->assertStatus(204);

        $this->assertSoftDeleted('about_mes', ['id' => $record->id]);

        // Restaurar
        $this->withToken($token)
            ->postJson("/api/v1/admin/about-me/{$record->id}/restore")
            ->assertStatus(200);

        $this->assertDatabaseHas('about_mes', [
            'id'         => $record->id,
            'deleted_at' => null,
        ]);
    }

    public function test_it_validates_required_fields_on_creation(): void
    {
        [$admin, $token] = $this->actingAsAdmin();

        $this->withToken($token)
            ->postJson('/api/v1/admin/about-me', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'description', 'years_of_experience']);
    }

    public function test_it_rejects_invalid_availability_status(): void
    {
        [$admin, $token] = $this->actingAsAdmin();

        $this->withToken($token)
            ->postJson('/api/v1/admin/about-me', [
                'title'               => 'Dev',
                'description'         => str_repeat('Bio. ', 20),
                'years_of_experience' => 3,
                'availability_status' => 'DISPONIVEL', // valor inválido
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['availability_status']);
    }
}
