<?php

namespace Database\Seeders\Job;

use App\Models\Job;
use Illuminate\Database\Seeder;

class JobTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobs = [
            [
                'name' => 'Desenvolvedor Backend Laravel',
                'description' => 'Desenvolvimento e manutenção de APIs em Laravel',
                'requirements' => 'Experiência com PHP, Laravel, MySQL, Git',
                'type_contract' => 'clt',
                'status' => 'open'
            ],
            [
                'name' => 'Desenvolvedor Frontend React',
                'description' => 'Desenvolvimento de interfaces web com React',
                'requirements' => 'Experiência com React, JavaScript, CSS, HTML',
                'type_contract' => 'pj',
                'status' => 'open'
            ],
            [
                'name' => 'Tech Lead',
                'description' => 'Liderança técnica de projetos e times de desenvolvimento',
                'requirements' => 'Experiência em liderança, arquitetura de software e metodologias ágeis',
                'type_contract' => 'freelancer',
                'status' => 'open'
            ],
        ];

        foreach ($jobs as $job) {
            $existingJob = Job::where('name', $job['name'])->first();

            if (!$existingJob) {
                Job::create($job);
            }
        }
    }
}
