<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_authenticates_admin_with_valid_credentials(): void
    {
        $admin = User::factory()->admin()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('Adm1nP@ss!'),
        ]);

        $response = $this->postJson('/api/v1/admin/login', [
            'email' => 'admin@test.com',
            'password' => 'Adm1nP@ss!',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'expires_at', 'user'])
            ->assertJsonPath('user.id', $admin->id);
    }

    public function test_it_rejects_authentication_with_invalid_credentials(): void
    {
        User::factory()->admin()->create(['email' => 'admin@test.com']);

        $response = $this->postJson('/api/v1/admin/login', [
            'email' => 'admin@test.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_it_returns_generic_error_message_for_non_existent_email_to_prevent_enumeration(): void
    {
        $response = $this->postJson('/api/v1/admin/login', [
            'email' => 'non_existent@test.com',
            'password' => 'any-password',
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('errors.email.0', 'Credenciais inválidas. Verifique e tente novamente.');
    }

    public function test_it_rejects_non_admin_user_even_with_correct_password(): void
    {
        User::factory()->create([
            'email' => 'user@test.com',
            'password' => Hash::make('Adm1nP@ss!'),
            'is_admin' => false,
        ]);

        $response = $this->postJson('/api/v1/admin/login', [
            'email' => 'user@test.com',
            'password' => 'Adm1nP@ss!',
        ]);

        $response->assertStatus(422);
    }

    public function test_it_rejects_inactive_admin_user(): void
    {
        User::factory()->admin()->inactive()->create([
            'email' => 'inactive@test.com',
            'password' => Hash::make('Adm1nP@ss!'),
        ]);

        $response = $this->postJson('/api/v1/admin/login', [
            'email' => 'inactive@test.com',
            'password' => 'Adm1nP@ss!',
        ]);

        $response->assertStatus(422);
    }

    public function test_it_requires_email_and_password_in_the_request_body(): void
    {
        $response = $this->postJson('/api/v1/admin/login', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_it_validates_email_format(): void
    {
        $response = $this->postJson('/api/v1/admin/login', [
            'email' => 'invalid-email',
            'password' => 'some-password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}