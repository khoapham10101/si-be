<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Product\Http\Requests\CreateProductRequest;
use Modules\Product\Http\Requests\DeleteImageProductRequest;
use Modules\Product\Http\Requests\ListProductRequest;
use Modules\Product\Http\Requests\UpdateProductRequest;
use Modules\Product\Http\Resources\ProductResource;
use Modules\Product\Repositories\ProductRepository;

class ProductController extends Controller
{
/**
     * Get all Products
     *
     * @return JsonResponse
     */
    public function index(ListProductRequest $request, ProductRepository $productRepository)
    {
        $data = $request->validated();

        $filters = $data['filters'] ?? [];
        $pagination = $data['pagination'] ?? array('per_page'=>15, 'current_page'=>1);
        $sort = $data['sort'] ?? [];

        $data = Cache::tags(['list-products'])->remember('list-products.' . $pagination['per_page'] .'.'. $pagination['current_page'], now()->addMinutes(30), function () use ($productRepository, $pagination) {
            return $productRepository->getQuery()
                    ->paginate($pagination['per_page'] ?: 999999999, ['*'], 'page', $pagination['current_page']);
        });

        return ProductResource::collection($data);
    }

    /**
     * Create a new Product
     *
    * @return JsonResponse
     */
    public function store(CreateProductRequest $request, ProductRepository $productRepository)
    {
        $data = $request->validated();
        $product = $productRepository->create($data);

        return response()->json(ProductResource::make($product), 201);
    }

    /**
     * Update the specified User
     *
     * @return JsonResponse
     */
    public function update(UpdateProductRequest $request, ProductRepository $productRepository, $productId)
    {

        $data = $request->validated();

        $product = $productRepository->getQuery()->find($productId);
        if (!$product) {
            return response()->json(['success' => false, 'message' => sprintf('Product %s not found', $productId)], 404);
        }

        $product = $productRepository->update($product, $data);

        return response()->json(ProductResource::make($product), 200);
    }

    /**
     * Delete the specified Product
     *
     * @return JsonResponse
     */
    public function delete(ProductRepository $productRepository, $productId)
    {

        $product = $productRepository->getQuery()->find($productId);
        if (!$product) {
            return response()->json(['success' => false, 'message' => sprintf('Product %s not found', $productId)], 404);
        }

        $productRepository->deleteFileStorage($product);
        $product->delete();
        return response()->json(null, 204);
    }

    /**
     * Get single product
     *
     * @return JsonResponse
     */
    public function show(ProductRepository $productRepository, $productId)
    {
        $product = $productRepository->getQuery()->find($productId);
        if (!$product) {
            return response()->json(['message' => sprintf('Product %s not found', $productId)], 404);
        }
        return response()->json(ProductResource::make($product));
    }

    /**
     * Get single product
     *
     * @return JsonResponse
     */
    public function deleteImageProduct(DeleteImageProductRequest $request, ProductRepository $productRepository, $productId)
    {
        $data = $request->validated();
        $path = $data['path'];

        $product = $productRepository->getQuery()->find($productId);
        if (!$product) {
            return response()->json(['message' => sprintf('Product %s not found', $productId)], 404);
        }

        $productRepository->deleteImageProduct($product, $path);
        return response()->json([
            'message' => 'Deleted image successfully'
        ], 200);
    }
}
