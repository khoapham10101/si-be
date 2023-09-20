<?php

namespace Modules\Wishlist\Http\Controllers;

use App\Traits\PaginationRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Product\Entities\Product;
use Modules\User\Entities\User;
use Modules\Wishlist\Http\Requests\WishlistCreate;
use Modules\Wishlist\Http\Resources\WishlistResource;
use Modules\Wishlist\Repositories\WishlistRepository;

class WishlistController extends Controller
{
    /**
     * Get all wishlist
     *
     * @return JsonResponse
     */
    public function index(PaginationRequest $request, WishlistRepository $wishlistRepository)
    {
        $data = $request->validated();

        $filters = $data['filters'] ?? [];
        $pagination = $data['pagination'] ?? array('per_page'=>15, 'current_page'=>1);
        $sort = $data['sort'] ?? [];

        $query = $wishlistRepository->getQuery();

        return WishlistResource::collection($query->paginate($pagination['per_page'] ?: 999999999, ['*'], 'page', $pagination['current_page']));
    }

    /**
     * Create a new wishlist
     *
     * @return JsonResponse
     */
    public function store(Request $request, WishlistRepository $wishlistRepository, $productId)
    {
        $product = Product::find($productId);
        if ($product === null) {
            return response()->json(['success' => false, 'message' => sprintf('Product %s not found', $productId)], 404);
        }

        $wishlist = $wishlistRepository->findByProductId(Auth::user()->id, $productId);
        if ($wishlist !== null) {
            return response()->json(['success' => false, 'message' => 'Product already in your wishlist'], 400);
        }

        $data = $request->all();
        $data['product_id'] = $product->id;

        $wishlist = $wishlistRepository->create($data);

        return response()->json(WishlistResource::make($wishlist), 201);
    }

    /**
     * Delete the specified wishlist
     *
     * @return JsonResponse
     */
    public function delete(WishlistRepository $wishlistRepository, $productId)
    {
        $wishlist = $wishlistRepository->getQuery()->where('product_id', $productId)->first();

        if (!$wishlist) {
            return response()->json(['success' => false, 'message' => sprintf('Product %s not found in your wishlist', $productId)], 404);
        }

        $wishlist->delete();

        return response()->json(null, 204);
    }

}
