<?php

namespace Modules\Brand\Http\Controllers;

use App\Traits\NameDropDown;
use App\Traits\PaginationRequest;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Brand\Http\Requests\BrandCreate;
use Modules\Brand\Http\Requests\BrandUpdate;
use Modules\Brand\Http\Resources\BrandResource;
use Modules\Brand\Repositories\BrandRepository;

class BrandController extends Controller
{
    /**
     * Get all brands
     *
     * @return JsonResponse
     */
    public function index(PaginationRequest $request, BrandRepository $brandRepository)
    {
        $data = $request->validated();

        $filters = $data['filters'] ?? [];
        $pagination = $data['pagination'] ?? array('per_page'=>15, 'current_page'=>1);
        $sort = $data['sort'] ?? [];

        $data = Cache::tags(['list-brands'])->remember('list-brands.' . $pagination['per_page'] .'.'. $pagination['current_page'], now()->addMinutes(30), function () use ($brandRepository, $pagination) {
            return $brandRepository->getQuery()
                    ->orderBy('name')
                    ->paginate($pagination['per_page'] ?: 999999999, ['*'], 'page', $pagination['current_page']);
        });

        return BrandResource::collection($data);
    }

    /**
     * Get single brand
     *
     * @return JsonResponse
     */
    public function show($brandId, BrandRepository $brandRepository)
    {
        $brand = $brandRepository->getQuery()->find($brandId);
        if (!$brand) {
            return response()->json(['message' => sprintf('Brand %s not found', $brandId)], 404);
        }
        return response()->json(BrandResource::make($brand));
    }

    public function dropdown(NameDropDown $request, BrandRepository $brandRepository)
    {
        $data = $request->validated();
        $filters = $data['filters'] ?? [];

        $brand = $brandRepository->dropdown($filters);
        return $brandRepository->flattenDropdownRows(BrandResource::collection($brand));
    }

    /**
     * Create a new brand
     *
     * @return JsonResponse
     */
    public function store(BrandCreate $request, BrandRepository $brandRepository)
    {

        $data = $request->validated();
        $brand = $brandRepository->create($data);

        return response()->json(BrandResource::make($brand), 201);
    }

    /**
     * Update the specified brand
     *
     * @return JsonResponse
     */
    public function update(BrandUpdate $request, BrandRepository $brandRepository, $brandId)
    {

        $brand = $brandRepository->getQuery()->find($brandId);
        if (!$brand) {
            return response()->json(['success' => false, 'message' => sprintf('Brand %s not found', $brandId)], 404);
        }

        $data = $request->validated();
        $brand = $brandRepository->update($brand, $data);

        return response()->json(BrandResource::make($brand), 200);
    }

    /**
     * Delete the specified brand
     *
     * @return JsonResponse
     */
    public function delete(BrandRepository $brandRepository, $brandId)
    {
        $brand = $brandRepository->getQuery()->find($brandId);
        if (!$brand) {
            return response()->json(['success' => false, 'message' => sprintf('Brand %s not found', $brandId)], 404);
        }

        $brand->delete();
        return response()->json(null, 204);
    }
}
