<?php

namespace Modules\Role\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Permission\Entities\Permission;
use Modules\Role\Entities\Role;

class RoleDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Role::query()->exists()) {
            return;
        }

        collect([
            'admin',
            'seller',
            'user',
        ])->each(function($name) {
            if (!Role::query()->where('name', $name)->exists()) {
                $obj = new Role;
                $obj->name = $name;
                $obj->save();
            }
        });

        $roleAdmin = Role::query()->where('name', 'admin')->first();
        if ($roleAdmin) {
            $listPermission = Permission::query()->get();
            if ($listPermission) {
                $permissionIds = $listPermission->map->id->toArray();
                $roleAdmin->permissions()->sync($permissionIds);
            }
        }
    }

}
