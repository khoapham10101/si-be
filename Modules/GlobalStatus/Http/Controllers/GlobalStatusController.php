<?php

namespace Modules\GlobalStatus\Http\Controllers;

use App\Traits\NameDropDown;
use App\Traits\PaginationRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\GlobalStatus\Http\Resources\GlobalStatusResource;
use Modules\GlobalStatus\Repositories\GlobalStatusRepository;

class GlobalStatusController extends Controller
{
   /**
     * Get all global status
     *
     * @return JsonResponse
     */
    public function index(PaginationRequest $request, GlobalStatusRepository $globalStatusRepository)
    {
        $data = $request->validated();

        $filters = $data['filters'] ?? [];
        $pagination = $data['pagination'] ?? array('per_page'=>15, 'current_page'=>1);
        $sort = $data['sort'] ?? [];

        $query = $globalStatusRepository->getQuery()
            ->orderBy('name');

        return GlobalStatusResource::collection($query->paginate($pagination['per_page'] ?: 999999999, ['*'], 'page', $pagination['current_page']));
    }

    /**
     * Get single global status
     *
     * @return JsonResponse
     */
    public function show($globalStatusId, GlobalStatusRepository $globalStatusRepository)
    {
        $globalStatus = $globalStatusRepository->getQuery()->find($globalStatusId);
        if (!$globalStatus) {
            return response()->json(['message' => sprintf('Global Status %s not found', $globalStatusId)], 404);
        }
        return response()->json(GlobalStatusResource::make($globalStatus));
    }

    public function dropdown(NameDropDown $request, GlobalStatusRepository $globalStatusRepository)
    {
        $data = $request->validated();
        $filters = $data['filters'] ?? [];

        $globalStatus = $globalStatusRepository->dropdown($filters);
        return $globalStatusRepository->flattenDropdownRows(GlobalStatusResource::collection($globalStatus));
    }
}
