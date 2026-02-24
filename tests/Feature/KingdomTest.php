<?php

namespace Tests\Feature;

use App\Enums\RoleEnum;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class KingdomTest extends TestCase
{
    use RefreshDatabase;
    
    public function setUp(): void
    {
        parent::setUp();

        Role::create([
            'name' => 'Membro',
            'slug' => RoleEnum::membro->value
        ]);

        Role::create([
            'name' => 'Administrador',
            'slug' => RoleEnum::admin->value
        ]);

        Role::create([
            'name' => 'Conselho',
            'slug' => RoleEnum::conselho->value
        ]);
    }

    public function test_non_admin_cannot_create_kingdom()
    {
        /** @var User $commonUser */
        $commonUser = User::factory()->create([
            'role_id' => Role::where('slug', RoleEnum::membro->value)->first()->id
        ]);

        $payload = [
            'name' => 'Test Kingdom',
        ];

        $this->actingAs($commonUser)
            ->postJson('api/v1/kingdom', $payload)->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_admin_can_create_kingdom()
    {
        /** @var User $commonUser */
        $commonUser = User::factory()->create([
            'role_id' => Role::where('slug', RoleEnum::admin->value)->first()->id
        ]);

        $payload = [
            'name' => 'Test Kingdom',
        ];

        $this->actingAs($commonUser)
            ->postJson('api/v1/kingdom', $payload)->assertStatus(Response::HTTP_OK);
    }
}
