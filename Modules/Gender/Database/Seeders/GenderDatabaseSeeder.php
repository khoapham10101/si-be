<?php

namespace Modules\Gender\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Gender\Entities\Gender;

class GenderDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect([
            'Male',
            'Female',
            'Other',
        ])->each(function ($name) {
            if (!Gender::query()->where('name', $name)->exists()) {
                $status = new Gender;
                $status->name = $name;
                $status->save();
            }
        });
    }
}
