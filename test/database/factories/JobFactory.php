<?php

namespace Database\Factories;

use App\Enums\StatusJob;
use App\Enums\TypeContract;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->jobTitle,
            'description' => $this->faker->paragraph,
            'requirements' => $this->faker->sentence,
            'type_contract' => $this->faker->randomElement([
                TypeContract::CLT,
                TypeContract::PJ,
                TypeContract::FREELANCER,
            ])
        ];
    }
}
