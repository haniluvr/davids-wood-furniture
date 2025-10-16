<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::updateOrCreate(
            ['email' => 'admin@davidswood.com'],
            [
                'employee_id' => 'EMP001',
                'first_name' => 'David',
                'last_name' => 'Wood',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'email_verified_at' => now(),
            ]
        );

        Employee::updateOrCreate(
            ['email' => 'manager@davidswood.com'],
            [
                'employee_id' => 'EMP002',
                'first_name' => 'John',
                'last_name' => 'Manager',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'email_verified_at' => now(),
            ]
        );

        Employee::updateOrCreate(
            ['email' => 'staff@davidswood.com'],
            [
                'employee_id' => 'EMP003',
                'first_name' => 'Jane',
                'last_name' => 'Staff',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'email_verified_at' => now(),
            ]
        );
    }
}