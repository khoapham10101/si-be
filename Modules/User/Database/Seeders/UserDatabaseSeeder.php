<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Gender\Entities\Gender;
use Modules\Role\Entities\Role;
use Modules\User\Entities\User;
use Modules\UserStatus\Entities\UserStatus;

class UserDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (User::query()->exists()) {
            return;
        }

        $adminRole = Role::find(1);

        // Administrator
        $adminSI = User::create([
            'first_name' => 'SI',
            'last_name' => 'Administrator',
            'email' => 'admin@si.com',
            'password' => bcrypt('Si2023!@'),
            'id_card'  => '123456789',
            'birthday' => '1990-09-09',
            'gender_id' => Gender::male()->id,
            'phone' => '0987654321',
            'address' => 'VN',
            'user_status_id'   => UserStatus::active()->id,
            'email_verified_at' => now()
        ]);


        if ($adminRole) {
            $adminRole->users()->attach($adminSI);
        }
    }
}
