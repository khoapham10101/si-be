<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
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
            ['name'  => 'List User Statuses', 'action'  => 'admin.user_statuses.index' , 'module_name' => 'User Statuses'],
            ['name'  => 'Create User Statuses', 'action'  => 'admin.user_statuses.create', 'module_name' => 'User Statuses'],
            ['name'  => 'Update User Statuses', 'action'  => 'admin.user_statuses.edit', 'module_name' => 'User Statuses'],
            ['name'  => 'Delete User Statuses', 'action'  => 'admin.user_statuses.destroy', 'module_name' => 'User Statuses']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::whereIn('action', ['admin.user_statuses.index',
                                    'admin.user_statuses.create',
                                    'admin.user_statuses.edit',
                                    'admin.user_statuses.destroy'
                                    ])->delete();
    }
};
