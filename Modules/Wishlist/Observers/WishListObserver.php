<?php

namespace Modules\WishList\Observers;

use Illuminate\Support\Facades\Cache;
use Modules\WishList\Entities\WishList;

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
        Cache::tags(['wishLists'])->flush();
    }

    /**
     * Handle the WishList "updated" event.
     *
     * @param  WishList  $wishList
     * @return void
     */
    public function updated(WishList $wishList)
    {
        Cache::tags(['wishLists'])->flush();
    }

    /**
     * Handle the WishList "deleted" event.
     *
     * @param  WishList  $wishList
     * @return void
     */
    public function deleted(WishList $wishList)
    {
        Cache::tags(['wishLists'])->flush();
    }

    /**
     * Handle the WishList "restored" event.
     *
     * @param  WishList  $wishList
     * @return void
     */
    public function restored(WishList $wishList)
    {
        Cache::tags(['wishLists'])->flush();
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
    }
}
