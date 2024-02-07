<?php

namespace Database\Factories;

use App\Enums\SpecialistType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Specialist>
 */
class SpecialistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name('male'),
            'type' => fake()->randomElement(SpecialistType::class),
            'created_at' => fake()->dateTimeThisYear(max: 'now'),
        ];
    }
}
