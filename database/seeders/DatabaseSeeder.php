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

    }
}
