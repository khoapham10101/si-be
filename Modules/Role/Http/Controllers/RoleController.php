<?php

namespace Modules\Role\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Role\Http\Requests\RoleCreate;
use Modules\Role\Http\Requests\RoleList;
use Modules\Role\Http\Resources\RoleResource;
use Modules\Role\Repositories\RoleRepository;

class RoleController extends Controller
{

    /**
     * Get all Role
     * @param RoleList $request
     * @param RoleRepository $roleRepository
     *
     * @return JsonResponse
     */
    public function index(RoleList $request, RoleRepository $roleRepository)
    {
        $data = $request->validated();

        $pagination = $data['pagination'] ?? array('per_page' => 15, 'current_page' => 1);

        $data = Cache::tags(['list-roles'])
            ->rememberForever(
                'list-roles.' . $pagination['per_page'] . '.' . $pagination['current_page'],
                function () use ($roleRepository, $pagination) {
                    return $roleRepository->getQuery()
                        ->paginate($pagination['per_page'] ?: 999999999, ['*'], 'page', $pagination['current_page']);
                }
            );

        return RoleResource::collection($data);
    }

    /**
     * Create a new Role
     * @param RoleCreate $request
     * @param RoleRepository $roleRepository
     *
     * @return JsonResponse
     */
    public function store(RoleCreate $request, RoleRepository $roleRepository)
    {
        $data = $request->validated();

        $role = $roleRepository->create($data);

        return response()->json([
            'message' => 'Role created successfully',
            'id'      => $role->id
        ], 201);
    }

    /**
     * Update a old Role
     * @param RoleCreate $request
     * @param RoleRepository $roleRepository
     * @param string $roleId
     *
     * @return JsonResponse
     */
    public function update(RoleCreate $request, RoleRepository $roleRepository, string $roleId)
    {
        $role = $roleRepository->getQuery()->find($roleId);
        if (!$role) {
            return response()->json(['success' => false, 'message' => sprintf('Role %s not found', $roleId)], 404);
        }

        $data = $request->validated();

        $role = $roleRepository->update($role, $data);

        return response()->json([
            'message' => 'Role updated successfully',
        ], 200);
    }

    /**
     * Delete the specified Role
     * @param string $roleId
     * @param  RoleRepository $roleRepository
     *
     * @return JsonResponse
     */
    public function show(string $roleId, RoleRepository $roleRepository)
    {
        $role = $roleRepository->getQuery()->find($roleId);
        if (!$role) {
            return response()->json(['success' => false, 'message' => sprintf('Role %s not found', $roleId)], 404);
        }

        return response()->json(RoleResource::make($role));
    }

    /**
     * Delete the specified Role
     * @param string $roleId
     * @param  RoleRepository $roleRepository
     *
     * @return JsonResponse
     */
    public function destroy(string $roleId, RoleRepository $roleRepository)
    {
        $role = $roleRepository->getQuery()->find($roleId);
        if (!$role) {
            return response()->json(['success' => false, 'message' => sprintf('Role %s not found', $roleId)], 404);
        }

        $role->users()->detach();
        $role->permissions()->detach();
        $role->delete();

        return response()->json([
            'message' => 'Role deleted successfully',
        ], 200);
    }
}
