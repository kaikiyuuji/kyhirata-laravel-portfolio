<?php

namespace Tests\Feature\Admin;

use App\Models\AboutMe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AboutMeTest extends TestCase
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
            'name' => 'Kaiki Hirata',
            'title' => 'Dev Full Stack',
            'bio' => 'Desenvolvedor apaixonado por tecnologia.',
            'email' => 'kaiki@portfolio.test',
            'location' => 'São Paulo, BR',
            'is_available_for_work' => 1,
        ], $overrides);
    }

    public function test_edit_returns_200_with_current_data(): void
    {
        $this->actingAsAdmin();

        $response = $this->get(route('admin.about.edit'));

        $response->assertStatus(200);
    }

    public function test_update_with_valid_data_changes_record(): void
    {
        $this->actingAsAdmin();

        $response = $this->put(route('admin.about.update'), $this->validData());

        $response->assertRedirect(route('admin.about.edit'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('about_me', ['name' => 'Kaiki Hirata']);
    }

    public function test_update_with_invalid_data_returns_errors(): void
    {
        $this->actingAsAdmin();

        $response = $this->put(route('admin.about.update'), $this->validData(['name' => '']));

        $response->assertSessionHasErrors(['name']);
    }

    public function test_no_create_route_exists_for_about_me(): void
    {
        $this->actingAsAdmin();

        $response = $this->get('/admin/about/create');

        $response->assertStatus(404);
    }

    public function test_no_destroy_route_exists_for_about_me(): void
    {
        $this->actingAsAdmin();

        $response = $this->delete('/admin/about');

        $response->assertStatus(405);
    }

    // --- Validação ---

    public function test_name_max_length_validation(): void
    {
        $this->actingAsAdmin();

        $response = $this->put(route('admin.about.update'), $this->validData([
            'name' => str_repeat('A', 101),
        ]));

        $response->assertSessionHasErrors(['name']);
    }

    public function test_email_must_be_valid(): void
    {
        $this->actingAsAdmin();

        $response = $this->put(route('admin.about.update'), $this->validData([
            'email' => 'invalid-email',
        ]));

        $response->assertSessionHasErrors(['email']);
    }

    public function test_bio_max_length_validation(): void
    {
        $this->actingAsAdmin();

        $response = $this->put(route('admin.about.update'), $this->validData([
            'bio' => str_repeat('A', 3001),
        ]));

        $response->assertSessionHasErrors(['bio']);
    }
}
