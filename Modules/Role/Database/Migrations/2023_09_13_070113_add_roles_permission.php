<?php

use Illuminate\Database\Migrations\Migration;
use Modules\Permission\Entities\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Permission::insert([
            ['name'  => 'List Roles', 'action'  => 'admin.roles.index' , 'module_name' => 'Roles'],
            ['name'  => 'Create Role', 'action'  => 'admin.roles.create', 'module_name' => 'Roles'],
            ['name'  => 'Update Role', 'action'  => 'admin.roles.edit', 'module_name' => 'Roles'],
            ['name'  => 'Delete Role', 'action'  => 'admin.roles.destroy', 'module_name' => 'Roles']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::whereIn('action', ['admin.roles.index',
                                    'admin.roles.create',
                                    'admin.roles.edit',
                                    'admin.roles.destroy'
                                    ])->delete();
    }
};
