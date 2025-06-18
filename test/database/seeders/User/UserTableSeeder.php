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
                'name' => 'Admin',
                'email' => 'admin@neoestech.com.br',
                'password' => '#Admin10'
            ],
            [   
                'role_id' => 2,
                'name' => 'Recrutador',
                'email' => 'recruiter@neoestech.com.br',
                'password' => '#Recruiter10'
            ],
            [   
                'role_id' => 3,
                'name' => 'Candidato',
                'email' => 'candidate@neoestech.com.br',
                'password' => '#Candidate10'
            ],
        ];

        foreach ($users as $user) {

            $existingUser = User::where('email', $user['email'])->first();

            if(!$existingUser){
                User::create($user);
            }            
        }   
    }
}