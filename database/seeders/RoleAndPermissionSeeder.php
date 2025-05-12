<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User management
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Class management
            'view classes',
            'create classes',
            'edit classes',
            'delete classes',

            // Student management
            'view students',
            'create students',
            'edit students',
            'delete students',

            // Teacher management
            'view teachers',
            'create teachers',
            'edit teachers',
            'delete teachers',

            // QR Code management
            'view qrcodes',
            'create qrcodes',
            'edit qrcodes',
            'delete qrcodes',

            // Attendance management
            'view attendances',
            'create attendances',
            'edit attendances',
            'delete attendances',
            'export attendances',

            // Report management
            'view reports',
            'export reports',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $roles = [
            'admin' => $permissions,
            'guru' => [
                'view classes',
                'view students',
                'view teachers',
                'view qrcodes',
                'create qrcodes',
                'view attendances',
                'create attendances',
                'edit attendances',
                'view reports',
                'export reports',
            ],
            'siswa' => [
                'view classes',
                'view attendances',
                'create attendances',
            ],
        ];

        foreach ($roles as $role => $rolePermissions) {
            $role = Role::create(['name' => $role]);
            $role->givePermissionTo($rolePermissions);
        }
    }
}
