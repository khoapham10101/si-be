<?php

namespace Modules\Product\Observers;

use Illuminate\Support\Facades\Cache;
use Modules\Product\Entities\Product;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     *
     * @param Product  $product
     * @return void
     */
    public function created(Product $product)
    {
        Cache::tags(['list-products'])->flush();
    }

    /**
     * Handle the Product "updated" event.
     *
     * @param  Product  $product
     * @return void
     */
    public function updated(Product $cart)
    {
        Cache::tags(['list-products'])->flush();
    }

    /**
     * Handle the Product "deleted" event.
     *
     * @param  Product  $product
     * @return void
     */
    public function deleted(Product $product)
    {
        Cache::tags(['list-products'])->flush();
    }

    /**
     * Handle the Product "restored" event.
     *
     * @param  Product  $product
     * @return void
     */
    public function restored(Product $product)
    {
        Cache::tags(['list-products'])->flush();
    }

    /**
     * Handle the Product "force deleted" event.
     *
     * @param  \App\Product  $product
     * @return void
     */
    public function forceDeleted(Product $product)
    {
        Cache::tags(['list-products'])->flush();
    }
}
