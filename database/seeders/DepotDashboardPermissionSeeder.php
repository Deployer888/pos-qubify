<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DepotDashboardPermissionSeeder extends Seeder
{
    public function run()
    {
        // Create the permission
        $permission = Permission::firstOrCreate([
            'name' => 'Access Depot Dashboard',
            'guard_name' => 'web'
        ]);

        // Assign to Super Admin role
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        if ($superAdminRole) {
            $superAdminRole->givePermissionTo($permission);
        }

        // Assign to Depot Manager role
        $depotManagerRole = Role::where('name', 'Depot Manager')->first();
        if ($depotManagerRole) {
            $depotManagerRole->givePermissionTo($permission);
        }

        // You can also create other depot-related permissions
        $otherPermissions = [
            'View Depot Stats',
            'Manage Depot Stock',
            'View Depot Sales',
            'Manage Depot Customers'
        ];

        foreach ($otherPermissions as $permissionName) {
            $perm = Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web'
            ]);

            // Assign to roles as needed
            if ($superAdminRole) {
                $superAdminRole->givePermissionTo($perm);
            }
            if ($depotManagerRole) {
                $depotManagerRole->givePermissionTo($perm);
            }
        }
    }
}