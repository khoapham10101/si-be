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
            ['name'  => 'List Products', 'action'  => 'admin.products.index' , 'module_name' => 'Products'],
            ['name'  => 'Create Product', 'action'  => 'admin.products.create', 'module_name' => 'Products'],
            ['name'  => 'Update Product', 'action'  => 'admin.products.edit', 'module_name' => 'Products'],
            ['name'  => 'Delete Product', 'action'  => 'admin.products.destroy', 'module_name' => 'Products']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::whereIn('action', ['admin.products.index',
                                    'admin.products.create',
                                    'admin.products.edit',
                                    'admin.products.destroy'
                                    ])->delete();
    }
};
