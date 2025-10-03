<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@davidswood.com'],
            [
                'first_name' => 'David',
                'last_name' => 'Wood',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'email_verified_at' => now(),
            ]
        );

        Admin::updateOrCreate(
            ['email' => 'manager@davidswood.com'],
            [
                'first_name' => 'John',
                'last_name' => 'Manager',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'email_verified_at' => now(),
            ]
        );

        Admin::updateOrCreate(
            ['email' => 'staff@davidswood.com'],
            [
                'first_name' => 'Jane',
                'last_name' => 'Staff',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'email_verified_at' => now(),
            ]
        );
    }
}