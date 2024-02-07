<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\VehicleBrand;
use App\Models\VehicleType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'contact_id' => fake()->randomElement(Contact::pluck('id')),
            'vehicle_type_id' => fake()->randomElement(VehicleType::pluck('id')),
            'vehicle_brand_id' => fake()->randomElement(VehicleBrand::pluck('id')),
            'model' => fake()->citySuffix(),
            'year' => fake()->year(),
            'color' => fake()->colorName(),
            'license_plate' => fake()->bothify('???-######-???'),
            'created_at' => fake()->dateTimeThisYear(max: 'now'),
        ];
    }
}
