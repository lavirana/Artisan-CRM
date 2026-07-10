<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create baseline permissions (optional but recommended)
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'manage settings']);
        Permission::create(['name' => 'view dashboard']);
        Permission::create(['name' => 'manage sales']);

        // 2. Create your required roles
        $adminRole = Role::create(['name' => 'Admin']);
        $managerRole = Role::create(['name' => 'Manager']);
        $salesRepRole = Role::create(['name' => 'Sales Rep']);

        // 3. Assign default permissions to roles
        $adminRole->givePermissionTo(Permission::all());
        
        $managerRole->givePermissionTo([
            'view dashboard',
            'manage sales'
        ]);

        $salesRepRole->givePermissionTo([
            'view dashboard'
        ]);
    }
}
