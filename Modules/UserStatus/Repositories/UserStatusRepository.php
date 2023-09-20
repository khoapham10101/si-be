<?php

namespace Modules\UserStatus\Repositories;

use App\Repositories\BaseRepository;
use Modules\UserStatus\Entities\UserStatus;
use Illuminate\Database\Eloquent\Builder;

class UserStatusRepository extends BaseRepository
{

    /**
     * @return Builder
     */
    public function getQuery()
    {
        return UserStatus::query();
    }

    /**
     * @param array $data
     * @return UserStatus
     */
    public function create($data)
    {

        $userStatus = new UserStatus;

        $this->updateData($userStatus, $data);
        $userStatus->push();

        /** @var UserStatus $refreshed */
        $refreshed = $this->getQuery()->find($userStatus->id);

        return $refreshed;
    }

    /**
     * @param UserStatus $userStatus
     * @param array $data
     * @return UserStatus
     */
    public function update($userStatus, $data)
    {
        $this->updateData($userStatus, $data);
        $userStatus->push();

        /** @var UserStatus $refreshed */
        $refreshed = $this->getQuery()->find($userStatus->id);
        return $refreshed;
    }

    /**
     * @param array $data
     * @return Collection|UserStatus[]
     */
    public function dropdown($data)
    {
        $query = $this->getQuery();

        if (!empty($data['name'] ?? null)) {
            $fields = ['name'];

            $query->where(function($query) use ($fields, $data) {
                foreach ($fields as $field) {
                    $query->orWhere($field, 'like' , '%'. $data['name'] .'%');
                }
            });
        }

        return $query->limit(config('app.dropdown.limit'))->get();
    }

}
