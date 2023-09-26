<?php

namespace Modules\Cart\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Modules\Brand\Entities\Brand;
use Modules\Cart\Entities\Cart;

class CartRepository extends BaseRepository
{

    /**
     * @return Builder
     */
    public function getQuery()
    {
        return Cart::query()
                ->with([
                    'user',
                    'product'
                ]);
    }

    /**
     * @param string $userId
     * @return JsonResponse
     */
    public function listCarts(string $userId)
    {
        $carts = Cache::remember('carts:' . $userId, now()->addMinutes(60), function () use ($userId) {
            return $this->getQuery()
                ->where('user_id', $userId)
                ->latest()
                ->get();
        });

        return $carts;
    }

    /**
     * @param array $data
     * @return Cart
     */
    public function add($data)
    {

        $cart = $this->getQuery()
            ->where('product_id', $data['product_id'])
            ->where('user_id', $data['user_id'])
            ->first();

        if ($cart) {
            $cart->updateProductQuantity($data['quantity']);
        } else {
            $cart = new Cart();
            $this->updateData($cart, $data);
            $cart->push();
        }

        /** @var Cart $refreshed */
        $refreshed = $this->getQuery()->find($cart->id);

        return $refreshed;
    }

    /**
     * @param Brand $brand
     * @param array $data
     * @return Brand
     */
    public function update($brand, $data)
    {
        $this->updateData($brand, $data);
        $brand->push();

        /** @var Brand $refreshed */
        $refreshed = $this->getQuery()->find($brand->id);
        return $refreshed;
    }

    /**
     * @param array $cartIdExists
     * @param string $userId
     * @return void
     */
    public function deleteNotCartExists(array $cartIdExists, string $userId)
    {
        $this->getQuery()
            ->where('user_id', $userId)
            ->whereNotIn('id', $cartIdExists)
            ->delete();
    }
}
