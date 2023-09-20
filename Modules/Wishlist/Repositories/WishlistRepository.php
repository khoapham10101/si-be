<?php

namespace Modules\Wishlist\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Modules\Wishlist\Entities\Wishlist;
use Illuminate\Database\Eloquent\Builder;

class WishlistRepository extends BaseRepository
{

    /**
     * @return Builder
     */
    public function getQuery()
    {
        return Wishlist::query()
                        ->where('user_id', '=', Auth::user()->id)
                        ->with([
                            'user',
                            'product',
                        ]);
    }

    public function findByProductId($userId, $productId)
    {
        return Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();
    }

    /**
     * @param array $data
     * @return Wishlist
     */
    public function create($data)
    {
        $wishlist = new Wishlist;

        $this->updateData($wishlist, $data);
        $wishlist->user_id = Auth::user()->id;
        $wishlist->product_id = $data['product_id'];

        $wishlist->push();

        /** @var Wishlist $refreshed */
        $refreshed = $this->getQuery()->find($wishlist->id);

        return $refreshed;
    }
}
