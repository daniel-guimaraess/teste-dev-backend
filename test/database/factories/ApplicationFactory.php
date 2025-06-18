<?php

namespace Database\Factories;

use App\Enums\StatusJob;
use App\Enums\TypeContract;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'job_id' => 1,
            'user_id' => 1,
            'status' => $this->faker->randomElement(['analyzing', 'selected', 'not_selected']),
            'applied_at' => now(),
        ];
    }
}
