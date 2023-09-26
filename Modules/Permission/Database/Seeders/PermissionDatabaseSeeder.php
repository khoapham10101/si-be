<?php

namespace Modules\Permission\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Permission\Entities\Permission;
use Modules\Role\Entities\Role;

class PermissionDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Permission::query()->exists()) {
            return;
        }

        collect([
            ['List Roles', 'admin.roles.index', 'Roles'],
            ['Create Role', 'admin.roles.create', 'Roles'],
            ['Update Role', 'admin.roles.edit', 'Roles'],
            ['Delete Role', 'admin.roles.destroy', 'Roles'],
            ['List Brands', 'admin.brands.index', 'Brands'],
            ['Create Brand', 'admin.brands.create', 'Brands'],
            ['Update Brand', 'admin.brands.edit', 'Brands'],
            ['Delete Brand', 'admin.brands.destroy', 'Brands'],
            ['List Products', 'admin.products.index', 'Products'],
            ['Create Product', 'admin.products.create', 'Products'],
            ['Update Product', 'admin.products.edit', 'Products'],
            ['Delete Product', 'admin.products.destroy', 'Products'],
            ['List Users', 'admin.users.index', 'Users'],
            ['Create User', 'admin.users.create', 'Users'],
            ['Update User', 'admin.users.edit', 'Users'],
            ['Delete User', 'admin.users.destroy', 'Users'],
            ['List User Status', 'admin.user_statuses.index', 'User Status'],
            ['Create User Status', 'admin.user_statuses.create', 'User Status'],
            ['Update User Status', 'admin.user_statuses.edit', 'User Status'],
            ['Delete User Status', 'admin.user_statuses.destroy', 'User Status'],
        ])->each(function ($row) {
            if (is_array($row)) {
                [$name, $action, $moduleName] = $row;
                $obj = new Permission;
                $obj->name = $name;
                $obj->action = $action;
                $obj->module_name = $moduleName;
                $obj->save();
            }
        });
    }
}
