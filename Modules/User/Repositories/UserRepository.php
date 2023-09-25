<?php

namespace Modules\User\Repositories;

use App\Repositories\BaseRepository;
use Modules\User\Entities\User;
use Illuminate\Support\Facades\Hash;
use Modules\Role\Entities\Role;

class UserRepository extends BaseRepository
{

    /**
     * @return Builder
     */
    public function getQuery()
    {
        return User::query()->with([
            'gender', 'userStatus', 'roles'
        ]);
    }

    /**
     * @param array $data
     * @return User
     */
    public function create($data)
    {

        $user = new User;

        $this->updateData($user, $data);
        if (isset($data['avatar']) && $data['avatar']->isValid()) {
            $avatarPath = $data['avatar']->store('uploads', 'public');
            $user->avatar = $avatarPath;
        }
        $user->password = Hash::make($data['password']);
        $user->push();

        // Asssign role to user when add new
        $user->roles()->attach(Role::where('name', 'user')->first());

        /** @var User $refreshed */
        $refreshed = $this->getQuery()->find($user->id);

        return $refreshed;
    }

    /**
     * @param User $user
     * @param array $data
     * @return User
     */
    public function update($user, $data)
    {
        $this->updateData($user, $data);
        $user->push();

        /** @var User $refreshed */
        $refreshed = $this->getQuery()->find($user->id);
        return $refreshed;
    }

}
