<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Create an admin user
        User::firstOrCreate(
            ['email' => 'admin@dwatelier.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'username' => 'admin',
                'email' => 'admin@dwatelier.com',
                'password' => Hash::make('password123'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create a regular user for testing
        User::firstOrCreate(
            ['email' => 'nerissa@example.com'],
            [
                'first_name' => 'Nerissa',
                'last_name' => 'Johnson',
                'username' => 'nerissa',
                'email' => 'nerissa@example.com',
                'password' => Hash::make('password123'),
                'is_admin' => true, // Make this user admin too for demo
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin users created successfully!');
        $this->command->info('Email: admin@dwatelier.com | Password: password123');
        $this->command->info('Email: nerissa@example.com | Password: password123');
    }
}
