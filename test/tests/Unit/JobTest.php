<?php

namespace Tests\Unit;

use App\Enums\StatusJob;
use App\Enums\TypeContract;
use App\Models\Job;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex(){

        Job::factory()->count(5)->create();

        $response = $response = $this->withoutMiddleware()->getJson('/api/jobs');

        $response->assertStatus(200);
    }

    public function testShow(){

        $job = Job::factory()->create();

        $response = $this->withoutMiddleware()->getJson('/api/jobs/' . $job->id);

        $response->assertStatus(200);
    }

    public function testCreate()
    {
        $role = Role::factory()->create(['id' => 1]);

        $user = User::factory()->create(['role_id' => $role->id]);

        $payload = [
            'name' => 'Nova vaga',
            'description' => 'Descrição nova vaga',
            'requirements' => 'Requisitos nova vaga',
            'type_contract' => 'clt'
        ];

        $response = $this->actingAs($user)
                        ->postJson('/api/jobs', $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('job_vacancies', [
            'name' => 'Nova vaga',
            'description' => 'Descrição nova vaga',
            'requirements' => 'Requisitos nova vaga',
            'type_contract' => 'clt'
        ]);
    }

    public function testUpdate()
    {   
        $role = Role::factory()->create(['id' => 1]);
        $user = User::factory()->create(['role_id' => $role->id]);
        $job = Job::factory()->create();

        $updatedData = [
            'name' => 'Att nome',
            'description' => 'Att descrição',
            'requirements' => 'Att requisitos',
            'type_contract' => TypeContract::PJ->value,
            'status' => StatusJob::OPEN->value,
        ];

        $response = $this->actingAs($user)->putJson('/api/jobs/' . $job->id, $updatedData);

        $response->assertStatus(200);

        $job->refresh();
        $this->assertEquals('Att nome', $job->name);
        $this->assertEquals('Att descrição', $job->description);
        $this->assertEquals('Att requisitos', $job->requirements);
        $this->assertEquals(StatusJob::OPEN, $job->status);
        $this->assertEquals(TypeContract::PJ, $job->type_contract);
    }

    public function testDelete()
    {   
        $job = Job::factory()->create();

        $response = $this->withoutMiddleware()->deleteJson('/api/jobs/' . $job->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('jobs', [
            'id' => $job->id,
        ]);
    }
}