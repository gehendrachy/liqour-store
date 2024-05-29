<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//         Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'dashboard-list',
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'setting-update',
            'categories-list',
            'categories-create',
            'categories-edit',
            'categories-delete',
            'product-list',
            'product-create',
            'product-edit',
            'product-delete',
        ];


        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

    }
}
