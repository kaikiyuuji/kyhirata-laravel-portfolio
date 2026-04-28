<?php

namespace Tests\Feature\Security;

use App\Models\Experience;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class XssTest extends TestCase
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

    public function test_script_tag_in_experience_is_saved_as_plain_text(): void
    {
        $this->actingAsAdmin();
        $xss = '<script>alert("xss")</script>';

        $this->post(route('admin.experiences.store'), [
            'company' => $xss,
            'role' => 'Developer',
            'description' => 'Normal description.',
            'started_at' => '2023-01-01',
            'is_visible' => 1,
        ]);

        // strip_tags in prepareForValidation removes the tags
        // so the DB should contain empty or cleaned string
        $experience = Experience::first();
        $this->assertNotNull($experience);
        // strip_tags removes HTML tags, leaving only inner text
        $this->assertStringNotContainsString('<script>', $experience->company);
    }

    public function test_xss_in_description_is_escaped_in_blade_output(): void
    {
        $this->actingAsAdmin();
        $xss = '<script>alert("xss")</script>';

        // Description doesn't have strip_tags in prepareForValidation,
        // so it's stored raw but Blade {{ }} escapes it
        Experience::factory()->create([
            'company' => 'Safe Company',
            'description' => $xss,
            'is_visible' => true,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        // Blade {{ }} escapes output — raw <script> should NOT appear
        $response->assertDontSee($xss, false);
        // The escaped version should be present
        $response->assertSee(e($xss), false);
    }

    public function test_xss_in_about_me_bio_is_escaped_in_blade(): void
    {
        $this->actingAsAdmin();
        $xss = '<script>document.cookie</script>';

        $this->put(route('admin.about.update'), [
            'name' => 'Test User',
            'title' => 'Developer',
            'bio' => $xss,
            'email' => 'test@example.com',
            'is_available_for_work' => 1,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee($xss, false);
        $response->assertSee(e($xss), false);
    }
}
