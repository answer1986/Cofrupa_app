<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Create permissions
        $permissions = [
            'manage users',
            'view calibration',
            'manage calibration',
            'view reports',
            'manage system',
            'manage processed bins',
            'scan qr codes',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        $adminRole = Role::where('name', 'Admin')->first();
        $supervisorRole = Role::where('name', 'Supervisor')->first();

        // Super Admin gets all permissions
        $superAdminRole->syncPermissions($permissions);

        // Admin gets most permissions except system management
        $adminRole->syncPermissions([
            'manage users',
            'view calibration',
            'manage calibration',
            'view reports',
            'manage processed bins',
            'scan qr codes',
        ]);

        // Supervisor gets limited permissions
        $supervisorRole->syncPermissions([
            'view calibration',
            'view reports',
            'scan qr codes',
        ]);
    }
}