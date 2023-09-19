<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\User\Http\Requests\UserCreate;
use Modules\User\Http\Requests\UserList;
use Modules\User\Http\Requests\UserUpdate;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Repositories\UserRepository;

class UserController extends Controller
{
    /**
     * Get all User
     *
     * @return JsonResponse
     */
    public function index(UserList $request, UserRepository $userRepository)
    {
        $data = $request->validated();

        $filters = $data['filters'] ?? [];
        $pagination = $data['pagination'] ?? array('per_page'=>15, 'current_page'=>1);
        $sort = $data['sort'] ?? [];

        $query = $userRepository->getQuery();

        return UserResource::collection($query->paginate($pagination['per_page'] ?: 999999999, ['*'], 'page', $pagination['current_page']));
    }

    /**
     * Get single user
     *
     * @return JsonResponse
     */
    public function show($userId, UserRepository $userRepository)
    {
        $user = $userRepository->getQuery()->find($userId);
        if (!$user) {
            return response()->json(['message' => sprintf('User %s not found', $userId)], 404);
        }
        return response()->json(UserResource::make($user));
    }

    /**
     * Create a new User
     *
     * @return JsonResponse
     */
    public function store(UserCreate $request, UserRepository $userRepository)
    {
        $data = $request->validated();
        $user = $userRepository->create($data);

        return response()->json(UserResource::make($user), 201);
    }

    /**
     * Update the specified User
     *
     * @return JsonResponse
     */
    public function update(UserUpdate $request, UserRepository $userRepository, $userId)
    {

        $user = $userRepository->getQuery()->find($userId);
        if (!$user) {
            return response()->json(['success' => false, 'message' => sprintf('User %s not found', $userId)], 404);
        }

        $data = $request->validated();
        $user = $userRepository->update($user, $data);

        return response()->json(UserResource::make($user), 200);
    }

    /**
     * Delete the specified User
     *
     * @return JsonResponse
     */
    public function delete(UserRepository $userRepository, $userId)
    {
        $loggedInUser = auth()->user();

        $userToDelete = $userRepository->getQuery()->find($userId);
        if (!$userToDelete) {
            return response()->json(['success' => false, 'message' => sprintf('User %s not found', $userId)], 404);
        }

        if ($loggedInUser && $loggedInUser->id === $userToDelete->id) {
            return response()->json(['success' => false, 'message' => 'You cannot delete yourself'], 403);
        }

        $userToDelete->delete();
        return response()->json(null, 204);
    }
}
