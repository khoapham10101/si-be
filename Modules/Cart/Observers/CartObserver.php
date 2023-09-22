<?php

namespace Modules\Cart\Observers;

use Illuminate\Support\Facades\Cache;
use Modules\Cart\Entities\Cart;

class CartObserver
{
    /**
     * Handle the Cart "created" event.
     *
     * @param  \App\Cart  $cart
     * @return void
     */
    public function created(Cart $cart)
    {
        Cache::forget('carts:' . $cart->user_id);
    }

    /**
     * Handle the Cart "updated" event.
     *
     * @param  \App\Cart  $cart
     * @return void
     */
    public function updated(Cart $cart)
    {
        Cache::forget('carts:' . $cart->user_id);
    }

    /**
     * Handle the Cart "deleted" event.
     *
     * @param  \App\Cart  $cart
     * @return void
     */
    public function deleted(Cart $cart)
    {
        Cache::forget('carts:' . $cart->user_id);
    }

    /**
     * Handle the Cart "restored" event.
     *
     * @param  \App\Cart  $cart
     * @return void
     */
    public function restored(Cart $cart)
    {
        //
    }

    /**
     * Handle the Cart "force deleted" event.
     *
     * @param  \App\Cart  $cart
     * @return void
     */
    public function forceDeleted(Cart $cart)
    {
        //
    }
}
