<?php

namespace Modules\Auth\Repositories;

use App\Repositories\BaseRepository;
use Modules\User\Entities\User;
use Illuminate\Support\Facades\Hash;
use Modules\UserStatus\Entities\UserStatus;

class AuthRepository extends BaseRepository
{
    /**
     * @return Builder
     */
    public function getQuery()
    {
        return User::query()->with([
            'gender', 'userStatus'
        ]);
    }

    /**
     * @param array $data
     * @return User
     */
    public function register($data)
    {

        $user = new User;

        $this->updateData($user, $data);
        $user->password = Hash::make($data['password']);
        $user->user_status_id = UserStatus::active()->id;
        $user->push();

        /** @var User $refreshed */
        $refreshed = $this->getQuery()->find($user->id);

        return $refreshed;
    }
}
