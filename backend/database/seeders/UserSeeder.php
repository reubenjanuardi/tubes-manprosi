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
        // Create admin user
        User::create([
            'name' => 'Admin PEMDI',
            'email' => 'admin@pemdi.id',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);

        $this->command->info('Users seeded successfully!');
        $this->command->info('Admin: admin@pemdi.id / admin123');
    }
}
