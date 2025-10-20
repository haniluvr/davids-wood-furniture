<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing employees (disable foreign key checks temporarily)
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Employee::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $employees = [
            // Super Admin
            [
                'first_name' => 'David',
                'last_name' => 'Atelier',
                'email' => 'david@dwatelier.co',
                'password' => Hash::make('password123'),
                'phone' => '+63 912 345 6789',
                'role' => 'super_admin',
                'permissions' => [
                    'users.view', 'users.create', 'users.edit', 'users.delete',
                    'products.view', 'products.create', 'products.edit', 'products.delete',
                    'orders.view', 'orders.create', 'orders.edit', 'orders.delete',
                    'inventory.view', 'inventory.create', 'inventory.edit', 'inventory.delete',
                    'reports.view', 'reports.export',
                    'settings.view', 'settings.edit',
                    'employees.view', 'employees.create', 'employees.edit', 'employees.delete',
                    'cms.view', 'cms.create', 'cms.edit', 'cms.delete',
                ],
                'status' => 'active',
                'email_verified_at' => now(),
            ],

            // Admin
            [
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'email' => 'sarah.johnson@dwatelier.co',
                'password' => Hash::make('password123'),
                'phone' => '+63 912 345 6790',
                'role' => 'admin',
                'permissions' => [
                    'users.view', 'users.create', 'users.edit',
                    'products.view', 'products.create', 'products.edit',
                    'orders.view', 'orders.create', 'orders.edit',
                    'inventory.view', 'inventory.create', 'inventory.edit',
                    'reports.view', 'reports.export',
                    'employees.view', 'employees.create', 'employees.edit',
                    'cms.view', 'cms.create', 'cms.edit',
                ],
                'status' => 'active',
                'email_verified_at' => now(),
            ],

            // Manager
            [
                'first_name' => 'Michael',
                'last_name' => 'Chen',
                'email' => 'michael.chen@dwatelier.co',
                'password' => Hash::make('password123'),
                'phone' => '+63 912 345 6791',
                'role' => 'manager',
                'permissions' => [
                    'users.view',
                    'products.view', 'products.edit',
                    'orders.view', 'orders.create', 'orders.edit',
                    'inventory.view',
                    'reports.view',
                    'employees.view',
                ],
                'status' => 'active',
                'email_verified_at' => now(),
            ],

            // Manager
            [
                'first_name' => 'Maria',
                'last_name' => 'Rodriguez',
                'email' => 'maria.rodriguez@dwatelier.co',
                'password' => Hash::make('password123'),
                'phone' => '+63 912 345 6792',
                'role' => 'manager',
                'permissions' => [
                    'products.view', 'products.create', 'products.edit',
                    'inventory.view', 'inventory.create', 'inventory.edit',
                    'reports.view',
                    'employees.view',
                ],
                'status' => 'active',
                'email_verified_at' => now(),
            ],

            // Employee
            [
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'john.smith@dwatelier.co',
                'password' => Hash::make('password123'),
                'phone' => '+63 912 345 6793',
                'role' => 'staff',
                'permissions' => [
                    'users.view',
                    'products.view',
                    'orders.view', 'orders.create', 'orders.edit',
                    'reports.view',
                ],
                'status' => 'active',
                'email_verified_at' => now(),
            ],

            // Employee
            [
                'first_name' => 'Lisa',
                'last_name' => 'Wang',
                'email' => 'lisa.wang@dwatelier.co',
                'password' => Hash::make('password123'),
                'phone' => '+63 912 345 6794',
                'role' => 'staff',
                'permissions' => [
                    'users.view',
                    'orders.view', 'orders.edit',
                    'reports.view',
                ],
                'status' => 'active',
                'email_verified_at' => now(),
            ],

            // Employee
            [
                'first_name' => 'Robert',
                'last_name' => 'Brown',
                'email' => 'robert.brown@dwatelier.co',
                'password' => Hash::make('password123'),
                'phone' => '+63 912 345 6795',
                'role' => 'staff',
                'permissions' => [
                    'products.view',
                    'inventory.view',
                ],
                'status' => 'active',
                'email_verified_at' => now(),
            ],

            // Employee
            [
                'first_name' => 'Jennifer',
                'last_name' => 'Davis',
                'email' => 'jennifer.davis@dwatelier.co',
                'password' => Hash::make('password123'),
                'phone' => '+63 912 345 6796',
                'role' => 'staff',
                'permissions' => [
                    'users.view',
                    'products.view',
                    'orders.view',
                    'reports.view',
                ],
                'status' => 'active',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }

        $this->command->info('Employees seeded successfully!');
        $this->command->info('Created '.count($employees).' employees with @dwatelier.co email addresses.');
    }
}
