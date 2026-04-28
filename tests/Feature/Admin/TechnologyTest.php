<?php

namespace Tests\Feature\Admin;

use App\Models\Technology;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TechnologyTest extends TestCase
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
            'name' => 'Laravel',
            'color' => '#FF2D20',
        ], $overrides);
    }

    // --- CRUD ---

    public function test_index_returns_200(): void
    {
        $this->actingAsAdmin();

        $response = $this->get(route('admin.technologies.index'));

        $response->assertStatus(200);
    }

    public function test_create_returns_200(): void
    {
        $this->actingAsAdmin();

        $response = $this->get(route('admin.technologies.create'));

        $response->assertStatus(200);
    }

    public function test_store_with_valid_data_creates_record(): void
    {
        $this->actingAsAdmin();

        $response = $this->post(route('admin.technologies.store'), $this->validData());

        $response->assertRedirect(route('admin.technologies.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('technologies', ['name' => 'Laravel']);
    }

    public function test_store_with_invalid_data_returns_errors(): void
    {
        $this->actingAsAdmin();

        $response = $this->post(route('admin.technologies.store'), []);

        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseCount('technologies', 0);
    }

    public function test_edit_returns_200(): void
    {
        $this->actingAsAdmin();
        $tech = Technology::factory()->create();

        $response = $this->get(route('admin.technologies.edit', $tech->id));

        $response->assertStatus(200);
    }

    public function test_update_with_valid_data_changes_record(): void
    {
        $this->actingAsAdmin();
        $tech = Technology::factory()->create(['name' => 'Old']);

        $response = $this->put(
            route('admin.technologies.update', $tech->id),
            $this->validData(['name' => 'New'])
        );

        $response->assertRedirect(route('admin.technologies.index'));
        $this->assertDatabaseHas('technologies', ['id' => $tech->id, 'name' => 'New']);
    }

    public function test_update_with_invalid_data_returns_errors(): void
    {
        $this->actingAsAdmin();
        $tech = Technology::factory()->create(['name' => 'Keep']);

        $response = $this->put(
            route('admin.technologies.update', $tech->id),
            $this->validData(['name' => ''])
        );

        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseHas('technologies', ['id' => $tech->id, 'name' => 'Keep']);
    }

    public function test_destroy_removes_record(): void
    {
        $this->actingAsAdmin();
        $tech = Technology::factory()->create();

        $response = $this->delete(route('admin.technologies.destroy', $tech->id));

        $response->assertRedirect(route('admin.technologies.index'));
        $this->assertDatabaseMissing('technologies', ['id' => $tech->id]);
    }

    // --- Validação ---

    public function test_name_must_be_unique_on_store(): void
    {
        $this->actingAsAdmin();
        Technology::factory()->create(['name' => 'Laravel']);

        $response = $this->post(route('admin.technologies.store'), $this->validData(['name' => 'Laravel']));

        $response->assertSessionHasErrors(['name']);
    }

    public function test_name_unique_ignores_own_record_on_update(): void
    {
        $this->actingAsAdmin();
        $tech = Technology::factory()->create(['name' => 'Laravel']);

        $response = $this->put(
            route('admin.technologies.update', $tech->id),
            $this->validData(['name' => 'Laravel'])
        );

        $response->assertRedirect(route('admin.technologies.index'));
        $response->assertSessionHas('success');
    }

    public function test_color_must_be_valid_hex(): void
    {
        $this->actingAsAdmin();

        $response = $this->post(route('admin.technologies.store'), $this->validData([
            'color' => 'not-a-hex',
        ]));

        $response->assertSessionHasErrors(['color']);
    }

    public function test_name_max_length_validation(): void
    {
        $this->actingAsAdmin();

        $response = $this->post(route('admin.technologies.store'), $this->validData([
            'name' => str_repeat('A', 51),
        ]));

        $response->assertSessionHasErrors(['name']);
    }
}
