<?php

namespace Tests\Feature;

use App\Enums\RoleEnum;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['slug' => RoleEnum::admin->value, 'name' => 'Admin']);
        Role::create(['slug' => RoleEnum::reino->value, 'name' => 'Reino']);
        Role::create(['slug' => RoleEnum::conselho->value, 'name' => 'Conselho']);
        Role::create(['slug' => RoleEnum::membro->value, 'name' => 'Membro']);
    }

    /** @test */
    public function the_logged_user_can_be_returned()
    {
        /** @var User $commonUser */
        $commonUser = User::factory()->create([
            'role_id' => Role::where('slug', RoleEnum::membro->value)->first()->id
        ]);

        $response = $this->actingAs($commonUser)->get('api/v1/auth/user');
        $response->assertStatus(200);
    }

    /** @test */
    public function a_non_admin_cannot_create_an_admin_user()
    {
        /** @var User $commonUser */
        $commonUser = User::factory()->create(
            [
                'role_id' => Role::where('slug', RoleEnum::reino->value)->first()->id
            ]
        );

        $adminRole = Role::where('slug', RoleEnum::admin->value)->first();

        $payload = [
            'name' => 'Amanda Pereira',
            'email' => 'amanda@example.com',
            'password' => '123456',
            'role_id' => $adminRole->id
        ];

        $response = $this->actingAs($commonUser)
            ->postJson('/api/v1/user', $payload);
        $response->assertStatus(403);
    }
}
