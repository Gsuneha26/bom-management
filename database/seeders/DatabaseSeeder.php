<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ])->assignRole('Admin');

        User::factory()->create([
            'name' => 'Purchase User',
            'email' => 'purchase@example.com',
            'password' => bcrypt('password'),
        ])->assignRole('Purchase Dept');

        User::factory()->create([
            'name' => 'Engineer User',
            'email' => 'engineer@example.com',
            'password' => bcrypt('password'),
        ])->assignRole('Engineer');

        User::factory()->create([
            'name' => 'Store Manager',
            'email' => 'store@example.com',
            'password' => bcrypt('password'),
        ])->assignRole('Store Manager');
    }
}
