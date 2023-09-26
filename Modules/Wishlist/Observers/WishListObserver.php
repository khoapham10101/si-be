<?php

namespace Modules\Wishlist\Observers;

use Illuminate\Support\Facades\Cache;
use Modules\Wishlist\Entities\WishList;

class WishListObserver
{
    /**
     * Handle the WishList "created" event.
     *
     * @param WishList  $wishList
     * @return void
     */
    public function created(WishList $wishList)
    {
        Cache::tags(['wishLists'. $wishList->user_id])->flush();
        Cache::tags(['list-products'])->flush();
    }

    /**
     * Handle the WishList "updated" event.
     *
     * @param  WishList  $wishList
     * @return void
     */
    public function updated(WishList $wishList)
    {
        Cache::tags(['wishLists'. $wishList->user_id])->flush();
        Cache::tags(['list-products'])->flush();
    }

    /**
     * Handle the WishList "deleted" event.
     *
     * @param  WishList  $wishList
     * @return void
     */
    public function deleted(WishList $wishList)
    {
        Cache::tags(['wishLists'. $wishList->user_id])->flush();
        Cache::tags(['list-products'])->flush();
    }

    /**
     * Handle the WishList "restored" event.
     *
     * @param  WishList  $wishList
     * @return void
     */
    public function restored(WishList $wishList)
    {
        Cache::tags(['wishLists'. $wishList->user_id])->flush();
        Cache::tags(['list-products'])->flush();
    }

    /**
     * Handle the WishList "force deleted" event.
     *
     * @param  WishList  $wishList
     * @return void
     */
    public function forceDeleted(WishList $wishList)
    {
        Cache::tags(['wishLists'])->flush();
        Cache::tags(['list-products'])->flush();
    }
}
