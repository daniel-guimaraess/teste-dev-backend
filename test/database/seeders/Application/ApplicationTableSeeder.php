<?php

namespace Database\Seeders\Application;

use App\Models\Application;
use App\Models\Job;
use Illuminate\Database\Seeder;

class ApplicationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $applications = [
            [
                'job_id' => 1,
                'user_id' => 2,
                'applied_at' => now()
            ],
            [
                'job_id' => 3,
                'user_id' => 2,
                'applied_at' => now()
            ],
            [
                'job_id' => 2,
                'user_id' => 3,
                'applied_at' => now()
            ],
        ];

        foreach ($applications as $application) {
            $existingApplication = Application::where('job_id', $application['job_id'])->where('user_id', $application['user_id'])->first();

            if (!$existingApplication) {
                Application::create($application);
            }
        }
    }
}
