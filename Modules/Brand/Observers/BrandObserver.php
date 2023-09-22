<?php

namespace Modules\Brand\Observers;

use Illuminate\Support\Facades\Cache;
use Modules\Brand\Entities\Brand;

class BrandObserver
{
    /**
     * Handle the Brand "created" event.
     *
     * @param Brand  $brand
     * @return void
     */
    public function created(Brand $brand)
    {
        // Cache::tags(['list-brands'])->flush();
    }

    /**
     * Handle the Brand "updated" event.
     *
     * @param  Brand  $brand
     * @return void
     */
    public function updated(Brand $brand)
    {
        Cache::tags(['list-brands'])->flush();
    }

    /**
     * Handle the Brand "deleted" event.
     *
     * @param  Brand  $brand
     * @return void
     */
    public function deleted(Brand $brand)
    {
        Cache::tags(['list-brands'])->flush();
    }

    /**
     * Handle the Brand "restored" event.
     *
     * @param  Brand  $brand
     * @return void
     */
    public function restored(Brand $brand)
    {
        Cache::tags(['list-brands'])->flush();
    }

    /**
     * Handle the Brand "force deleted" event.
     *
     * @param  Brand  $brand
     * @return void
     */
    public function forceDeleted(Brand $brand)
    {
        Cache::tags(['list-brands'])->flush();
    }
}
