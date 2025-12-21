<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete existing admin if exists
        User::where('email', 'admin@pemdilid.com')->delete();

        // Create admin user
        User::create([
            'name' => 'Admin PEMDILID',
            'email' => 'admin@pemdilid.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '+62 812-3456-7890',
            'organization' => 'PEMDILID',
            'position' => 'System Administrator',
            'is_active' => true,
        ]);

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@pemdilid.com');
        $this->command->info('Password: admin123');
    }
}
