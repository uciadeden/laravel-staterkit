<?php
// Database/Seeders/RolePermissionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Seed the roles and permissions.
     *
     * @return void
     */
    public function run()
    {
        // Membuat Permissions untuk CRUD
        $createPermission = Permission::firstOrCreate(['name' => 'create posts']);
        $editPermission = Permission::firstOrCreate(['name' => 'edit posts']);
        $deletePermission = Permission::firstOrCreate(['name' => 'delete posts']);
        $viewPermission = Permission::firstOrCreate(['name' => 'view posts']);

        // Membuat Permissions untuk CRUD
        $createPermissionUser = Permission::firstOrCreate(['name' => 'create users']);
        $editPermissionUser = Permission::firstOrCreate(['name' => 'edit users']);
        $deletePermissionUser = Permission::firstOrCreate(['name' => 'delete users']);
        $viewPermissionUser = Permission::firstOrCreate(['name' => 'view users']);

        // Membuat Roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $userRole = Role::firstOrCreate(['name' => 'User']);

        // Memberikan Permissions ke Roles
        $adminRole->givePermissionTo([
            $createPermission, 
            $editPermission, 
            $deletePermission, 
            $viewPermission,
            $createPermissionUser, 
            $editPermissionUser, 
            $deletePermissionUser, 
            $viewPermissionUser
        ]);
        
        $userRole->givePermissionTo($viewPermission);  // User hanya memiliki permission untuk 'view posts'
    }
}
