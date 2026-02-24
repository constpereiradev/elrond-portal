<?php

namespace Tests\Feature;

use App\Enums\RoleEnum;
use App\Models\Council;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Role::create([
            'slug' => RoleEnum::conselho->value,
            'name' => 'Conselho',
            'status' => 'i'
        ]);
    }

    public function test_cannot_auth_with_missing_credentials()
    {
        $payload = [
            'email' => 'admin@gmail.com',
            //'password' => 1234
        ];

        $response = $this->post('/api/v1/auth', $payload);
        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function test_cannot_login_an_inactive_user_role()
    {
        /** @var User $commomUser */
        $commomUser = User::factory()->create([
            'role_id' => Role::where('slug', RoleEnum::conselho->value)
                ->where('status', 'i')
                ->first()->id,
            'password' => bcrypt('password123'),
        ]);

        $payload = [
            'email' => $commomUser->email,
            'password' => 'password123'
        ];

        $response = $this->postJson('api/v1/auth', $payload);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_cannot_logout_a_user_that_does_not_exist()
    {
        $response = $this->postJson('api/v1/logout', []);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
