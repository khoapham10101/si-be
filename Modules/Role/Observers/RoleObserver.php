<?php

namespace Modules\Role\Observers;

use Illuminate\Support\Facades\Cache;
use Modules\Role\Entities\Role;

class RoleObserver
{
    /**
     * Handle the Role "created" event.
     *
     * @param Role  $role
     * @return void
     */
    public function created(Role $role)
    {
        Cache::tags(['list-roles'])->flush();
    }

    /**
     * Handle the Role "updated" event.
     *
     * @param  Role  $role
     * @return void
     */
    public function updated(Role $role)
    {
        Cache::tags(['list-roles'])->flush();
    }

    /**
     * Handle the Role "deleted" event.
     *
     * @param  Role  $role
     * @return void
     */
    public function deleted(Role $role)
    {
        Cache::tags(['list-roles'])->flush();
    }

    /**
     * Handle the Role "restored" event.
     *
     * @param  Role  $role
     * @return void
     */
    public function restored(Role $role)
    {
        Cache::tags(['list-roles'])->flush();
    }

    /**
     * Handle the Role "force deleted" event.
     *
     * @param  Role  $role
     * @return void
     */
    public function forceDeleted(Role $role)
    {
        Cache::tags(['list-roles'])->flush();
    }
}
