<?php

namespace Modules\UserStatus\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\UserStatus\Entities\UserStatus;

class UserStatusDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect([
            'Active',
            'Inactive',
        ])->each(function($name) {
            if (!UserStatus::query()->where('name', $name)->exists()) {
                $status = new UserStatus;
                $status->name = $name;
                $status->save();
            }
        });
    }
}
