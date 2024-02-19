<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FakeDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeders de la empresa
        \App\Models\VehicleBrand::factory(10)->create();
        \App\Models\VehicleType::factory(10)->create();
        \App\Models\Agent::factory(10)->create();
        \App\Models\Specialist::factory(15)->create();
        \App\Models\Station::factory(10)->create();
        // Seeders de los clientes
        \App\Models\Contact::factory(50)->create();
        \App\Models\Vehicle::factory(100)->create();
    }
}
