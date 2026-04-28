<?php

namespace Tests\Feature\Security;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::factory()->create([
            'email' => config('admin.email'),
            'password' => Hash::make('password'),
        ]);
    }

    private function createNonAdmin(): User
    {
        return User::factory()->create([
            'email' => 'regular@test.com',
            'password' => Hash::make('password'),
        ]);
    }

    // --- Visitante não autenticado ---

    public function test_guest_is_redirected_to_login_from_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_guest_is_redirected_to_login_from_experiences(): void
    {
        $response = $this->get('/admin/experiences');

        $response->assertRedirect('/login');
    }

    public function test_guest_is_redirected_to_login_from_projects(): void
    {
        $response = $this->get('/admin/projects');

        $response->assertRedirect('/login');
    }

    public function test_guest_is_redirected_to_login_from_technologies(): void
    {
        $response = $this->get('/admin/technologies');

        $response->assertRedirect('/login');
    }

    public function test_guest_is_redirected_to_login_from_social_links(): void
    {
        $response = $this->get('/admin/social-links');

        $response->assertRedirect('/login');
    }

    public function test_guest_is_redirected_to_login_from_about_edit(): void
    {
        $response = $this->get('/admin/about/edit');

        $response->assertRedirect('/login');
    }

    // --- Usuário autenticado não-admin ---

    public function test_non_admin_is_redirected_from_dashboard(): void
    {
        $user = $this->createNonAdmin();

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertRedirect('/');
    }

    public function test_non_admin_is_redirected_from_experiences(): void
    {
        $user = $this->createNonAdmin();

        $response = $this->actingAs($user)->get('/admin/experiences');

        $response->assertRedirect('/');
    }

    public function test_non_admin_is_redirected_from_projects(): void
    {
        $user = $this->createNonAdmin();

        $response = $this->actingAs($user)->get('/admin/projects');

        $response->assertRedirect('/');
    }

    // --- Admin autenticado ---

    public function test_admin_can_access_dashboard(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    public function test_admin_can_access_experiences(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/admin/experiences');

        $response->assertStatus(200);
    }

    public function test_admin_can_access_projects(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/admin/projects');

        $response->assertStatus(200);
    }

    public function test_admin_can_access_technologies(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/admin/technologies');

        $response->assertStatus(200);
    }

    public function test_admin_can_access_social_links(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/admin/social-links');

        $response->assertStatus(200);
    }

    public function test_admin_can_access_about_edit(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/admin/about/edit');

        $response->assertStatus(200);
    }

    // --- Página pública ---

    public function test_public_portfolio_is_accessible_without_auth(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
