<?php

namespace Modules\UserStatus\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Permission\Entities\Permission;

class UserStatusPermissionFactory extends Factory
{
    protected $model = Permission::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'action' => $this->faker->word,
            'module_name' => $this->faker->word,
        ];
    }
}
