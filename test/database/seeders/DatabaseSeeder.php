<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Job\JobTableSeeder;
use Database\Seeders\Role\RoleTableSeeder;
use Database\Seeders\User\UserTableSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {  
        $this->call(RoleTableSeeder::class);
        $this->call(UserTableSeeder::class);  
        $this->call(JobTableSeeder::class);  
    }
}
