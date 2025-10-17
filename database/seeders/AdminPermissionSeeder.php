<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminPermission;

class AdminPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing permissions
        AdminPermission::truncate();

        // Get default role permissions
        $defaultPermissions = AdminPermission::getDefaultRolePermissions();

        // Create permissions for each role
        foreach ($defaultPermissions as $role => $permissions) {
            foreach ($permissions as $permission) {
                AdminPermission::updateOrCreate(
                    [
                        'role' => $role,
                        'permission' => $permission,
                    ],
                    [
                        'granted' => true,
                    ]
                );
            }
        }

        $this->command->info('Admin permissions seeded successfully.');
    }
}
