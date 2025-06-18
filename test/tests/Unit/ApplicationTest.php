<?php

namespace Tests\Unit;

use App\Models\Application;
use App\Models\Job;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function testGetApplicationsByJobID(){

        $job = Job::factory()->create();

        $role = Role::factory()->create();

        $user = User::factory()->create(['role_id' => $role->id]);

        Application::factory()->create(['job_id' => $job->id, 'user_id' => $user->id]);

        $response = $response = $this->withoutMiddleware()->getJson('/api/applications/job/' . $job->id);

        $response->assertStatus(200);
    }


    public function testGetApplicationsByUserID(){

        $job = Job::factory()->create();

        $role = Role::factory()->create();

        $user = User::factory()->create(['role_id' => $role->id]);

        Application::factory()->create(['job_id' => $job->id, 'user_id' => $user->id]);

        $response = $response = $this->withoutMiddleware()->getJson('/api/applications/user/' . $user->id);

        $response->assertStatus(200);
    }


    public function testCreateApplication()
    {
        $job = Job::factory()->create(['status' => 'open']);

        $role = Role::factory()->create();

        $user = User::factory()->create(['role_id' => $role->id]);

        $payload = [
            'job_id' => $job->id,
            'user_id' => $user->id,
        ];

        $response = $this->withoutMiddleware()->postJson('/api/applications', $payload);

        $response->assertStatus(200);


        $this->assertDatabaseHas('applications', [
            'job_id' => $job->id,
            'user_id' => $user->id,
        ]);
    }

    public function testUpdateStatus()
    {
        $job = Job::factory()->create(['id' => 1]);
        $role = Role::factory()->create();
        $user = User::factory()->create(['id' => 1, 'role_id' => $role->id]);

        $application = Application::factory()->create([
            'job_id' => $job->id,
            'user_id' => $user->id,
            'status' => 'analyzing',
        ]);

        $newStatus = 'selected';

        $response = $this->withoutMiddleware()->putJson("/api/applications/" . $application->id, [
            'status' => $newStatus,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'status' => $newStatus,
        ]);
    }

    public function testDeleteApplication()
    {

        $job = Job::factory()->create(['id' => 1]);
        $role = Role::factory()->create();
        $user = User::factory()->create(['id' => 1, 'role_id' => $role->id]);

        $application = Application::factory()->create([
            'job_id' => $job->id,
            'user_id' => $user->id,
        ]);

        $response = $this->withoutMiddleware()->deleteJson("/api/applications/" . $application->id);

        $response->assertStatus(200);

        $this->assertSoftDeleted('applications', ['id' => $application->id]);
    }

}