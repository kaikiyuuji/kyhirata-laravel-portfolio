<?php

namespace Tests\Feature\Admin;

use App\Models\SocialLink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SocialLinkTest extends TestCase
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
            'platform' => 'GitHub',
            'url' => 'https://github.com/testuser',
            'icon' => 'fab fa-github',
            'is_visible' => 1,
            'order' => 0,
        ], $overrides);
    }

    // --- CRUD ---

    public function test_index_returns_200(): void
    {
        $this->actingAsAdmin();

        $response = $this->get(route('admin.social-links.index'));

        $response->assertStatus(200);
    }

    public function test_create_returns_200(): void
    {
        $this->actingAsAdmin();

        $response = $this->get(route('admin.social-links.create'));

        $response->assertStatus(200);
    }

    public function test_store_with_valid_data_creates_record(): void
    {
        $this->actingAsAdmin();

        $response = $this->post(route('admin.social-links.store'), $this->validData());

        $response->assertRedirect(route('admin.social-links.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('social_links', ['platform' => 'GitHub']);
    }

    public function test_store_with_invalid_data_returns_errors(): void
    {
        $this->actingAsAdmin();

        $response = $this->post(route('admin.social-links.store'), []);

        $response->assertSessionHasErrors(['platform', 'url', 'icon', 'is_visible']);
        $this->assertDatabaseCount('social_links', 0);
    }

    public function test_edit_returns_200(): void
    {
        $this->actingAsAdmin();
        $link = SocialLink::factory()->create();

        $response = $this->get(route('admin.social-links.edit', $link->id));

        $response->assertStatus(200);
    }

    public function test_update_with_valid_data_changes_record(): void
    {
        $this->actingAsAdmin();
        $link = SocialLink::factory()->create(['platform' => 'Old']);

        $response = $this->put(
            route('admin.social-links.update', $link->id),
            $this->validData(['platform' => 'LinkedIn'])
        );

        $response->assertRedirect(route('admin.social-links.index'));
        $this->assertDatabaseHas('social_links', ['id' => $link->id, 'platform' => 'LinkedIn']);
    }

    public function test_update_with_invalid_data_returns_errors(): void
    {
        $this->actingAsAdmin();
        $link = SocialLink::factory()->create(['platform' => 'Keep']);

        $response = $this->put(
            route('admin.social-links.update', $link->id),
            $this->validData(['url' => 'not-a-url'])
        );

        $response->assertSessionHasErrors(['url']);
        $this->assertDatabaseHas('social_links', ['id' => $link->id, 'platform' => 'Keep']);
    }

    public function test_destroy_removes_record(): void
    {
        $this->actingAsAdmin();
        $link = SocialLink::factory()->create();

        $response = $this->delete(route('admin.social-links.destroy', $link->id));

        $response->assertRedirect(route('admin.social-links.index'));
        $this->assertDatabaseMissing('social_links', ['id' => $link->id]);
    }

    // --- Validação ---

    public function test_url_must_be_valid(): void
    {
        $this->actingAsAdmin();

        $response = $this->post(route('admin.social-links.store'), $this->validData([
            'url' => 'invalid-url',
        ]));

        $response->assertSessionHasErrors(['url']);
    }

    public function test_platform_max_length_validation(): void
    {
        $this->actingAsAdmin();

        $response = $this->post(route('admin.social-links.store'), $this->validData([
            'platform' => str_repeat('A', 51),
        ]));

        $response->assertSessionHasErrors(['platform']);
    }
}
