<?php

namespace Database\Seeders\User;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [   
                'role_id' => 1,
                'name' => 'Maria recrutadora',
                'email' => 'maria@neoestech.com.br',
                'password' => '#Recruiter10'
            ],
            [   
                'role_id' => 2,
                'name' => 'Daniel candidato',
                'email' => 'daniel@neoestech.com.br',
                'password' => '#Candidate10'
            ],
            [   
                'role_id' => 2,
                'name' => 'Matheus candidato',
                'email' => 'matheus@neoestech.com.br',
                'password' => '#Candidate10'
            ],
        ];

        foreach ($users as $user) {

            $existingUser = User::where('email', $user['email'])->first();

            if(!$existingUser){
                User::updateOrCreate($user);
            }            
        }   
    }
}