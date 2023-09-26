<?php

namespace Modules\Brand\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Brand\Entities\Brand;

class BrandDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect([
            'Brand 1',
            'Brand 2',
        ])->each(function ($name) {
            if (!Brand::query()->where('name', $name)->exists()) {
                $status = new Brand;
                $status->name = $name;
                $status->save();
            }
        });
    }
}
