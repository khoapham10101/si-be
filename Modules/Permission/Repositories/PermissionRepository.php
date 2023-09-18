<?php

namespace Modules\Permission\Repositories;

use App\Repositories\BaseRepository;
use Modules\Permission\Entities\Permission;

class PermissionRepository extends BaseRepository
{

    /**
     * @return Builder
     */
    public function getQuery()
    {
        return Permission::query();
    }

    public function getPermissions()
    {
        return $this->getQuery()
                    ->select('id', 'module_name', 'name', 'action')
                    ->get();
    }
}
