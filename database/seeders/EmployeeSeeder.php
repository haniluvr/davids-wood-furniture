<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing admins (disable foreign key checks temporarily)
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Admin::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $employees = [
            // Super Admin
            [
                'employee_id' => 'EMP001',
                'first_name' => 'David',
                'last_name' => 'Atelier',
                'email' => 'david@dwatelier.co',
                'password' => Hash::make('password123'),
                'phone' => '+63 912 345 6789',
                'role' => 'super_admin',
                'department' => 'Executive',
                'position' => 'Founder & CEO',
                'hire_date' => '2020-01-01',
                'salary' => 150000.00,
                'employment_status' => 'active',
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
            ],
            
            // Admin
            [
                'employee_id' => 'EMP002',
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'email' => 'sarah.johnson@dwatelier.co',
                'password' => Hash::make('password123'),
                'phone' => '+63 912 345 6790',
                'role' => 'admin',
                'department' => 'Operations',
                'position' => 'Operations Manager',
                'hire_date' => '2021-03-15',
                'salary' => 80000.00,
                'employment_status' => 'active',
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
            ],
            
            // Manager
            [
                'employee_id' => 'EMP003',
                'first_name' => 'Michael',
                'last_name' => 'Chen',
                'email' => 'michael.chen@dwatelier.co',
                'password' => Hash::make('password123'),
                'phone' => '+63 912 345 6791',
                'role' => 'manager',
                'department' => 'Sales',
                'position' => 'Sales Manager',
                'hire_date' => '2021-06-01',
                'salary' => 65000.00,
                'employment_status' => 'active',
                'permissions' => [
                    'users.view',
                    'products.view', 'products.edit',
                    'orders.view', 'orders.create', 'orders.edit',
                    'inventory.view',
                    'reports.view',
                    'employees.view',
                ],
                'status' => 'active',
            ],
            
            // Manager
            [
                'employee_id' => 'EMP004',
                'first_name' => 'Maria',
                'last_name' => 'Rodriguez',
                'email' => 'maria.rodriguez@dwatelier.co',
                'password' => Hash::make('password123'),
                'phone' => '+63 912 345 6792',
                'role' => 'manager',
                'department' => 'Production',
                'position' => 'Production Manager',
                'hire_date' => '2021-08-15',
                'salary' => 70000.00,
                'employment_status' => 'active',
                'permissions' => [
                    'products.view', 'products.create', 'products.edit',
                    'inventory.view', 'inventory.create', 'inventory.edit',
                    'reports.view',
                    'employees.view',
                ],
                'status' => 'active',
            ],
            
            // Employee
            [
                'employee_id' => 'EMP005',
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'john.smith@dwatelier.co',
                'password' => Hash::make('password123'),
                'phone' => '+63 912 345 6793',
                'role' => 'staff',
                'department' => 'Sales',
                'position' => 'Sales Representative',
                'hire_date' => '2022-01-10',
                'salary' => 35000.00,
                'employment_status' => 'active',
                'permissions' => [
                    'users.view',
                    'products.view',
                    'orders.view', 'orders.create', 'orders.edit',
                    'reports.view',
                ],
                'status' => 'active',
            ],
            
            // Employee
            [
                'employee_id' => 'EMP006',
                'first_name' => 'Lisa',
                'last_name' => 'Wang',
                'email' => 'lisa.wang@dwatelier.co',
                'password' => Hash::make('password123'),
                'phone' => '+63 912 345 6794',
                'role' => 'staff',
                'department' => 'Customer Service',
                'position' => 'Customer Service Representative',
                'hire_date' => '2022-03-20',
                'salary' => 30000.00,
                'employment_status' => 'active',
                'permissions' => [
                    'users.view',
                    'orders.view', 'orders.edit',
                    'reports.view',
                ],
                'status' => 'active',
            ],
            
            // Employee
            [
                'employee_id' => 'EMP007',
                'first_name' => 'Robert',
                'last_name' => 'Brown',
                'email' => 'robert.brown@dwatelier.co',
                'password' => Hash::make('password123'),
                'phone' => '+63 912 345 6795',
                'role' => 'staff',
                'department' => 'Production',
                'position' => 'Craftsman',
                'hire_date' => '2022-05-15',
                'salary' => 40000.00,
                'employment_status' => 'active',
                'permissions' => [
                    'products.view',
                    'inventory.view',
                ],
                'status' => 'active',
            ],
            
            // Employee
            [
                'employee_id' => 'EMP008',
                'first_name' => 'Jennifer',
                'last_name' => 'Davis',
                'email' => 'jennifer.davis@dwatelier.co',
                'password' => Hash::make('password123'),
                'phone' => '+63 912 345 6796',
                'role' => 'staff',
                'department' => 'Administration',
                'position' => 'Administrative Assistant',
                'hire_date' => '2022-07-01',
                'salary' => 28000.00,
                'employment_status' => 'active',
                'permissions' => [
                    'users.view',
                    'products.view',
                    'orders.view',
                    'reports.view',
                ],
                'status' => 'active',
            ],
        ];

        foreach ($employees as $employee) {
            Admin::create($employee);
        }

        $this->command->info('Employees seeded successfully!');
        $this->command->info('Created ' . count($employees) . ' employees with @dwatelier.co email addresses.');
    }
}