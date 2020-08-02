<?php

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        // create permissions

        Permission::create(['name' => 'api', 'guard_name' => 'web']);
        Permission::create(['name' => 'debug', 'guard_name' => 'web']);
        Permission::create(['name' => 'beta', 'guard_name' => 'web']);

        // create roles and assign created permissions
        Role::create(['name' => 'root', 'guard_name' => 'web'])->givePermissionTo(Permission::all());
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'client', 'guard_name' => 'web']);
    }
}
