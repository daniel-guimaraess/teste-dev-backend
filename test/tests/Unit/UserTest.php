<?php

namespace Tests\Unit;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex(){

        $role = Role::factory()->create();

        User::factory()->count(5)->create(['role_id' => $role->id]);

        $response = $response = $this->withoutMiddleware()->getJson('/api/users');

        $response->assertStatus(200);
    }

    public function testShow(){
        $role = Role::factory()->create();

        $user = User::factory()->create(['role_id' => $role->id]);

        $response = $this->withoutMiddleware()->getJson('/api/users/' . $user->id);

        $response->assertStatus(200);
    }

    public function testCreateUser()
    {
        $role = Role::factory()->create();

        $payload = [
            'name' => 'Novo usuário',
            'email' => 'novo@email.com',
            'password' => 'senha123',
            'role_id' => $role->id,
        ];

        $response = $this->withoutMiddleware()->postJson('/api/users', $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'name' => 'Novo usuário',
            'email' => 'novo@email.com',
            'role_id' => $role->id,
        ]);
    }

    public function testUpdate()
    {   
        Role::factory()->count(2)->create();

        $user = User::factory()->create(['role_id' => 1]);

        $updatedData = [
            'role_id' => 2,
            'name' => 'Usuario atualizado',
            'email' => 'atualizado@gmail.com',
            'password' => 'senha123'
        ];

        $response = $this->withoutMiddleware()->putJson('/api/users/'.$user->id, $updatedData);

        $response->assertStatus(200);

        $user->refresh();
        $this->assertEquals(2, $user->role->id);
        $this->assertEquals('Usuario atualizado', $user->name);
        $this->assertEquals('atualizado@gmail.com', $user->email);
    }

    public function testDelete()
    {   
        Role::factory()->create();

        $user = User::factory()->create(['role_id' => 1]);

        $response = $this->withoutMiddleware()->deleteJson('/api/users/'.$user->id);

        $response->assertStatus(200);
    }
}