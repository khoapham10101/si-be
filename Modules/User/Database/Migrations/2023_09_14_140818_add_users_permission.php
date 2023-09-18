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
            ['name'  => 'List Users', 'action'  => 'admin.users.index' , 'module_name' => 'Users'],
            ['name'  => 'Create User', 'action'  => 'admin.users.create', 'module_name' => 'Users'],
            ['name'  => 'Create User', 'action'  => 'admin.users.edit', 'module_name' => 'Users'],
            ['name'  => 'Create User', 'action'  => 'admin.users.destroy', 'module_name' => 'Users']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::whereIn('action', ['admin.users.index',
                                        'admin.users.create',
                                        'admin.users.edit',
                                        'admin.users.destroy'
                                        ])->delete();
    }
};
