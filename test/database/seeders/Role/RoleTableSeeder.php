<?php

namespace Database\Seeders\Role;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'recruiter',
                'description' => 'Pode criar e divulgar vagas, além de gerenciar candidaturas recebidas.'
            ],
            [
                'name' => 'candidate',
                'description' => 'Pode visualizar vagas disponíveis e se candidatar a elas.'
            ]
        ];

        foreach ($roles as $role) {

            $existingRoleUser = Role::where('name', $role['name'])->first();

            if(!$existingRoleUser){
                Role::create([
                    'name' => $role['name'],
                    'description' => $role['description']
                ]);
            }            
        }   
    }
}