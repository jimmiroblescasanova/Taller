<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PermissionsSeeder::class);

        $user = \App\Models\User::factory()->create([
            'name' => 'Admin JRC',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
        ]);

        $SuperAdmin = Role::create(['name' => 'Super Admin']);
        $user->assignRole($SuperAdmin);

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
