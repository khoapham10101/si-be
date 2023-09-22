<?php

namespace Modules\UserStatus\Http\Controllers;

use App\Traits\NameDropDown;
use App\Traits\PaginationRequest;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\UserStatus\Http\Requests\UserStatusCreate;
use Modules\UserStatus\Http\Requests\UserStatusUpdate;
use Modules\UserStatus\Http\Resources\UserStatusResource;
use Modules\UserStatus\Repositories\UserStatusRepository;

class UserStatusController extends Controller
{
    /**
     * Get all user status
     *
     * @return JsonResponse
     */
    public function index(PaginationRequest $request, UserStatusRepository $userStatusRepository)
    {
        $data = $request->validated();

        $filters = $data['filters'] ?? [];
        $pagination = $data['pagination'] ?? array('per_page'=>15, 'current_page'=>1);
        $sort = $data['sort'] ?? [];

        $data = Cache::tags(['list-userStatus'])->rememberForever('list-userStatus.' . $pagination['per_page'] .'.'. $pagination['current_page'], function () use ($userStatusRepository, $pagination) {
            return $userStatusRepository->getQuery()
                    ->orderBy('name')
                    ->paginate($pagination['per_page'] ?: 999999999, ['*'], 'page', $pagination['current_page']);
        });

        return UserStatusResource::collection($data);
    }

    /**
     * Get single user status
     *
     * @return JsonResponse
     */
    public function show($userStatusId, UserStatusRepository $userStatusRepository)
    {
        $userStatus = $userStatusRepository->getQuery()->find($userStatusId);
        if (!$userStatus) {
            return response()->json(['message' => sprintf('User Status %s not found', $userStatusId)], 404);
        }
        return response()->json(UserStatusResource::make($userStatus));
    }

    public function dropdown(NameDropDown $request, UserStatusRepository $userStatusRepository)
    {
        $data = $request->validated();
        $filters = $data['filters'] ?? [];

        $userStatus = $userStatusRepository->dropdown($filters);
        return $userStatusRepository->flattenDropdownRows(UserStatusResource::collection($userStatus));
    }

    /**
     * Create a new user status
     *
     * @return JsonResponse
     */
    public function store(UserStatusCreate $request, UserStatusRepository $userStatusRepository)
    {

        $data = $request->validated();
        $userStatus = $userStatusRepository->create($data);

        return response()->json(UserStatusResource::make($userStatus), 201);
    }

    /**
     * Update the specified user status
     *
     * @return JsonResponse
     */
    public function update(UserStatusUpdate $request, UserStatusRepository $userStatusRepository, $userStatusId)
    {

        $userStatus = $userStatusRepository->getQuery()->find($userStatusId);
        if (!$userStatus) {
            return response()->json(['success' => false, 'message' => sprintf('User Status %s not found', $userStatusId)], 404);
        }

        $data = $request->validated();
        $userStatus = $userStatusRepository->update($userStatus, $data);

        return response()->json(UserStatusResource::make($userStatus), 200);
    }

    /**
     * Delete the specified user status
     *
     * @return JsonResponse
     */
    public function delete(UserStatusRepository $userStatusRepository, $userStatusId)
    {
        $userStatus = $userStatusRepository->getQuery()->find($userStatusId);
        if (!$userStatus) {
            return response()->json(['success' => false, 'message' => sprintf('User Status %s not found', $userStatusId)], 404);
        }

        $userStatus->delete();
        return response()->json(null, 204);
    }
}
