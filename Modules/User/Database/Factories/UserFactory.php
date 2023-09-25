<?php

namespace Modules\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\Entities\User;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'first_name' => $this->faker->name,
            'last_name' => $this->faker->name,
            'id_card' => $this->faker->unique()->numerify('##########'),
            'user_status_id' => 0,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'), // Adjust the password as needed
        ];
    }
}
