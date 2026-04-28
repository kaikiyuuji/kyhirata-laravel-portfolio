<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LogoutTest extends TestCase
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

    public function test_it_ends_authenticated_admin_session(): void
    {
        [$admin, $token] = $this->actingAsAdmin();

        $response = $this->withToken($token)
            ->postJson('/api/v1/admin/logout');

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Sessão encerrada.');

        // Token deve ser inválido após logout
        $this->withToken($token)
            ->getJson('/api/v1/admin/me')
            ->assertStatus(401);
    }

    public function test_it_blocks_logout_without_authentication(): void
    {
        $response = $this->postJson('/api/v1/admin/logout');

        $response->assertStatus(401);
    }
}
