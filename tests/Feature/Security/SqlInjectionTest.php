<?php

namespace Tests\Feature\Security;

use App\Models\Experience;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SqlInjectionTest extends TestCase
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

    public function test_sql_payload_in_company_field_is_saved_as_literal_string(): void
    {
        $this->actingAsAdmin();
        $payload = "'; DROP TABLE experiences; --";

        $this->post(route('admin.experiences.store'), [
            'company' => $payload,
            'role' => 'Developer',
            'description' => 'Test description for SQL injection.',
            'started_at' => '2023-01-01',
            'is_visible' => 1,
        ]);

        $this->assertDatabaseHas('experiences', [
            'company' => $payload,
        ]);
    }

    public function test_union_select_payload_does_not_leak_user_data(): void
    {
        $this->actingAsAdmin();
        $payload = "' UNION SELECT * FROM users --";

        $response = $this->post(route('admin.experiences.store'), [
            'company' => $payload,
            'role' => 'Tester',
            'description' => 'Union select test.',
            'started_at' => '2023-06-15',
            'is_visible' => 1,
        ]);

        $response->assertRedirect(route('admin.experiences.index'));
        $this->assertDatabaseHas('experiences', ['company' => $payload]);
    }

    public function test_or_1_equals_1_payload_is_stored_literally(): void
    {
        $this->actingAsAdmin();
        $payload = "' OR '1'='1";

        $this->post(route('admin.experiences.store'), [
            'company' => $payload,
            'role' => 'Analyst',
            'description' => 'OR injection test.',
            'started_at' => '2022-03-10',
            'is_visible' => 1,
        ]);

        $experience = Experience::where('company', $payload)->first();
        $this->assertNotNull($experience);
        $this->assertEquals($payload, $experience->company);
    }
}
