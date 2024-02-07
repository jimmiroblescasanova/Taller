<?php

namespace Database\Factories;

use App\Enums\ContactMedia;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->numerify('##########'),
            'channel' => fake()->randomElement(ContactMedia::class),
            'created_at' => fake()->dateTimeThisYear(max: 'now'),
        ];
    }
}
