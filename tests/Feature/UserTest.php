<?php

namespace Tests\Feature;

use App\Enums\RoleEnum;
use App\Models\Council;
use App\Models\Kingdom;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
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

        Kingdom::create([
            'name' => 'Reino Teste',
            'description' => 'Descrição do Reino Teste'
        ]);

        Council::create([
            'name' => 'Conselho Teste',
            'description' => 'Descrição do Conselho Teste'
        ]);
    }

    public function test_the_logged_user_can_be_returned()
    {
        /** @var User $commonUser */
        $commonUser = User::factory()->create([
            'role_id' => Role::where('slug', RoleEnum::membro->value)->first()->id
        ]);

        $response = $this->actingAs($commonUser)->get('api/v1/auth/user');
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_a_non_admin_cannot_create_an_admin_user()
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
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_a_user_can_be_registered()
    {
        /** @var User $commonUser */
        $commonUser = User::factory()->create([
            'role_id' => Role::where('slug', RoleEnum::membro->value)->first()->id
        ]);

        $payload = [
            'name' => 'Amanda Pereira',
            'email' => 'amanda@example.com',
            'password' => '123456',
            'role_id' => Role::where('slug', RoleEnum::membro->value)->first()->id
        ];

        $response = $this->actingAs($commonUser)
            ->postJson('/api/v1/user', $payload);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_cannot_send_council_and_kingom_data_simultaneously()
    {
        /** @var User $commonUser */
        $commonUser = User::factory()->create([
            'role_id' => Role::where('slug', RoleEnum::membro->value)->first()->id
        ]);

        $payload = [
            'name' => 'Amanda Pereira',
            'email' => 'amanda@example.com',
            'password' => '123456',
            'role_id' => Role::where('slug', RoleEnum::membro->value)->first()->id,
            'council_id' => 1,
            'kingdom_id'=> 1,
        ];

        $response = $this->actingAs($commonUser)
            ->postJson('/api/v1/user', $payload);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_cannot_have_missing_fields_on_user_register()
    {
        $payload = [
            'name' => 'Amanda Pereira',
            //'email' => 'amanda@example.com',
            'password' => '123456',
            'role_id' => Role::where('slug', RoleEnum::membro->value)->first()->id,
            'council_id' => 1,
            'kingdom_id'=> 1,
        ];

        $response = $this->postJson('/api/v1/user', $payload);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
