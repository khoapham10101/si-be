<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
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
        $adminREC = User::create([
            'first_name' => 'REC',
            'last_name' => 'Administrator',
            'email' => 'admin@rec.com',
            'password' => bcrypt('Rec2023!'),
            'id_card'  => '123456789',
            'user_status_id'   => UserStatus::active()->id,
            'email_verified_at' => now()
        ]);


        if ($adminRole) {
            $adminRole->users()->attach($adminREC);
        }
        // // Saler
        // User::create([
        //     'name' => 'Saler REC',
        //     'email' => 'saler@rec.com',
        //     'password' => bcrypt('Rec2023!'),
        //     'email_verified_at' => now(),
        // ]);

        // // Simple User
        // User::create([
        //     'name' => 'Simple User REC',
        //     'email' => 'user@rec.com',
        //     'password' => bcrypt('Rec2023!'),
        //     'email_verified_at' => now(),
        // ]);
    }
}
