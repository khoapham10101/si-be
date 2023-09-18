<?php

namespace Modules\GlobalStatus\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\GlobalStatus\Entities\GlobalStatus;

class GlobalStatusDatabaseSeeder extends Seeder
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
            if (!GlobalStatus::query()->where('name', $name)->exists()) {
                $status = new GlobalStatus;
                $status->name = $name;
                $status->save();
            }
        });
    }
}
