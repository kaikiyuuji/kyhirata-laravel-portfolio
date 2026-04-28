<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper: cria o usuário admin no banco.
     */
    private function createAdmin(string $password = 'Adm1nP@ss!'): User
    {
        return User::factory()->create([
            'email' => config('admin.email'),
            'password' => Hash::make($password),
        ]);
    }

    public function test_admin_can_login_with_valid_credentials(): void
    {
        $this->createAdmin();

        $response = $this->post('/login', [
            'email' => config('admin.email'),
            'password' => 'Adm1nP@ss!',
        ]);

        $response->assertRedirect(config('fortify.home'));
        $this->assertAuthenticated();
    }

    public function test_login_with_wrong_password_returns_generic_error(): void
    {
        $this->createAdmin();

        $response = $this->post('/login', [
            'email' => config('admin.email'),
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_login_with_nonexistent_email_returns_same_generic_error(): void
    {
        $response = $this->post('/login', [
            'email' => 'non_existent@test.com',
            'password' => 'any-password',
        ]);

        $response->assertSessionHasErrors(['email']);
        $errors = session('errors')->getBag('default');
        $this->assertEquals(
            'Credenciais inválidas. Verifique e tente novamente.',
            $errors->first('email')
        );
        $this->assertGuest();
    }

    public function test_rate_limiting_blocks_after_too_many_attempts(): void
    {
        $this->createAdmin();
        $throttle = config('fortify.login_throttle', 5);

        for ($i = 0; $i < $throttle; $i++) {
            $this->post('/login', [
                'email' => config('admin.email'),
                'password' => 'wrong-password',
            ]);
        }

        $response = $this->post('/login', [
            'email' => config('admin.email'),
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(429);
    }

    public function test_authenticated_user_is_redirected_away_from_login_page(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/login');

        $response->assertRedirect(config('fortify.home'));
    }

    public function test_non_admin_user_cannot_login_even_with_correct_password(): void
    {
        User::factory()->create([
            'email' => 'regular@test.com',
            'password' => Hash::make('Adm1nP@ss!'),
        ]);

        $response = $this->post('/login', [
            'email' => 'regular@test.com',
            'password' => 'Adm1nP@ss!',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }
}
