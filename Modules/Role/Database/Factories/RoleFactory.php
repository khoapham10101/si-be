<?php

namespace Modules\Role\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Role\Entities\Role;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        return [
            'name' => 'admin'
        ];
    }
}
