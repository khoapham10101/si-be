<?php

namespace Modules\UserStatus\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\UserStatus\Entities\UserStatus;

class UserStatusFactory extends Factory
{
    protected $model = UserStatus::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word
        ];
    }
}

