<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminMeTest extends TestCase
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

    public function test_it_returns_authenticated_admin_data(): void
    {
        [$admin, $token] = $this->actingAsAdmin();

        $response = $this->withToken($token)
            ->getJson('/api/v1/admin/me');

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'email', 'is_admin'])
            ->assertJsonPath('id', $admin->id)
            ->assertJsonMissingPath('password'); // senha NUNCA exposta
    }

    public function test_it_blocks_access_without_token(): void
    {
        $response = $this->getJson('/api/v1/admin/me');

        $response->assertStatus(401);
    }
}
