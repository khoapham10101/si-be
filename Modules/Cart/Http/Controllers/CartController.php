<?php

namespace Modules\Cart\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Cart\Http\Requests\AddToCartRequest;
use Modules\Cart\Http\Requests\AddToCartUpdateRequest;
use Modules\Cart\Http\Resources\CartResource;
use Modules\Cart\Repositories\CartRepository;

class CartController extends Controller
{
    /**
     * Get all brands
     *
     * @return JsonResponse
     */
    public function index(Request $request, CartRepository $cartRepository)
    {
        $user = $request->user();
        $userId = $user->id;
        $listCarts = $cartRepository->listCarts($userId);

        return CartResource::collection($listCarts);
    }

    /**
     * Create a add to cart
     *
     * @return JsonResponse
     */
    public function addToCart(AddToCartRequest $request, CartRepository $cartRepository)
    {

        $data = $request->validated();
        $user = $request->user();
        $data['user_id']  = $user->id;

        $addToCart = $cartRepository->add($data);

        return response()->json(CartResource::make($addToCart), 201);
    }

    /**
     * Update the specified brand
     *
     * @return JsonResponse
     */
    public function updateMutiple(AddToCartUpdateRequest $request, CartRepository $cartRepository)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $user = $request->user();
            $userId = $user->id;
            $cartItemsData = $data['cart_items'];
            $cartIdExists = [];
            foreach ($cartItemsData as $cartItemData) {
                $cartItem = $cartRepository->getQuery()
                                ->find($cartItemData['cart_id']);

                if ($cartItem) {
                    $cartIdExists[] = $cartItemData['cart_id'];
                    unset($cartItemData['cart_id']);

                    $cartRepository->update($cartItem, $cartItemData);
                }
            }
            $cartRepository->deleteNotCartExists($cartIdExists, $userId);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return response()->json([
            'message'   => 'Updated My Cart Successfully.'
        ], 200);
    }

    /**
     * Delete the specified cart
     *
     * @return JsonResponse
     */
    public function delete(CartRepository $cartRepository, $cartId)
    {
        $cart = $cartRepository->getQuery()->find($cartId);
        if (!$cart) {
            return response()->json(['success' => false, 'message' => sprintf('Cart %s not found', $cartId)], 404);
        }

        $cart->delete();
        return response()->json(null, 204);
    }
}
