<?php

namespace Modules\Permission\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Permission\Repositories\PermissionRepository;

class PermissionController extends Controller
{
    /**
     * Get all Permissions
     * @param PermissionRepository $permissionRepository
     * @return JsonResponse
     */
    public function index(PermissionRepository $permissionRepository)
    {
        $permissions = $permissionRepository->getPermissions();
        $result = [];
        if ($permissions) {
            foreach ($permissions as $permission) {
                $module_name = $permission->module_name;
                if (!isset($result[$module_name])) {
                    $result[$module_name] = [];
                }

                $result[$module_name][] = [
                    'id'   => $permission->id,
                    'name' => $permission->name,
                    'action' => $permission->action
                ];

            }
        }

        return response()->json($result);
    }
}
