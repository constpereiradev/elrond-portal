<?php

namespace Tests\Feature;

use App\Enums\RoleEnum;
use App\Models\Expedition;
use App\Models\ExpeditionProtocol;
use App\Models\ExpeditionStatus;
use App\Models\Kingdom;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ExpeditionTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Role::create(['slug' => RoleEnum::membro->value, 'name' => 'Membro']);
        Role::create(['slug' => RoleEnum::reino->value, 'name' => 'Reino']);
        Role::create(['slug' => RoleEnum::conselho->value, 'name' => 'Conselho']);
        ExpeditionStatus::create(['status' => 'Em Análise', 'slug' => 'ANALISE']);
        ExpeditionStatus::create(['status' => 'Rejeitada', 'slug' => 'REJEITADA']);

        $kingdom = Kingdom::create([
            'name' => 'Test Kingdom',
        ]);

        $expedition = Expedition::create([
            'kingdom_id' => $kingdom->id,
            'start_date' => now(),
            'status_id' => ExpeditionStatus::first()->id
        ]);

        ExpeditionProtocol::create([
            'expedition_id' => $expedition->id,
            'uuid' => '123ABC',
        ]);
    }

    public function test_non_council_or_kingdom_cannot_view_expedition()
    {
        /** @var User $commonUser */
        $commonUser = User::factory()->create([
            'role_id' => Role::where('slug', RoleEnum::membro->value)->first()->id
        ]);

        $this
            ->actingAs($commonUser)
            ->get('/api/v1/expedition/123ABC')
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_non_kingdom_cannot_register_expedition()
    {
        /** @var User $commonUser */
        $commonUser = User::factory()->create([
            'role_id' => Role::where('slug', RoleEnum::membro->value)->first()->id,
        ]);

        $payload = [
            'start_date' => now()->addDays(1)->format('Y-m-d'),
            'kingdom_id' => Kingdom::first()->id,
        ];

        $this
            ->actingAs($commonUser)
            ->postJson('/api/v1/expedition', $payload)
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_kingdom_can_register_expedition()
    {
        /** @var User $commonUser */
        $commonUser = User::factory()->create([
            'role_id' => Role::where('slug', RoleEnum::reino->value)->first()->id,
            'kingdom_id' => Kingdom::first()->id,
        ]);

        $payload = [
            'start_date' => now()->addDays(1)->format('Y-m-d'),
            'kingdom_id' => $commonUser->kingdom_id,
        ];

        $this
            ->actingAs($commonUser)
            ->postJson('/api/v1/expedition', $payload)
            ->assertStatus(Response::HTTP_OK);
    }

    public function test_non_council_cannot_update_expedition()
    {
        $this->withExceptionHandling();

        /** @var User $commonUser */
        $commonUser = User::factory()->create([
            'role_id' => Role::where('slug', RoleEnum::membro->value)->first()->id,
        ]);

        $payload = [
            'status_id' => ExpeditionStatus::first()->id
        ];

        $this
            ->actingAs($commonUser)
            ->putJson('/api/v1/expedition/123ABC', $payload)
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_council_can_update_expedition()
    {
        $this->withExceptionHandling();

        /** @var User $commonUser */
        $commonUser = User::factory()->create([
            'role_id' => Role::where('slug', RoleEnum::conselho->value)->first()->id,
        ]);

        $payload = [
            'status_id' => ExpeditionStatus::first()->id
        ];

        $this
            ->actingAs($commonUser)
            ->putJson('/api/v1/expedition/123ABC', $payload)
            ->assertStatus(Response::HTTP_OK);
    }
}
