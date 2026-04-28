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
     * Helper: cria e autentica o admin.
     */
    private function createAndAuthenticateAdmin(): User
    {
        $admin = User::factory()->create([
            'email' => config('admin.email'),
            'password' => Hash::make('password'),
        ]);

        $this->actingAs($admin);

        return $admin;
    }

    public function test_authenticated_admin_can_logout(): void
    {
        $this->createAndAuthenticateAdmin();

        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_logout_requires_authentication(): void
    {
        $response = $this->post('/logout');

        $response->assertRedirect('/login');
    }
}
