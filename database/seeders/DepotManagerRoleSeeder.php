<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DepotManagerRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Depot Manager role
        $role = Role::create(['name' => 'Depot Manager', 'guard_name' => 'web']);

        // Assign depot-related permissions
        $permissions = [
            'Show Depot List',
            'Add Depot',
            'Edit Depot',
            'Delete Depot',
            'Manage Depot Stock',
            'Manage Depot Customers',
            'Access Depot POS'
        ];

        foreach ($permissions as $permission) {
            // Check if permission exists, if not create it
            $permissionModel = Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web']
            );
            
            // Assign permission to role
            $role->givePermissionTo($permissionModel);
        }
    }
}
