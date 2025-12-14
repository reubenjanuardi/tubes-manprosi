<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test user for development
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Create admin user
        User::create([
            'name' => 'Admin PEMDI',
            'email' => 'admin@pemdi.id',
            'password' => Hash::make('admin123'),
        ]);

        // Create demo assessor user
        User::create([
            'name' => 'John Doe Assessor',
            'email' => 'assessor@pemdi.id',
            'password' => Hash::make('assessor123'),
        ]);

        $this->command->info('Users seeded successfully!');
        $this->command->info('Test User: test@example.com / password123');
        $this->command->info('Admin: admin@pemdi.id / admin123');
        $this->command->info('Assessor: assessor@pemdi.id / assessor123');
    }
}
