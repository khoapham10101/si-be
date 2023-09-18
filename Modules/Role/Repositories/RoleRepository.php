<?php

namespace Modules\Role\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Modules\Permission\Entities\Permission;
use Modules\RealEstate\Entities\RealEstate;
use Modules\Role\Entities\Role;

class RoleRepository extends BaseRepository
{

    /**
     * @return Builder
     */
    public function getQuery()
    {
        return Role::query()->with(['permissions']);
    }

    /**
     * @param array $data
     * @return Role
     */
    public function create($data)
    {
        DB::beginTransaction();
        try {
            $role = new Role;
            $listPermission = $data['list_permission'];
            unset($data['list_permission']);


            $this->updateData($role, $data);
            $role->push();

            $listPermission = Permission::query()->whereIn('id', $listPermission)->get();
            if ($listPermission) {
                $permissionIds = $listPermission->map->id->toArray();
                $role->permissions()->attach($permissionIds);
            }

            /** @var RealEstate $refreshed */
            $refreshed = $this->getQuery()->find($role->id);

            DB::commit();
            return $refreshed;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    /**
     * @param Role $role
     * @param array $data
     * @return Role
     */
    public function update($role, $data)
    {
        $listPermission = $data['list_permission'];
        unset($data['list_permission']);

        $this->updateData($role, $data);
        $role->push();

        $listPermission = Permission::query()->whereIn('id', $listPermission)->get();
        if ($listPermission) {
            $permissionIds = $listPermission->map->id->toArray();
            $role->permissions()->sync($permissionIds);
        }

        /** @var RealEstate $refreshed */
        $refreshed = $this->getQuery()->find($role->id);
        return $refreshed;
    }

}
