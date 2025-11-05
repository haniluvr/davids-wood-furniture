<?php

namespace Database\Seeders;

use App\Models\AdminPermission;
use Illuminate\Database\Seeder;

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
            foreach ($permissions as $permission => $granted) {
                // Only create if granted is true
                if ($granted === true) {
                    AdminPermission::updateOrCreate(
                        [
                            'role' => strtolower($role), // Normalize role to lowercase
                            'permission' => $permission,
                        ],
                        [
                            'granted' => true,
                        ]
                    );
                }
            }
        }

        $this->command->info('Admin permissions seeded successfully.');
    }
}
